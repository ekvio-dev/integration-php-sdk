<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Shop;


interface Wallet
{
    public function search(WalletSearchCriteria $criteria): array;
}