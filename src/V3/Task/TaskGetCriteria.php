<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Task;

use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class TaskGetCriteria extends Criteria
{
    private const TASK_STATUS = ['active', 'hide'];
    private const TASK_FIELDS = ["id","category_id","name","status","image","bonus_score","inspector","answers_count_limit_type","answers_count","is_answers_lifetime_limited","answers_lifetime","submit_for_review_text","successful_completion_text","created_at","updated_at"];

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

    public function onlyTaskFilter(array $tasks): self
    {
        Assert::allNatural($tasks, 'Task IDs have not positive integer.');
        Assert::maxCount($tasks, 500, 'Task IDs exceed 500 elements.');

        return $this->cloneWithFilter('task', $tasks);
    }

    public function onlyCategoryFilter(array $categories): self
    {
        Assert::allNatural($categories, 'Category IDs have not positive integer.');
        Assert::maxCount($categories, 500, 'Category IDs exceed 500 elements.');

        return $this->cloneWithFilter('category', $categories);
    }

    public function method(): string
    {
        return Method::POST;
    }
}
