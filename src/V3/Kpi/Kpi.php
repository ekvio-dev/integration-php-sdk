<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Kpi;

/**
 * Interface Kpi
 * @package Ekvio\Integration\Sdk\V3\Kpi
 */
interface Kpi
{
    public function importKpi(array $transactions, array $config = []): array;
    public function deleteKpi(array $data): array;
    public function searchKpi(KpiSearchCriteria $criteria): array;
    public function searchKpiTransactions(KpiTransactionsSearchCriteria $criteria): array;
}