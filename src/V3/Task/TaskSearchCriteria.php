<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Task;

use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class TaskSearchCriteria extends Criteria
{
    private const TASK_STATUS = ['active', 'hide'];
    private const TASK_FIELDS = ['id', 'task_id', 'type', 'name', 'hint', 'order', 'settings', 'required', 'created_at', 'updated_at'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyTaskStatus(string $status): self
    {
        Assert::notEmpty($status, 'Task status required.');
        Assert::inArray($status, self::TASK_STATUS, 'Task status invalid. Use active or hide.');

        return $this->cloneWithParam('task_status', $status);
    }

    public function onlyTasksFilter(array $tasks): self
    {
        Assert::allNatural($tasks, 'Task IDs have not positive integer.');
        Assert::maxCount($tasks, 500, 'Task IDs exceed 500 elements.');

        return $this->cloneWithFilter('task', $tasks);
    }

    public function onlyFieldsFilter(array $fields): self
    {
        Assert::maxCount($fields, 500, 'Task IDs exceed 500 elements.');

        return $this->cloneWithFilter('field', $fields);
    }

    public function method(): string
    {
        return Method::POST;
    }
}
