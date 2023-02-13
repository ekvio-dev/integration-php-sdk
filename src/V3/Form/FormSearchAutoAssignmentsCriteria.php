<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Form;

use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

/**
 * Class FormSearchCriteria
 * @package Ekvio\Integration\Sdk\V3\Form
 */
class FormSearchAutoAssignmentsCriteria extends Criteria
{
    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyLimit(int $limit): self
    {
        return $this->cloneWithParam('limit', $limit);
    }

    public function onlyAfter(int $after): self
    {
        return $this->cloneWithParam('after', $after);
    }

    public function onlyValues(array $values): self
    {
        Assert::allString($values, 'Values have not strings.');
        Assert::maxCount($values, 500, 'Values exceed 500 elements.');

        return $this->cloneWithFilter('value', $values);
    }

    public function onlyChiefEmails(array $chiefEmails): self
    {
        Assert::allString($chiefEmails, 'Chief email values have not strings.');
        Assert::maxCount($chiefEmails, 500, 'Chief email values exceed 500 elements.');

        return $this->cloneWithFilter('chief_email', $chiefEmails);
    }

    public function onlyGroups(array $groups): self
    {
        Assert::allNatural($groups, 'Group values is not integers.');
        Assert::maxCount($groups, 500, 'Values exceed 500 elements.');

        return $this->cloneWithFilter('group', $groups);
    }

    public function method(): string
    {
        return Method::POST;
    }
}
