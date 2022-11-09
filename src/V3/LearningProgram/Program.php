<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\LearningProgram;

/**
 * Interface Program
 * @package Ekvio\Integration\Sdk\V3\LearningProgram
 */
interface Program
{
    /**
     * @param ProgramSearchCriteria $criteria
     * @return array
     */
    public function search(ProgramSearchCriteria $criteria): array;

    /**
     * @param ProgramStatisticCriteria $criteria
     * @return array
     */
    public function statistic(ProgramStatisticCriteria $criteria): array;

    /**
     * @param ProgramSearchCategoryCriteria $criteria
     * @return array
     */
    public function searchCategories(ProgramSearchCategoryCriteria $criteria): array;
}