<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Kpi;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V3\EqueoClient;

/**
 * Class KpiApi
 * @package Ekvio\Integration\Sdk\V3\Kpi
 */
class KpiApi implements Kpi
{
    private const KPI_TRANSACTIONS_IMPORT_ENDPOINT = '/v3/kpi/transactions';
    private const KPI_TRANSACTIONS_DELETE_ENDPOINT = '/v3/kpi/transactions';
    private const KPI_SEARCH_ENDPOINT = '/v3/kpi/search';
    private const KPI_TRANSACTIONS_SEARCH_ENDPOINT = '/v3/kpi/transactions/search';
    private const KPI_TRANSACTIONS_OVERRIDE_DEFAULT = true;

    /**
     * @var EqueoClient
     */
    private EqueoClient $client;

    /**
     * KpiApi constructor.
     * @param EqueoClient $client
     */
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $transactions
     * @param array $config
     * @return array
     * @throws ApiException
     */
    public function importKpi(array $transactions, array $config = []): array
    {
        $override = self::KPI_TRANSACTIONS_OVERRIDE_DEFAULT;
        if(isset($config['override']) && is_bool($config['override'])) {
            $override = $config['override'];
        }

        $response = $this->client->deferredRequest('POST', self::KPI_TRANSACTIONS_IMPORT_ENDPOINT, [], [
            'data' => $transactions,
            'override' => $override
        ]);

        return $response['data'];
    }

    /**
     * @throws ApiException
     */
    public function deleteKpi(array $data): array
    {
        $response = $this->client->deferredRequest('DELETE', self::KPI_TRANSACTIONS_DELETE_ENDPOINT, [], [
            'data' => $data
        ]);

        return $response['data'];
    }

    /**
     * @throws ApiException
     */
    public function searchKpi(KpiSearchCriteria $criteria): array
    {
        return $this->client->pagedRequest(
            $criteria->method(),
            self::KPI_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );
    }

    /**
     * @throws ApiException
     */
    public function searchKpiTransactions(KpiTransactionsSearchCriteria $criteria): array
    {
        return $this->client->pagedRequest(
            $criteria->method(),
            self::KPI_TRANSACTIONS_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );
    }
}