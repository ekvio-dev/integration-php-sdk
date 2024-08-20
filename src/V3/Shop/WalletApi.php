<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Shop;


use Ekvio\Integration\Sdk\V3\EqueoClient;

class WalletApi implements Wallet
{
    private const WALLETS_SEARCH_ENDPOINT = '/v3/shop/wallets/search';
    private EqueoClient $client;
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }
    public function search(WalletSearchCriteria $criteria): array
    {
        return $this->client->pagedRequest(
            $criteria->method(),
            self::WALLETS_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );
    }
}