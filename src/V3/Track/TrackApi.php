<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Track;

use Ekvio\Integration\Sdk\V3\EqueoClient;

class TrackApi implements Track
{
    private const CHUNK = 500;
    private const TRACK_STATISTIC_ENDPOINT = '/v3/tracks/statistic';
    private const TRACK_COLD_STATISTIC_ENDPOINT = '/v3/tracks/statistic/cold';
    private const TRACK_SEARCH_ENDPOINT = '/v3/tracks/search';
    private const TRACK_SEARCH_CONTENT_ENDPOINT = '/v3/tracks/content/search';

    private EqueoClient $client;

    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    public function statistic(TrackStatisticCriteria $criteria): array
    {
        $response = $this->client->deferredRequest(
            $criteria->method(),
            self::TRACK_STATISTIC_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response['data'];
    }

    public function statisticCold(TrackColdStatisticCriteria $criteria): array
    {
        $response = $this->client->deferredRequest(
            $criteria->method(),
            self::TRACK_COLD_STATISTIC_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response['data'];
    }

    public function search(TrackSearchCriteria $criteria): array
    {
        $response = $this->client->pagedRequest(
            $criteria->method(),
            self::TRACK_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response;
    }

    public function searchContent(TrackSearchContentCriteria $criteria): array
    {
        $response = $this->client->pagedRequest(
            $criteria->method(),
            self::TRACK_SEARCH_CONTENT_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response;
    }
}