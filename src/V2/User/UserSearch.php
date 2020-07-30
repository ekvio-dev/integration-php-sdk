<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\User;

/**
 * Interface UserFinder
 * @package Ekvio\Integration\Sdk\V2\User
 */
interface UserSearch
{
    /**
     * @param UserSearchCriteria $criteria
     * @return array
     */
    public function search(UserSearchCriteria $criteria): array;
}