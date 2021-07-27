<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\Tests\V2;

use Ekvio\Integration\Sdk\V2\EqueoClient;
use Ekvio\Integration\Sdk\V2\Integration\HttpIntegrationResult;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class EqueoClientTest
 * @package Ekvio\Integration\Sdk\Tests\V2
 */
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
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345');
        $equeoClient->request('GET', '/v2/test/me', ['param' => 1]);

        foreach ($container as $transaction) {
            /** @var Request $request */
            $request = $transaction['request'];
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('test.dev', $request->getUri()->getHost());
            $this->assertEquals('/v2/test/me', $request->getUri()->getPath());
            $this->assertEquals('param=1', $request->getUri()->getQuery());
        }
    }

    private function getMockClient(array &$container, array $responses = []): Client
    {
        if(!$responses) {
            $responses = [new Response(200, ['X-Foo' => 'Bar'], '{"data": 1}')];
        }

        $mock = new MockHandler($responses);
        $history = Middleware::history($container);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        return new Client(['handler' => $handlerStack]);
    }
}