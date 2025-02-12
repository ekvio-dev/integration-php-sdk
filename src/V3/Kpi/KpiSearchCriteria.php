<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Kpi;

use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class KpiSearchCriteria extends Criteria
{
    private const KPI_STATUS = ['active', 'hide'];
    private const KPI_FIELDS = ['id', 'name', 'status', 'unit', 'rating', 'created_at', 'updated_at'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyKpiStatus(string $status): self
    {
        Assert::notEmpty($status, 'Kpi status required.');
        Assert::inArray($status, self::KPI_STATUS, 'Kpi status invalid. Use active or hide.');

        return $this->cloneWithParam('status', $status);
    }

    public function method(): string
    {
        return Method::POST;
    }
}