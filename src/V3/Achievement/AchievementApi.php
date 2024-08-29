<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Achievement;

use Ekvio\Integration\Sdk\V3\EqueoClient;

class AchievementApi implements Achievement
{
    private const BADGES_STATISTIC_ENDPOINT = '/v2/badges/statistic';
    private const BADGES_SEARCH_ENDPOINT = '/v2/badges/search';

    private EqueoClient $client;

    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    public function badgesStatistic(BadgesStatisticCriteria $criteria): array
    {
        $response = $this->client->deferredRequest(
            $criteria->method(),
            self::BADGES_STATISTIC_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response['data'];
    }

    public function badgesSearch(BadgesSearchCriteria $criteria): array
    {
        $response = $this->client->pagedRequest(
            $criteria->method(),
            self::BADGES_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response;
    }
}
