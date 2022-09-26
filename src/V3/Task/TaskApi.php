<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Task;

use Ekvio\Integration\Sdk\V3\EqueoClient;

class TaskApi implements Task
{
    private const TASKS_STATISTIC_ENDPOINT = '/v2/tasks/statistic';

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
}