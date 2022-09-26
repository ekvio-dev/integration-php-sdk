<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Task;

interface Task
{
    public function statistic(TaskStatisticCriteria $criteria): array;
}