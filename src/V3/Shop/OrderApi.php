<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Shop;


use Ekvio\Integration\Sdk\V3\EqueoClient;

class OrderApi implements Order
{
    private const CHUNK = 100;
    private const ORDERS_SEARCH_ENDPOINT = '/v3/shop/orders/search';
    private const ORDERS_UPDATE_ENDPOINT = '/v3/shop/orders';
    private EqueoClient $client;
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }
    public function search(OrderSearchCriteria $criteria): array
    {
        return $this->client->pagedRequest(
            $criteria->method(),
            self::ORDERS_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );
    }

    public function update(array $orders): array
    {
        $data = [];
        foreach (array_chunk($orders, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('PUT', self::ORDERS_UPDATE_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }
}