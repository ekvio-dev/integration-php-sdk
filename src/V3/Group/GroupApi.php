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
    private const GROUP_UPDATE_ENDPOINT = '/v2/groups/delete';

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
}