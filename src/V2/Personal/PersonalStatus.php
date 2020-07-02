<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Personal;

use Ekvio\Integration\Sdk\V2\ApiException;

/**
 * Interface PersonalStatus
 * @package Ekvio\Integration\Sdk\V2\Material\Personal
 */
interface PersonalStatus
{
    /**
     * @param array
     * @throws ApiException
     */
    public function updateStatuses(array $statuses): void;
}