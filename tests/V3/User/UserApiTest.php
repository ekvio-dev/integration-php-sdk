<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\Tests\V3\User;

use Ekvio\Integration\Sdk\Tests\HttpDummyResult;
use Ekvio\Integration\Sdk\V3\EqueoClient;
use Ekvio\Integration\Sdk\V3\User\UserApi;
use Ekvio\Integration\Sdk\V3\User\UserDeleteCriteria;
use Ekvio\Integration\Sdk\V3\User\UserSearchCriteria;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class UserApiTest extends TestCase
{
    public function testApiUsersSyncRequest()
    {
        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, [], '{"data": {"integration": 1}}'),
            new Response(200, [], '{"data": {"status": "completed", "file": "link-me"}}')
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345');
        $userApi = new UserApi($equeoClient);
        $userApi->sync([['login' => 'test']]);

        /** @var Request $request */
        $request = $container[0]['request'];
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('test.dev', $request->getUri()->getHost());
        $this->assertEquals('/v3/users/sync', $request->getUri()->getPath());
    }

    public function testApiUsersSearchRequest()
    {
        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, [], '{"data":[]}'),
            new Response(200, [], '{"data":[]}'),
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345');
        $userApi = new UserApi($equeoClient);
        $userApi->search(UserSearchCriteria::createFrom([]));
        $userApi->search(UserSearchCriteria::createFrom(['params' => [
            'fields' => ['login','first_name'],
            'include' => ['groups']
        ]]));

        /** @var Request $request */
        $request = $container[0]['request'];
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('test.dev', $request->getUri()->getHost());
        $this->assertEquals('/v3/users/search', $request->getUri()->getPath());

        /** @var Request $request */
        $request = $container[1]['request'];
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('test.dev', $request->getUri()->getHost());
        $this->assertEquals('/v3/users/search', $request->getUri()->getPath());
        $this->assertEquals('fields=login,first_name&include=groups', $request->getUri()->getQuery());
    }

    public function testApiUsersRenameRequest()
    {
        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, [], '{"data": {"integration": 1}}'),
            new Response(200, [], '{"data": {"status": "completed", "file": "link-me"}}')
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345');
        $userApi = new UserApi($equeoClient);
        $loginsRename = [
            ['from' => 'test', 'to' => 'test2'],
            ['from' => 'test3', 'to' => 'test4']
        ];
        $userApi->rename($loginsRename);

        /** @var Request $request */
        $request = $container[0]['request'];
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('test.dev', $request->getUri()->getHost());
        $this->assertEquals('/v2/users/rename', $request->getUri()->getPath());

        $body = (string) $request->getBody();
        $data = json_decode($body, true);
        $this->assertEquals($data['data'], $loginsRename);
    }

    public function testApiUsersDeleteRequest()
    {
        $container = [];
        $client = $this->getMockClient($container, [
            new Response(200, [], '{"data": {"integration": 1}}'),
            new Response(200, [], '{"data": {"status": "completed", "file": "link-me"}}')
        ]);
        $equeoClient = new EqueoClient($client, new HttpDummyResult(), 'http://test.dev', '12345');
        $userApi = new UserApi($equeoClient);

        $loginsDeleteCriteria = ['login' => ['test', 'test2', 'test3']];
        $userApi->delete(UserDeleteCriteria::createFrom($loginsDeleteCriteria));

        /** @var Request $request */
        $request = $container[0]['request'];
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('test.dev', $request->getUri()->getHost());
        $this->assertEquals('/v2/users/delete', $request->getUri()->getPath());

        $body = (string) $request->getBody();
        $data = json_decode($body, true);
        $this->assertEquals($data['data'], $loginsDeleteCriteria);
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
}