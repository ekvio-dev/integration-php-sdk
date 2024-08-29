<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Achievement;

use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class BadgesSearchCriteria extends Criteria
{
    private const BADGE_STATUS = ['active', 'hide'];
    private const BADGE_FIELDS = ['id', 'category_id', 'description', 'name', 'points', 'image', 'can_give_manager', 'is_limited', 'limit_count', 'status'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyStatus(string $status): self
    {
        Assert::notEmpty($status, 'Badge status required.');
        Assert::inArray($status, self::BADGE_STATUS, 'Badge status invalid. Use active or hide.');

        return $this->cloneWithParam('status', $status);
    }

    public function onlyIdFilter(array $badges): self
    {
        Assert::allNatural($badges, 'Badge IDs have not positive integer.');
        Assert::maxCount($badges, 500, 'Badge IDs exceed 500 elements.');

        return $this->cloneWithFilter('id', $badges);
    }

    public function onlyCategoryIdFilter(array $categories): self
    {
        Assert::allNatural($categories, 'Badge category IDs have not positive integer.');
        Assert::maxCount($categories, 500, 'Badge category IDs exceed 500 elements.');

        return $this->cloneWithFilter('category_id', $categories);
    }

    public function method(): string
    {
        return Method::POST;
    }
}
