<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Form;

/**
 * Interface Form
 * @package Ekvio\Integration\Sdk\V3\Form
 */
interface Form
{
    /**
     * @param FormSearchAutoAssignmentsCriteria $criteria
     * @return array
     */
    public function searchAutoAssignments(FormSearchAutoAssignmentsCriteria $criteria): array;

    /**
     * @param array $autoAssignments
     * @return array
     */
    public function createAutoAssignments(array $autoAssignments): array;

    /**
     * @param array $autoAssignments
     * @return array
     */
    public function updateAutoAssignments(array $autoAssignments): array;

    /**
     * @param array $autoAssignments
     * @return array
     */
    public function deleteAutoAssignments(array $autoAssignments): array;
}
