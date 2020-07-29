<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\User;

/**
 * Interface LoginRename
 * @package Ekvio\Integration\Sdk\V2\User
 */
interface LoginRename
{
    /**
     * @param array $logins
     * @return array
     */
    public function rename(array $logins): array;
}