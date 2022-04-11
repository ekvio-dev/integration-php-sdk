<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Material;

/**
 * Interface MaterialStatistic
 * @package Ekvio\Integration\Sdk\V3\Material
 */
interface MaterialStatistic
{
    /**
     * @param array $criteria
     * @return array
     */
    public function statistic(array $criteria = []): array;
}