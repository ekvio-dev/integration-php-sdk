<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Track;

use DateTimeInterface;
use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Ekvio\Integration\Sdk\Common\UserStatus;
use Webmozart\Assert\Assert;

class TrackColdStatisticCriteria extends Criteria
{
    private const TRACK_STATUS = ['active', 'hide'];
    private const ANSWER_STATUS = ['appointed', 'in_progress', 'completed', 'checking', 'fail'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyTracks(array $tracks): self
    {
        Assert::allNatural($tracks, 'Track IDs have not positive integer.');
        Assert::maxCount($tracks, 500, 'Track IDs exceed 500 elements.');

        return $this->cloneWithFilter('track', $tracks);
    }

    public function onlyCategories(array $categories): self
    {
        Assert::allNatural($categories, 'Category IDs have not positive integer.');
        Assert::maxCount($categories, 500, 'Category IDs exceed 500 elements.');

        return $this->cloneWithFilter('category', $categories);
    }

    public function onlyLogin(array $logins): self
    {
        Assert::notEmpty($logins, 'Logins required.');
        Assert::maxCount($logins, 500, 'Logins IDs exceed 500 elements.');
        return $this->cloneWithFilter('login', $logins);
    }

    public function onlyTrackStatus(string $status): self
    {
        Assert::notEmpty($status, 'Track status required.');
        Assert::inArray($status, self::TRACK_STATUS, 'Track status invalid. Use active or hide.');

        return $this->cloneWithParam('track_status', $status);
    }

    public function onlyUserStatus(string $status): self
    {
        Assert::notEmpty($status, 'User status required.');
        Assert::inArray($status, UserStatus::STATUSES, 'User status invalid. Use active or blocked.');

        return $this->cloneWithParam('user_status', $status);
    }

    public function onlyAnswerStatus(array $status): self
    {
        Assert::notEmpty($status, 'Answer status required.');
        Assert::allInArray($status, self::ANSWER_STATUS, 'Answer status invalid. Use appointed, in_progress, completed, checking or fail.');
        Assert::maxCount($status, 1, 'Available only 1 status.');

        return $this->cloneWithParam('answer_status', $status);
    }

    public function onlyToDate(DateTimeInterface $dt): self
    {
        return $this->cloneWithParam('to_date', $dt);
    }

    public function onlyAfterDate(DateTimeInterface $dt): self
    {
        return $this->cloneWithParam('after_date', $dt);
    }

    public function method(): string
    {
        return Method::POST;
    }
}