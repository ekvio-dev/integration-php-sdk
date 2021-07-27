<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V1\Personal;

use Ekvio\Integration\Sdk\ApiException;

/**
 * Interface PersonalStatus
 * @package Ekvio\Integration\Sdk\V2\Material\Personal
 * @deprecated
 */
interface PersonalStatus
{
    /**
     * @param array
     * @throws ApiException
     */
    public function updateStatuses(array $statuses): void;
}