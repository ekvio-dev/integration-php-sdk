<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Event;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V2\EqueoClient;

/**
 * Class EventApi
 * @package Ekvio\Integration\Sdk\V2\Event
 */
class EventApi implements Event
{
    private const EVENTS_SEARCH_ENDPOINT = '/v2/events';
    private const EVENTS_STATISTIC_ENDPOINT = '/v2/events/statistic';
    private EqueoClient $client;

    /**
     * LearningProgram constructor.
     * @param EqueoClient $client
     */
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param EventSearchCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function search(EventSearchCriteria $criteria): array
    {
        return $this->client->pagedRequest($criteria->method(), self::EVENTS_SEARCH_ENDPOINT, $criteria->queryParams());
    }

    /**
     * @param EventStatisticCriteria $criteria
     * @return array
     */
    public function statistic(EventStatisticCriteria $criteria): array
    {
        $response = $this->client->deferredRequest($criteria->method(), self::EVENTS_STATISTIC_ENDPOINT, $criteria->queryParams());
        return $response['data'];
    }
}