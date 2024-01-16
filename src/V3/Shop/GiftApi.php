<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Shop;


use Ekvio\Integration\Sdk\V3\EqueoClient;

class GiftApi implements Gift
{
    private const CHUNK = 300;
    private const GIFTS_SEARCH_ENDPOINT = '/v3/shop/gifts/search';
    private const GIFTS_CREATE_ENDPOINT = '/v3/shop/gifts';
    private const GIFTS_UPDATE_ENDPOINT = '/v3/shop/gifts';
    private EqueoClient $client;
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }
    public function search(GiftSearchCriteria $criteria): array
    {
        return $this->client->pagedRequest(
            $criteria->method(),
            self::GIFTS_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );
    }

    public function create(array $gifts): array
    {
        $data = [];
        foreach (array_chunk($gifts, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('POST', self::GIFTS_CREATE_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    public function update(array $gifts): array
    {
        $data = [];
        foreach (array_chunk($gifts, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('PUT', self::GIFTS_UPDATE_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }
}