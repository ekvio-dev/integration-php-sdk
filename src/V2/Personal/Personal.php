<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Personal;
/**
 * Interface Personal
 */
interface Personal
{
    public function assignments(AssignmentSearchCriteria $criteria): array;
}