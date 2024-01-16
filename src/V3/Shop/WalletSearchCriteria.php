<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Shop;


use DateTimeImmutable;
use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class WalletSearchCriteria extends Criteria
{
    private function __construct(){}

    public static function build(array $criteria = []): self
    {
        return new self();
    }

    public function withFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
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

    public function onlyLogin(array $login): self
    {
        Assert::allNotEmpty($login, 'Login filter requires not empty string');
        return $this->cloneWithFilter('login', $login);
    }

    public function method(): string
    {
        return Method::POST;
    }
}