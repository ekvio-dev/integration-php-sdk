<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Message;

use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class MessageSearchCriteria extends Criteria
{
    private const MESSAGE_STATUS = ['active', 'hide'];
    private const MESSAGE_FIELDS = ['id','topic','status','text','author','blocked','created_at','published_at','comments','likes','tags','image'];

    private function __construct(){}

    public static function build(): self
    {
        return new self();
    }

    public function onlyFields(array $fields): self
    {
        return $this->cloneWithParam('fields', $fields);
    }

    public function onlyMessageStatus(string $status): self
    {
        Assert::notEmpty($status, 'Message status required.');
        Assert::inArray($status, self::MESSAGE_STATUS, 'Message status invalid. Use active or hide.');

        return $this->cloneWithParam('status', $status);
    }

    public function onlyMessagesFilter(array $tasks): self
    {
        Assert::allNatural($tasks, 'Message IDs have not positive integer.');
        Assert::maxCount($tasks, 500, 'Message IDs exceed 500 elements.');

        return $this->cloneWithFilter('id', $tasks);
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