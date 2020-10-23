<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Kpi;

use Ekvio\Integration\Sdk\V2\EqueoClient;

/**
 * Class KpiApi
 * @package Ekvio\Integration\Sdk\V2\Kpi
 */
class KpiApi implements Kpi
{
    private const KPI_TRANSACTIONS_ENDPOINT = '/v2/kpi/transactions';
    private const KPI_TRANSACTIONS_OVERRIDE_DEFAULT = true;

    /**
     * @var EqueoClient
     */
    private $client;

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
     * @throws \Ekvio\Integration\Sdk\ApiException
     */
    public function transactions(array $transactions, array $config = []): array
    {
        $override = self::KPI_TRANSACTIONS_OVERRIDE_DEFAULT;
        if(isset($config['override']) && is_bool($config['override'])) {
            $override = $config['override'];
        }

        $response = $this->client->deferredRequest('POST', self::KPI_TRANSACTIONS_ENDPOINT, [], [
            'data' => $transactions,
            'override' => $override
        ]);

        return $response['data'];
    }
}