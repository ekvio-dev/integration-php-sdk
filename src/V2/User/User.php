<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\User;

/**
 * Interface User
 * @package Ekvio\Integration\Sdk\V2\User
 */
interface User
{
    /**
     * @param UserSearchCriteria $criteria
     * @return array
     */
    public function search(UserSearchCriteria $criteria): array;
    /**
     * @param array $users
     * @return array
     */
    public function sync(array $users): array;
    /**
     * @param UserDeleteCriteria $criteria
     * @return array
     */
    public function delete(UserDeleteCriteria $criteria): array;
    /**
     * @param array $logins
     * @return array
     */
    public function rename(array $logins): array;
}