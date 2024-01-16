<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Shop;


use DateTimeImmutable;
use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class OrderSearchCriteria extends Criteria
{
    private const STATUS = ['new', 'in_progress', 'on_delivery', 'done', 'rejected', 'archived'];
    private const USER_STATUS = ['active', 'blocked'];
    private function __construct(){}

    public static function build(array $criteria = []): self
    {
        return new self();
    }

    public function withFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyStatusFilter(array $status): self
    {
        Assert::allInArray($status, self::STATUS, 'Invalid order status');
        return $this->cloneWithParam('status', $status);
    }

    public function onlyUserStatusFilter(string $status): self
    {
        Assert::inArray($status, self::USER_STATUS, 'Invalid user status');
        return $this->cloneWithParam('user_status', $status);
    }

    public function withAfterDate(string $datetime): self
    {
        Assert::isEmpty($datetime, 'Invalid after date value format');
        if (!DateTimeImmutable::createFromFormat(DATE_ATOM, $datetime)) {
            throw new InvalidArgumentException('Invalid after date value format');
        }

        return $this->cloneWithParam('after_date', $datetime);
    }

    public function withToDate(string $datetime): self
    {
        Assert::isEmpty($datetime, 'Invalid to date value format');
        if (!DateTimeImmutable::createFromFormat(DATE_ATOM, $datetime)) {
            throw new InvalidArgumentException('Invalid to date value format');
        }

        return $this->cloneWithParam('to_date', $datetime);
    }

    public function method(): string
    {
        return Method::POST;
    }
}