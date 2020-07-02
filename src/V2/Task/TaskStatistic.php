<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Task;

/**
 * Interface TaskStatistic
 * @package Ekvio\Integration\Sdk\V2\Task
 */
interface TaskStatistic
{
    /**
     * @param array $criteria
     * @return array
     */
    public function statistic(array $criteria = []): array;
}