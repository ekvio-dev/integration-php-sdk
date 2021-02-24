<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\LearningProgram;

/**
 * Interface LearningProgramStructure
 * @package Ekvio\Integration\Sdk\V2\LearningProgram
 * @deprecated
 */
interface LearningProgramStructure
{
    /**
     * @param array $criteria
     * @return array
     */
    public function structure(array $criteria = []): array;
}