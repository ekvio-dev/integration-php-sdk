<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Task;

interface Task
{
    public function getTasks(TaskGetCriteria $criteria): array;
    public function statistic(TaskStatisticCriteria $criteria): array;
    public function search(TaskSearchCriteria $criteria): array;
    public function fields(TaskFieldSearchCriteria $criteria): array;
    public function updateStatuses(array $statuses): array;
}