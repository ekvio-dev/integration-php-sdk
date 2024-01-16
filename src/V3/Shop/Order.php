<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Shop;


interface Order
{
    public function search(OrderSearchCriteria $criteria): array;
    public function update(array $orders): array;
}