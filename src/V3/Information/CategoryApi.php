<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Information;


use Ekvio\Integration\Sdk\V3\EqueoClient;

class CategoryApi implements Category
{
    private const CHUNK = 500;
    private const CATEGORY_ENDPOINT = '/v3/informations/categories';

    private EqueoClient $client;
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    public function search(SearchCategoryCriteria $criteria): array
    {
        return $this->client->pagedRequest('GET', self::CATEGORY_ENDPOINT, $criteria->params());
    }

    public function update(array $categories): array
    {
        $data = [];
        foreach (array_chunk($categories, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('PUT', self::CATEGORY_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    public function create(array $categories): array
    {
        $data = [];
        foreach (array_chunk($categories, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('POST', self::CATEGORY_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    public function delete(array $categories): array
    {
        $data = [];
        foreach (array_chunk($categories, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('DELETE', self::CATEGORY_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }
}