<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Group;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V3\EqueoClient;

/**
 * Class Group
 * @package Ekvio\Integration\Sdk\V3\Group
 */
class GroupApi implements Group
{
    private const GROUP_SEARCH_ENDPOINT = '/v3/groups/search';
    private const GROUP_UPDATE_ENDPOINT = '/v3/groups/update';
    private const GROUP_CREATE_ENDPOINT = '/v3/groups/create';
    private const GROUP_DELETE_ENDPOINT = '/v3/groups/delete';

    /**
     * @var EqueoClient
     */
    private EqueoClient $client;

    /**
     * @param EqueoClient $client
     */
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param GroupSearchCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function search(GroupSearchCriteria $criteria): array
    {
        $method = 'POST';
        $body = [];
        if($criteria->filters()) {
            $body['filters'] = $criteria->filters();
        }
        return $this->client->pagedRequest($method, self::GROUP_SEARCH_ENDPOINT, $criteria->params(), $body);
    }

    /**
     * @param GroupUpdateCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function update(GroupUpdateCriteria $criteria): array
    {
        $response = $this->client->deferredRequest('POST', self::GROUP_UPDATE_ENDPOINT, [], [
            'data' => $criteria->data()
        ]);

        return $response['data'];
    }

    /**
     * @param GroupCreateCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function create(GroupCreateCriteria  $criteria): array
    {
        $response = $this->client->deferredRequest('POST', self::GROUP_CREATE_ENDPOINT, [], [
            'data' => $criteria->data()
        ]);

        return $response['data'];
    }

    /**
     * @param GroupDeleteCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function delete(GroupDeleteCriteria  $criteria): array
    {
        $response = $this->client->deferredRequest('DELETE', self::GROUP_DELETE_ENDPOINT, [], [
            'data' => $criteria->data()
        ]);

        return $response['data'];
    }
}