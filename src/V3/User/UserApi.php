<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\User;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V3\EqueoClient;

/**
 * Class User
 * @package Ekvio\Integration\Sdk\V3\User
 */
class UserApi implements User
{
    private const USER_SYNC_ENDPOINT = '/v3/users/sync';
    private const USER_LOGIN_RENAME_ENDPOINT = '/v2/users/rename';
    private const USER_SEARCH_ENDPOINT = '/v3/users/search';
    private const USER_DELETE_ENDPOINT = '/v2/users/delete';

    private const DEFAULT_PARTIAL_SYNC = false;
    private const DEFAULT_CHIEF_SYNC = false;
    private const DEFAULT_NOTIFY_USERS = false;
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
     * @param array $config
     * @return array
     * @throws ApiException
     */
    public function sync(array $users, array $config = []): array
    {
        $response = $this->client->deferredRequest('POST', self::USER_SYNC_ENDPOINT, [], [
            'data' => $users,
            'partial_sync' => $config['partial_sync'] ?? self::DEFAULT_PARTIAL_SYNC,
            'chief_sync' => $config['chief_sync'] ?? self::DEFAULT_CHIEF_SYNC,
            'notify_users' => $config['notify_users'] ?? self::DEFAULT_NOTIFY_USERS
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
        $response = $this->client->deferredRequest('DELETE', self::USER_DELETE_ENDPOINT, [], [
            'data' => [
                'login' => $criteria->login()
            ]
        ]);

        return $response['data'];
    }
}