<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Group;

/**
 * Interface Group
 * @package Ekvio\Integration\Sdk\Common\Group
 */
interface Group
{
    /**
     * @param GroupSearchCriteria $criteria
     * @return array
     */
    public function search(GroupSearchCriteria $criteria): array;

    /**
     * @param array $data
     * @return array
     */
    public function update(array $data): array;

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array;

    /**
     * @param array $data
     * @return array
     */
    public function delete(array $data): array;
}