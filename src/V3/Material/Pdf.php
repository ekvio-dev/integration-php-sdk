<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Material;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V3\EqueoClient;

/**
 * Class Material
 * @package Ekvio\Integration\Sdk\V3\Material
 */
class Pdf implements MaterialPdf
{
    private const MATERIALS_PDF_ENDPOINT = '/v3/materials/pdf';
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
    public function create(array $criteria = []): array
    {
        $fields = [];


        if(array_key_exists('status', $criteria)) {
            $fields['status'] = $criteria['status'];
        }

        if(array_key_exists('name', $criteria)) {
            $fields['name'] = $criteria['name'];
        }

        if(array_key_exists('study_time', $criteria)) {
            $fields['study_time'] = $criteria['study_time'];
        }

        if(array_key_exists('image_token', $criteria)) {
            $fields['image_token'] = $criteria['image_token'];
        }

        if(array_key_exists('file_token', $criteria)) {
            $fields['file_token'] = $criteria['file_token'];
        }

        if(array_key_exists('allow_sharing', $criteria)) {
            $fields['pdf_allow_sharing'] = $criteria['allow_sharing'];
        }

        $response = $this->client->request('POST', self::MATERIALS_PDF_ENDPOINT, $fields);

        if(isset($response['errors'])) {
            ApiException::apiErrors($response['errors']);
        }

        $integration = (int) $response['data']['integration'];
        $content = $this->client->integration($integration);

        return $content['data'];
    }
}