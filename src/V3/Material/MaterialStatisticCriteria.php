<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Task;

use DateTimeInterface;
use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Ekvio\Integration\Sdk\Common\UserStatus;
use Webmozart\Assert\Assert;

class MaterialStatisticCriteria extends Criteria
{
    private const MATERIAL_STATUS = ['active', 'hide'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyMaterialStatus(string $status): self
    {
        Assert::notEmpty($status, 'Material status required.');
        Assert::inArray($status, self::MATERIAL_STATUS, 'Material status invalid. Use active or hide.');

        return $this->cloneWithParam('material_status', $status);
    }

    public function onlyUserStatus(string $status): self
    {
        Assert::notEmpty($status, 'User status required.');
        Assert::inArray($status, UserStatus::STATUSES, 'User status invalid. Use active or blocked.');

        return $this->cloneWithParam('user_status', $status);
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