<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Task;

use Ekvio\Integration\Sdk\V3\EqueoClient;

class TaskApi implements Task
{
    private const TASKS_STATISTIC_ENDPOINT = '/v2/tasks/statistic';
    private const TASKS_FIELDS_SEARCH_ENDPOINT = '/v2/tasks/fields/search';
    private const TASK_STATUSES_UPDATE_ENDPOINT = '/v2/tasks/answers/statuses';

    private EqueoClient $client;

    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    public function statistic(TaskStatisticCriteria $criteria): array
    {
        $response = $this->client->deferredRequest(
            $criteria->method(),
            self::TASKS_STATISTIC_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response['data'];
    }

    public function search(TaskSearchCriteria $criteria): array
    {
        $response = $this->client->pagedRequest(
            $criteria->method(),
            self::TASKS_FIELDS_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response;
    }

    public function updateStatuses(array $statuses): array
    {
        $response = $this->client->deferredRequest(
            'PUT',
            self::TASK_STATUSES_UPDATE_ENDPOINT,
            [],
            [
                'data' => $statuses
            ]
        );

        return $response['data'];
    }
}
