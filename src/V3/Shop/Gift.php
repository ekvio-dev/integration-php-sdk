<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Shop;


interface Gift
{
    public function search(GiftSearchCriteria $criteria): array;
    public function create(array $gifts): array;
    public function update(array $gifts): array;
}