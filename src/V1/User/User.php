<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V1\User;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V1\EqueoClient;
use Ekvio\Integration\Sdk\V2\User\UserSync;

/**
 * Class User
 * @package Ekvio\Integration\Sdk\V2\User
 */
class User implements UserSync
{
    private const USER_SYNC_ENDPOINT = '/v1/users';
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
            'users' => $users
        ]);

        if(!isset($response['success'])) {
            ApiException::apiFailed('Attribute success not set');
        }

        return $response['success'];
    }
}