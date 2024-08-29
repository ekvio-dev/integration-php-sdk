<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Achievement;

use DateTimeInterface;
use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class BadgesStatisticCriteria extends Criteria
{
    private const ASSIGNMENT_USER_TYPE = ['admin', 'manager'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyAssigmentUserType(string $type): self
    {
        Assert::notEmpty($type, 'Assignment user type required.');
        Assert::inArray($type, self::ASSIGNMENT_USER_TYPE, 'Assignment user type invalid. Use admin or manager.');

        return $this->cloneWithParam('assignment_user_type', $type);
    }

    public function onlyToDate(DateTimeInterface $dt): self
    {
        return $this->cloneWithParam('to_date', $dt);
    }

    public function onlyAfterDate(DateTimeInterface $dt): self
    {
        return $this->cloneWithParam('after_date', $dt);
    }

    public function onlyBadgeIdFilter(array $badges): self
    {
        Assert::allNatural($badges, 'Badge IDs have not positive integer.');
        Assert::maxCount($badges, 500, 'Badge IDs exceed 500 elements.');

        return $this->cloneWithFilter('badge', $badges);
    }

    public function onlyLoginFilter(array $logins): self
    {
        Assert::allString($logins, 'Badge logins have not string.');
        Assert::maxCount($logins, 500, 'Badge logins exceed 500 elements.');

        return $this->cloneWithFilter('login', $logins);
    }

    public function onlyAssignmentUserFilter(array $logins): self
    {
        Assert::allString($logins, 'Badge assignment user logins have not string.');
        Assert::maxCount($logins, 500, 'Badge assignment user logins exceed 500 elements.');

        return $this->cloneWithFilter('assignment_user', $logins);
    }

    public function method(): string
    {
        return Method::POST;
    }
}
