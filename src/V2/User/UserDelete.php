<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\User;

/**
 * Interface UserDelete
 * @package Ekvio\Integration\Sdk\V2\User
 */
interface UserDelete
{
    /**
     * @param UserDeleteCriteria $criteria
     * @return array
     */
    public function delete(UserDeleteCriteria $criteria): array;
}