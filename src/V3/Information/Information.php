<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Information;


interface Information
{
    public function search(SearchInformationCriteria $criteria): array;
    public function create(array $information): array;
    public function update(array $information): array;
    public function delete(array $information): array;
    public function sort(string $attribute = 'name'): array;
}