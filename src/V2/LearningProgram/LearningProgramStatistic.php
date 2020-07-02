<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\LearningProgram;

/**
 * Interface LearningProgramStatistic
 * @package Ekvio\Integration\Sdk\V2\LearningProgram
 */
interface LearningProgramStatistic
{
    /**
     * @param array $criteria
     * @return array
     */
    public function statistic(array $criteria = []): array;
}