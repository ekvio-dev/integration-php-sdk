<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Kpi;

use DateTimeInterface;
use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class KpiTransactionsSearchCriteria extends Criteria
{
    private const KPI_FIELDS = ['id', 'kpi_id', 'name', 'login', 'amount', 'comment', 'date', 'created_at', 'updated_at'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyToDate(DateTimeInterface $dt): self
    {
        return $this->cloneWithParam('to_date', $dt);
    }

    public function onlyAfterDate(DateTimeInterface $dt): self
    {
        return $this->cloneWithParam('after_date', $dt);
    }

    public function onlyLogins(array $logins): self
    {
        Assert::notEmpty($logins, 'Logins required.');
        Assert::maxCount($logins, 500, 'Logins exceed 500 elements.');
        return $this->cloneWithFilter('login', $logins);
    }

    public function onlyKpis(array $kpis): self
    {
        Assert::notEmpty($kpis, 'Kpis required.');
        Assert::maxCount($kpis, 500, 'Kpis exceed 500 elements.');
        return $this->cloneWithFilter('kpi', $kpis);
    }

    public function method(): string
    {
        return Method::POST;
    }
}