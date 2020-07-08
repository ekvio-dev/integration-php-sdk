<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\User;

use Ekvio\Integration\Sdk\V2\ApiException;
use Ekvio\Integration\Sdk\V2\EqueoClient;

/**
 * Class User
 * @package Ekvio\Integration\Sdk\V2\User
 */
class User implements UserSync
{
    private const USER_SYNC_ENDPOINT = '/v2/users/sync';
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
     * @throws ApiException|\Ekvio\Integration\Sdk\V2\ApiException
     */
    public function sync(array $users): array
    {
        $response = $this->client->deferredRequest('POST', self::USER_SYNC_ENDPOINT, [], [
            'data' => $users
        ]);

        return $response['data'];
    }
}