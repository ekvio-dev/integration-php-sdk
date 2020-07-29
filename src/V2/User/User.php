<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\User;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V2\EqueoClient;

/**
 * Class User
 * @package Ekvio\Integration\Sdk\V2\User
 */
class User implements UserSync, LoginRename
{
    private const USER_SYNC_ENDPOINT = '/v2/users/sync';
    private const USER_LOGIN_RENAME_ENDPOINT = '/v2/users/rename';
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
}