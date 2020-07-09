<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\LearningProgram;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V2\EqueoClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class LearningProgram
 * @package Ekvio\Integration\Sdk\V2\LearningProgram
 */
class LearningProgram implements LearningProgramStructure, LearningProgramStatistic
{
    private const LEARNING_PROGRAMS_STRUCTURE_ENDPOINT = '/v2/learning-programmes';
    private const LEARNING_PROGRAMS_STATISTIC_ENDPOINT = '/v2/learning-programmes/statistic';
    /**
     * @var EqueoClient
     */
    private $client;

    /**
     * LearningProgram constructor.
     * @param EqueoClient $client
     */
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $criteria
     * @return array
     * @throws GuzzleException
     */
    public function structure(array $criteria = []): array
    {
        $fields = [];
        if(array_key_exists('fields', $criteria)) {
            $fields['fields'] = $criteria['fields'];
        }

        if(array_key_exists('program_status', $criteria)) {
            $fields['program_status'] = $criteria['program_status'];
        }

        if(array_key_exists('material_status', $criteria)) {
            $fields['material_status'] = $criteria['material_status'];
        }

        if(array_key_exists('included', $criteria)) {
            $fields['included'] = $criteria['included'];
        }

        $response = $this->client->request('GET', self::LEARNING_PROGRAMS_STRUCTURE_ENDPOINT, $fields);

        if(isset($response['errors'])) {
            ApiException::apiErrors($response['errors']);
        }

        return $response['data'];
    }

    /**
     * @param array $criteria
     * @return array
     * @throws GuzzleException
     */
    public function statistic(array $criteria = []): array
    {
        $fields = [];
        if(array_key_exists('program_status', $criteria)) {
            $fields['program_status'] = $criteria['program_status'];
        }

        if(array_key_exists('user_status', $criteria)) {
            $fields['user_status'] = $criteria['user_status'];
        }

        if(array_key_exists('from_date', $criteria)) {
            $fields['from_date'] = $criteria['from_date'];
        }

        if(array_key_exists('material_status', $criteria)) {
            $fields['material_status'] = $criteria['material_status'];
        }

        $response = $this->client->request('GET', self::LEARNING_PROGRAMS_STATISTIC_ENDPOINT, $fields);

        if(isset($response['errors'])) {
            ApiException::apiErrors($response['errors']);
        }

        $integration = (int) $response['data']['integration'];
        $content = $this->client->integration($integration);

        return $content['data'];
    }
}