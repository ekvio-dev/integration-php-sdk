<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Kpi;

/**
 * Interface Kpi
 * @package Ekvio\Integration\Sdk\V2\Kpi
 */
interface Kpi
{
    /**
     * Import KPI values
     *
     * @param array $transactions
     * @param array $config
     * @return array
     */
    public function transactions(array $transactions, array $config = []): array;
}