<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\User;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V2\EqueoClient;

/**
 * Class User
 * @package Ekvio\Integration\Sdk\V2\User
 */
class User implements UserSync, LoginRename, UserSearch, UserDelete
{
    private const USER_SYNC_ENDPOINT = '/v2/users/sync';
    private const USER_LOGIN_RENAME_ENDPOINT = '/v2/users/rename';
    private const USER_SEARCH_ENDPOINT = '/v2/users/search';
    private const USER_DELETE_ENDPOINT = '/v2/users/delete';
    /**
     * @var EqueoClient
     */
    private $client;

    /**
     * Material constructor.
     * @param EqueoClient $client
     */
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $users
     * @return array
     * @throws ApiException
     */
    public function sync(array $users): array
    {
        $response = $this->client->deferredRequest('POST', self::USER_SYNC_ENDPOINT, [], [
            'data' => $users
        ]);

        return $response['data'];
    }

    /**
     * @param array $logins
     * @return array
     * @throws ApiException
     */
    public function rename(array $logins): array
    {
        $response = $this->client->deferredRequest('POST', self::USER_LOGIN_RENAME_ENDPOINT, [], [
            'data' => $logins
        ]);

        return $response['data'];
    }

    /**
     * @param UserSearchCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function search(UserSearchCriteria $criteria): array
    {
        $method = 'GET';
        $body = [];
        if($criteria->filters()) {
            $method = 'POST';
            $body['filters'] = $criteria->filters();
        }
        return $this->client->pagedRequest($method, self::USER_SEARCH_ENDPOINT, $criteria->params(), $body);
    }

    /**
     * @param UserDeleteCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function delete(UserDeleteCriteria $criteria): array
    {
        $response = $this->client->deferredRequest('POST', self::USER_DELETE_ENDPOINT, [], [
            'data' => [
                'login' => $criteria->login()
            ]
        ]);

        return $response['data'];
    }
}