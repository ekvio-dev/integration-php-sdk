<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Task;

use DateTimeInterface;
use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Ekvio\Integration\Sdk\Common\UserStatus;
use Webmozart\Assert\Assert;

class TaskStatisticCriteria extends Criteria
{
    private const TASK_STATUS = ['active', 'hide'];
    private const ANSWER_STATUS = ['completed', 'checking', 'failed'];
    private const REVIEW_ROLE = ['administrator', 'manager'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyTasks(array $tasks): self
    {
        Assert::allNatural($tasks, 'Task IDs have not positive integer.');
        Assert::maxCount($tasks, 500, 'Task IDs exceed 500 elements.');

        return $this->cloneWithParam('tasks', $tasks);
    }

    public function onlyCategories(array $categories): self
    {
        Assert::allNatural($categories, 'Category IDs have not positive integer.');
        Assert::maxCount($categories, 500, 'Category IDs exceed 500 elements.');

        return $this->cloneWithParam('category', $categories);
    }

    public function onlyTaskStatus(string $status): self
    {
        Assert::notEmpty($status, 'Task status required.');
        Assert::inArray($status, self::TASK_STATUS, 'Task status invalid. Use active or hide.');

        return $this->cloneWithParam('task_status', $status);
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
        Assert::allInArray($status, self::ANSWER_STATUS, 'Answer status invalid. Use completed, checking or failed.');
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

    public function onlyCheckDate(DateTimeInterface $dt): self
    {
        return $this->cloneWithParam('from_check_date', $dt);
    }

    public function onlyRole(string $role): self
    {
        Assert::notEmpty($role, 'Role required.');
        Assert::inArray($role, self::REVIEW_ROLE, 'Role invalid. Use administrator or manager.');
        return $this->cloneWithFilter('role', [$role]);
    }

    public function onlyReviewers(array $reviewers): self
    {
        Assert::notEmpty($reviewers, 'Reviewers required.');
        Assert::maxCount($reviewers, 500, 'Reviewers IDs exceed 500 elements.');
        return $this->cloneWithFilter('reviewer', $reviewers);
    }

    public function method(): string
    {
        return Method::POST;
    }
}