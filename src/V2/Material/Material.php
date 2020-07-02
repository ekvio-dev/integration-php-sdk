<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Material;

use Ekvio\Integration\Sdk\V2\ApiException;
use Ekvio\Integration\Sdk\V2\EqueoClient;

/**
 * Class Material
 * @package Ekvio\Integration\Sdk\V2\Material
 */
class Material implements MaterialStatistic
{
    private const MATERIALS_STATISTIC_ENDPOINT = '/v2/materials/statistic';
    /**
     * @var EqueoClient
     */
    private $client;

    /**
     * Material constructor.
     * @param EqueoClient $client
     */
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $criteria
     * @return array
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function statistic(array $criteria = []): array
    {
        $fields = [];
        if(array_key_exists('material_status', $criteria)) {
            $fields['material_status'] = $criteria['material_status'];
        }

        if(array_key_exists('user_status', $criteria)) {
            $fields['user_status'] = $criteria['user_status'];
        }

        if(array_key_exists('from_date', $criteria)) {
            $fields['from_date'] = $criteria['from_date'];
        }

        $response = $this->client->request('GET', self::MATERIALS_STATISTIC_ENDPOINT, $fields);

        if(isset($response['errors'])) {
            ApiException::apiErrors($response['errors']);
        }

        $integration = (int) $response['data']['integration'];
        $content = $this->client->integration($integration);

        return $content['data'];
    }
}