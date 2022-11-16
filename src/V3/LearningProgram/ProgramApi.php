<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\LearningProgram;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V3\EqueoClient;

/**
 * Class ProgramApi
 * @package Ekvio\Integration\Sdk\V3\LearningProgram
 */
class ProgramApi implements Program
{
    private const LEARNING_PROGRAMS_STRUCTURE_ENDPOINT = '/v3/learning-programmes';
    private const LEARNING_PROGRAMS_CATEGORIES_ENDPOINT = '/v3/learning-programmes/categories';
    private const LEARNING_PROGRAMS_STATISTIC_ENDPOINT = '/v3/learning-programmes/statistic';
    private const LEARNING_PROGRAMS_ASSIGNMENT_PERSONAL = '/v3/learning-programmes/assignments/personal';
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
     * @param ProgramSearchCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function search(ProgramSearchCriteria $criteria): array
    {
        $response = $this->client->request(
            $criteria->method(),
            self::LEARNING_PROGRAMS_STRUCTURE_ENDPOINT,
            $criteria->queryParams()
        );
        return $response['data'];
    }

    /**
     * @param ProgramSearchCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function searchCategories(ProgramSearchCategoryCriteria $criteria): array
    {
        $response = $this->client->request(
            $criteria->method(),
            self::LEARNING_PROGRAMS_CATEGORIES_ENDPOINT,
            $criteria->queryParams()
        );
        return $response['data'];
    }

    /**
     * @param ProgramStatisticCriteria $criteria
     * @return array
     * @throws ApiException
     */
    public function statistic(ProgramStatisticCriteria $criteria): array
    {
        $response = $this->client->deferredRequest(
            $criteria->method(),
            self::LEARNING_PROGRAMS_STATISTIC_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );
        return $response['data'];
    }

    /**
     * @param array $assignments
     * @return array
     * @throws ApiException
     */
    public function createIndividualAssignments(array $assignments): array
    {
        $response = $this->client->deferredRequest(
            'POST',
            self::LEARNING_PROGRAMS_ASSIGNMENT_PERSONAL,
            [],
            ['data' => $assignments]
        );

        return $response['data'];
    }
}