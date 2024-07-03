<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\Tests\V3;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\Common\Integration\HttpIntegrationResult;
use Ekvio\Integration\Sdk\Tests\HttpDummyResult;
use Ekvio\Integration\Sdk\V3\EqueoClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EqueoClientTest extends TestCase
{
    public function testRaiseExceptionIfNoApiHost()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('API host required');
        $client = new Client();
        new EqueoClient($client, new HttpIntegrationResult(), '', '12345');
    }

    public function testRaiseExceptionIfNoApiToken()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('API token required');
        $client = new Client();
        new EqueoClient($client, new HttpIntegrationResult(), 'http://test.dev', '');
    }

    public function testRawEqueoClientRequest()
    {
        $container = [];
        $client = $this->getMockClient($container);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev/meta', '12345', $this->defaultOptions());
        $equeoClient->request('GET', '/users/search', ['param' => 1]);

        foreach ($container as $transaction) {
            /** @var Request $request */
            $request = $transaction['request'];
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('test.dev', $request->getUri()->getHost());
            $this->assertEquals('/meta/users/search', $request->getUri()->getPath());
            $this->assertEquals('param=1', $request->getUri()->getQuery());
        }
    }

    public function testRaiseExceptionWhenDeferredRequestReturnBadIntegrationStructureResponse()
    {
        $this->expectException(ApiException::class);
        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, ['X-Foo' => 'Bar'], '{"data":{"integra":100}}')
        ]);

        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev/meta', '12345', $this->defaultOptions());
        $equeoClient->deferredRequest('POST', '/meta/v2/users/sync', [], ['data' => []]);
    }

    public function testRaiseExceptionWhenDeferredRequestReturnNotNaturalIntegrationId()
    {
        $this->expectException(ApiException::class);
        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, ['X-Foo' => 'Bar'], '{"data":{"integration":-100}}')
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345', $this->defaultOptions());
        $equeoClient->deferredRequest('POST', '/v2/users/sync', [], ['data' => []]);
    }

    public function testRaiseExceptionWhenDeferredRequestReturnErrorsInIntegrationResponse()
    {
        $this->expectException(ApiException::class);
        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, ['X-Foo' => 'Bar'], '{"data":{"integration":100}}'),
            new Response(200, [], '{"errors":[{"code": 100, "message": "Bad request"}]}')
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345', $this->defaultOptions());
        $equeoClient->deferredRequest('POST', '/v2/users/sync', [], ['data' => []]);
    }

    public function testRaiseExceptionWhenDeferredRequestReturnInvalidStructureResponse()
    {
        $this->expectException(ApiException::class);
        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, ['X-Foo' => 'Bar'], '{"data":{"integration":100}}'),
            new Response(200, [], '{"data":{"invalid_status":"failed"}}')
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345', $this->defaultOptions());
        $equeoClient->deferredRequest('POST', '/v2/users/sync', [], ['data' => []]);
    }

    public function testRaiseExceptionWhenDeferredRequestReturnResponseWithoutFileLink()
    {
        $this->expectException(ApiException::class);
        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, ['X-Foo' => 'Bar'], '{"data":{"integration":100}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"completed"}}'),
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345', [
                'request_interval' => false
            ] + $this->defaultOptions());
        $equeoClient->deferredRequest('POST', '/users/sync', ['data' => []]);
    }

    public function testRaiseExceptionWhenDeferredRequestFailedAfterExceedRetryCount()
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Bad request: integration 100 status progress was not change or data from link  is empty');

        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, ['X-Foo' => 'Bar'], '{"data":{"integration":100}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"completed"}}'),
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345', [
                'request_interval' => false,
                'retry_count' => 3
            ] + $this->defaultOptions());

        $equeoClient->deferredRequest('POST', '/users/sync', [], ['data' => []]);
    }

    public function testRaiseExceptionWhenDeferredRequestFailedAfterExceedRetryCountWithEmptyBody()
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Bad request: integration 100 status completed was not change or data from link http://dev.link is empty');

        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, ['X-Foo' => 'Bar'], '{"data":{"integration":100}}'),
            new Response(200, [], '{"data":{"status":"completed","file":"http://dev.link"}}'),
            new Response(200, [], '{"data":{"status":"completed","file":"http://dev.link"}}'),
            new Response(200, [], '{"data":{"status":"completed","file":"http://dev.link"}}'),
            new Response(200, [], '{"data":{"status":"completed","file":"http://dev.link"}}'),
            new Response(200, [], '{"data":{"status":"completed","file":"http://dev.link"}}'),
            new Response(200, [], '{"data":{"status":"completed","file":"http://dev.link"}}'),
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult('null'), 'http://test.dev', '12345', [
                'request_interval' => false,
                'retry_count' => 3
            ] + $this->defaultOptions());

        $equeoClient->deferredRequest('POST', '/users/sync', [], ['data' => []]);
    }

    public function testSuccessDeferredRequest()
    {
        $responses = [
            new Response(200, ['X-Foo' => 'Bar'], '{"data":{"integration":100}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"completed", "file": "http://result.link"}}'),
        ];

        $container = [];
        $client = $this->getMockClient($container, $responses);

        $equeoClient = new EqueoClient($client, new HttpDummyResult('{"data": [{"field": "Hello"}]}'), 'http://test.dev', '12345', [
            'request_interval' => false
        ]);
        $response = $equeoClient->deferredRequest('POST', '/users/sync', ['data' => []]);

        $this->assertCount(count($responses), $container);
        $this->assertEquals(['data' => [['field' => 'Hello']]], $response);
    }

    public function testSuccessRequestRetryMechanism()
    {
        $result = '{"data":[{"user":1}],"meta":{"pagination":{"total":2,"count":1,"per_page":1,"current_page":1,"total_pages":2,"links":{"next":"/v2/url?page=2"}}}}';
        $responses = [
            new Response(502, [], null),
            new Response(502, [], null),
            new Response(200, [], $result),
        ];

        $container = [];
        $client = $this->getMockClient($container, $responses);

        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345', [
            'request_retry_count' => 3,
            'request_retry_timeout' => 0
        ]);

        $response = $equeoClient->request('GET', 'users/search');

        $this->assertEquals(json_decode($result, true), $response);
    }

    public function testFailedInMainDeferredRequestWithRetryMechanism()
    {
        $this->expectException(ApiException::class);

        $result = '{"data": [{"status":"created"},{"status":"updated"}}';
        $responses = [
            new Response(502, [], null),
            new Response(502, [], null),
            new Response(502, [], null),
            new Response(200, [], $result),
        ];

        $container = [];
        $client = $this->getMockClient($container, $responses);

        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345', [
            'request_retry_count' => 3,
            'request_retry_timeout' => 0
        ]);
        $equeoClient->deferredRequest('POST', '/users/sync', [], ['data' => []]);
    }

    public function testFailedInIntegrationRequestWithRetryMechanism()
    {
        $this->expectException(ApiException::class);

        $responses = [
            new Response(502, [], null),
            new Response(200, [], '{"data":{"integration":100}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(502, [], null),
            new Response(502, [], null),
            new Response(502, [], null),
            new Response(502, [], null),
        ];

        $container = [];
        $client = $this->getMockClient($container, $responses);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345', [
            'request_interval' => false,
            'request_retry_count' => 3,
            'request_retry_timeout' => 0
        ]);

        $equeoClient->deferredRequest('POST', '/users/sync', [], ['data' => []]);
    }

    public function testSuccessDeferredRequestWithRetryMechanism()
    {
        $result = '{"data": [{"status":"created"},{"status":"updated"}]}';
        $responses = [
            new Response(502, [], null),
            new Response(200, [], '{"data":{"integration":100}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(200, [], '{"data":{"status":"progress"}}'),
            new Response(502, [], null),
            new Response(200, [], '{"data":{"status":"completed", "file": "http://result.link"}}'),
        ];

        $container = [];
        $client = $this->getMockClient($container, $responses);
        $equeoClient = new EqueoClient($client, new HttpDummyResult($result), 'http://test.dev', '12345', [
            'request_interval' => false,
            'request_retry_count' => 1,
            'request_retry_timeout' => 0
        ]);

        $response = $equeoClient->deferredRequest('POST', '/users/sync', [], ['data' => []]);
        $this->assertArrayHasKey('data', $response);
        $this->assertCount(2, $response['data']);
    }

    public function testSuccessPagedRequest()
    {
        $responses = [
            new Response(200, [], '{"data":[{"user": 1}], "meta": {"pagination":{"total": 2,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 2,"links": {"next": "/v2/url?page=2"}}}}'),
            new Response(200, [], '{"data":[{"user": 2}], "meta": {"pagination":{"total": 2,"count": 1,"per_page": 1,"current_page": 2,"total_pages": 2,"links": {"previous":"/v2/url?page=1"}}}}'),
        ];

        $container = [];
        $client = $this->getMockClient($container, $responses);

        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345');
        $response = $equeoClient->pagedRequest('GET', 'users/search');

        $this->assertCount(count($responses), $container);
        $this->assertEquals([['user' => 1], ['user' => 2]], $response);
    }

    public function testSuccessPagedRequestWithRetryMechanism()
    {
        $responses = [
            new Response(502, [], null),
            new Response(200, [], '{"data":[{"user": 1}], "meta": {"pagination":{"total": 2,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 2,"links": {"next": "/v2/url?page=2"}}}}'),
            new Response(502, [], null),
            new Response(200, [], '{"data":[{"user": 2}], "meta": {"pagination":{"total": 2,"count": 1,"per_page": 1,"current_page": 2,"total_pages": 2,"links": {"previous":"/v2/url?page=1"}}}}'),
        ];

        $container = [];
        $client = $this->getMockClient($container, $responses);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345', $this->defaultOptions());
        $response = $equeoClient->pagedRequest('GET', 'users/search');

        $this->assertCount(2, $response);
    }

    private function getMockClient(array &$container, array $responses = []): Client
    {
        if(!$responses) {
            $responses = [new Response(200, ['X-Foo' => 'Bar'], '{"data": []}')];
        }

        $mock = new MockHandler($responses);
        $history = Middleware::history($container);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        return new Client(['handler' => $handlerStack]);
    }

    private function defaultOptions(): array
    {
        return [
            'request_retry_count' => 3,
            'request_retry_timeout' => 0
        ];
    }
}