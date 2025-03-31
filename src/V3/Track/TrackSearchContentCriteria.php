<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Track;

use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class TrackSearchContentCriteria extends Criteria
{
    private const TRACK_FIELDS = ['id', 'track_id', 'content_id', 'content_type', 'order', 'created_at', 'updated_at'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyIdFilter(array $ids): self
    {
        Assert::allNatural($ids, 'Track content IDs have not positive integer.');
        Assert::maxCount($ids, 500, 'Track content IDs exceed 500 elements.');

        return $this->cloneWithFilter('id', $ids);
    }

    public function onlyTracks(array $tracks): self
    {
        Assert::allNatural($tracks, 'Track IDs have not positive integer.');
        Assert::maxCount($tracks, 500, 'Track IDs exceed 500 elements.');

        return $this->cloneWithFilter('track', $tracks);
    }

    public function method(): string
    {
        return Method::POST;
    }
}