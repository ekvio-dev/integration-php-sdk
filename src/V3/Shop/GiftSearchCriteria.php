<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Shop;


use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class GiftSearchCriteria extends Criteria
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

    public function onlyStatus(string $status): self
    {
        Assert::inArray($status, self::STATUS, 'Invalid gift status');
        return $this->cloneWithParam('status', $status);
    }

    public function onlyIdFilter(array $ids): self
    {
        Assert::allPositiveInteger($ids, 'Gift ID filter requires only positive integer');
        Assert::maxCount($ids, 500, 'Gift ID filter max count 500 items');
        return $this->cloneWithFilter('id', $ids);
    }

    public function archived(bool $flag): self
    {
        return $this->cloneWithFilter('archived', $flag);
    }

    public function method(): string
    {
        return Method::POST;
    }
}