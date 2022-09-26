<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Task;

interface Tasks
{
    public function statistic(TaskStatisticCriteria $criteria): array;
}