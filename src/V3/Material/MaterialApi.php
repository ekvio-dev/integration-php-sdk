<?php

namespace Ekvio\Integration\Sdk\V3\Material;

use Ekvio\Integration\Sdk\Common\Method;
use Ekvio\Integration\Sdk\V3\EqueoClient;
use Ekvio\Integration\Sdk\V3\Task\MaterialStatisticCriteria;

class MaterialApi implements Material
{
    private const DOCUMENT_CREATE_ENDPOINT = '/v3/materials/document';
    private const DOCUMENT_UPDATE_ENDPOINT = '/v3/materials/document';
    private const LINK_CREATE_ENDPOINT = '/v3/materials/link';
    private const LINK_UPDATE_ENDPOINT = '/v3/materials/link';
    private const PDF_CREATE_ENDPOINT = '/v3/materials/pdf';
    private const PDF_UPDATE_ENDPOINT = '/v3/materials/pdf';
    private const MATERIALS_STATISTIC_ENDPOINT = '/v3/materials/statistic';
    private const MATERIAL_SEARCH = '/v3/materials';
    private EqueoClient $client;

    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }
    public function createDocument(array $items): array
    {
        return $this->client->defaultDeferredRequest(Method::POST, self::DOCUMENT_CREATE_ENDPOINT, $items);
    }

    public function updateDocument(array $items): array
    {
        return $this->client->defaultDeferredRequest(Method::PUT, self::DOCUMENT_UPDATE_ENDPOINT, $items);
    }

    public function createLink(array $items): array
    {
        return $this->client->defaultDeferredRequest(Method::POST, self::LINK_CREATE_ENDPOINT, $items);
    }

    public function updateLink(array $items): array
    {
        return $this->client->defaultDeferredRequest(Method::PUT, self::LINK_UPDATE_ENDPOINT, $items);
    }

    public function createPdf(array $items): array
    {
        return $this->client->defaultDeferredRequest(Method::POST, self::PDF_CREATE_ENDPOINT, $items);
    }

    public function updatePdf(array $items): array
    {
        return $this->client->defaultDeferredRequest(Method::PUT, self::PDF_UPDATE_ENDPOINT, $items);
    }

    public function statistic(MaterialStatisticCriteria $criteria): array
    {
        $response = $this->client->deferredRequest(
            $criteria->method(),
            self::MATERIALS_STATISTIC_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response['data'];
    }

    public function search(MaterialSearchCriteria $criteria): array
    {
        return $this->client->pagedRequest(
            $criteria->method(),
            self::MATERIAL_SEARCH,
            $criteria->queryParams(),
            $criteria->body()
        );
    }
}