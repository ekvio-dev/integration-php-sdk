<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Personal;

use Ekvio\Integration\Sdk\V2\ApiException;
use Ekvio\Integration\Sdk\V2\EqueoClient;

/**
 * Class Personal
 * @package Ekvio\Integration\Sdk\V2\Personal
 */
class Personal implements PersonalStatus
{
    private const PERSONAL_STATUSES_UPDATE_ENDPOINT = '/v1/personal_data/statuses';
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
     * @param array $statuses
     */
    public function updateStatuses(array $statuses): void
    {
        $response = $this->client->request('POST', self::PERSONAL_STATUSES_UPDATE_ENDPOINT, [], [
            'statuses' => $statuses
        ]);

        //v1 handle errors
        if(isset($response['error'])) {
            throw ApiException::apiFailed(sprintf('Api failed %s:%s', $response['error']['code'], $response['error']['message']));
        }

        if(!isset($response['success'])) {
            throw ApiException::apiFailed('Unknown response status');
        }
    }
}