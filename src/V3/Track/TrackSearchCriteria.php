<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Track;

use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class TrackSearchCriteria extends Criteria
{
    private const TRACK_STATUS = ['active', 'hide'];
    private const TRACK_FIELDS = [
        'id',
        'module_id',
        'category_id',
        'status',
        'name',
        'description',
        'publish_at',
        'publish_time_zone',
        'deadline_type',
        'deadline_at',
        'deadline_period_type',
        'deadline_period_at',
        'deadline_time_zone',
        'unpublish_type',
        'unpublish_at',
        'unpublish_period_type',
        'unpublish_period_at',
        'unpublish_time_zone',
        'created_at',
        'updated_at'
    ];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyTrackStatus(string $status): self
    {
        Assert::notEmpty($status, 'Track status required.');
        Assert::inArray($status, self::TRACK_STATUS, 'Track status invalid. Use active or hide.');

        return $this->cloneWithParam('status', $status);
    }

    public function onlyIdFilter(array $ids): self
    {
        Assert::allNatural($ids, 'Track IDs have not positive integer.');
        Assert::maxCount($ids, 500, 'Track IDs exceed 500 elements.');

        return $this->cloneWithFilter('id', $ids);
    }

    public function onlyCategories(array $categories): self
    {
        Assert::allNatural($categories, 'Category IDs have not positive integer.');
        Assert::maxCount($categories, 500, 'Category IDs exceed 500 elements.');

        return $this->cloneWithFilter('category', $categories);
    }

    public function method(): string
    {
        return Method::POST;
    }
}