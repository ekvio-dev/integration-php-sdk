<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Training;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V2\EqueoClient;

/**
 * Class TrainingApi
 * @package Ekvio\Integration\Sdk\V2\Training
 */
class TrainingApi implements Training
{
    private const TRAININGS_SEARCH_ENDPOINT = '/v2/trainings';
    private const TRAININGS_STATISTIC_ENDPOINT = '/v2/trainings/statistic';
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
     * @param TrainingSearchCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function search(TrainingSearchCriteria $criteria): array
    {
        $response = $this->client->pagedRequest($criteria->method(), self::TRAININGS_SEARCH_ENDPOINT, $criteria->queryParams());
        return $response['data'];
    }

    /**
     * @param TrainingSearchCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function statistic(TrainingSearchCriteria $criteria): array
    {
        $response = $this->client->deferredRequest($criteria->method(), self::TRAININGS_STATISTIC_ENDPOINT, $criteria->queryParams());
        return $response['data'];
    }
}