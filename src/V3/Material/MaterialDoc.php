<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Material;

/**
 * Interface MaterialStatistic
 * @package Ekvio\Integration\Sdk\V3\Material
 */
interface MaterialDoc
{
    /**
     * @param array $criteria
     * @return array
     */
    public function create(array $criteria = []): array;
}