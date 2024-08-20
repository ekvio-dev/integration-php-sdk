<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Media;


use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class MediaSearchCriteria extends Criteria
{
    private const STATUS = ['active', 'hide'];
    private function __construct(){}

    public static function build(array $criteria = []): self
    {
        return new self();
    }

    public function withFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyCategoryFilter(array $categories): self
    {
        Assert::allNatural($categories, 'Category IDs have not positive integer.');
        Assert::maxCount($categories, 500, 'Category IDs exceed 500 elements.');

        return $this->cloneWithFilter('category', $categories);
    }

    public function onlyStatusFilter(string $status): self
    {
        Assert::inArray($status, self::STATUS, 'Unknown media status');

        return $this->cloneWithFilter('status', $status);
    }

    public function method(): string
    {
        return Method::POST;
    }
}