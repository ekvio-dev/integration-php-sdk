<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Information;


use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class SearchInformationCriteria extends Criteria
{
    private const ALLOW_STATUS = ['active', 'hide'];
    private function __construct() {}

    public static function build(): self
    {
        return new self();
    }

    public function onlyFields(array $fields): self
    {
        Assert::notEmpty($fields, 'Field should not be empty');
        Assert::allStringNotEmpty($fields, 'Field should be not empty string');

        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyCategory(int $id): self
    {
        Assert::positiveInteger($id, 'Category ID must be an integer');

        return $this->cloneWithFilter('category', $id);
    }

    public function onlyStatus(string $status): self
    {
        Assert::notEmpty($status, 'Status must be a non-empty string');
        Assert::inArray($status, self::ALLOW_STATUS, 'Status must be active or hide');

        return $this->cloneWithFilter('status', $status);
    }

    public function onlyMaterials(array $ids): self
    {
        Assert::notEmpty($ids, 'Materials must be a non-empty array');
        Assert::allPositiveInteger($ids, 'Materials must be an array of positive integers');
        Assert::maxCount($ids, 500, 'Materials must be less than 500');

        return $this->cloneWithFilter('materials', $ids);
    }

    public function method(): string
    {
        return Method::POST;
    }
}