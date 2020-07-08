<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\User;

/**
 * Interface UserSync
 * @package Ekvio\Integration\Sdk\V2
 */
interface UserSync
{
    /**
     * @param array $users
     * @return array
     */
    public function sync(array $users): array;
}