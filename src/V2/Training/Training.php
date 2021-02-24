<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Training;

/**
 * Interface Training
 * @package Ekvio\Integration\Sdk\V2\Course
 */
interface Training
{
    /**
     * @param TrainingSearchCriteria $criteria
     * @return array
     */
    public function search(TrainingSearchCriteria $criteria): array;

    /**
     * @param TrainingSearchCriteria $criteria
     * @return array
     */
    public function statistic(TrainingSearchCriteria $criteria): array;
}