<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Assignment;


interface Assignment
{
    public function search(SearchAssignmentCriteria $criteria): array;
    public function createPersonal(CreatePersonalAssignments $collection): array;
    public function createGroup(CreateGroupAssignments $collection): array;
}