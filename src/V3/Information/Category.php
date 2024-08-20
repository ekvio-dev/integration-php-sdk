<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Information;


interface Category
{
    public function search(SearchCategoryCriteria $criteria): array;
    public function update(array $categories): array;
    public function create(array $categories): array;
    public function delete(array $categories): array;

}