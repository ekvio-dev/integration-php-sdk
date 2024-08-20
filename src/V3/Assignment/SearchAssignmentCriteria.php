<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Assignment;


use Ekvio\Integration\Sdk\Common\Criteria;
use Ekvio\Integration\Sdk\Common\Method;
use Webmozart\Assert\Assert;

class SearchAssignmentCriteria extends Criteria
{
    private const SEARCH_ENTITY_TYPE = ['learning_program', 'info', 'event', 'message'];
    private const TYPE = ['personal', 'group'];
    private string $entity;
    private function __construct(string $entity)
    {
        $this->entity = $entity;
    }

    public static function build(string $entity): self
    {
        Assert::inArray($entity, self::SEARCH_ENTITY_TYPE, sprintf('Unsupported entity type %s', $entity));
        return new self($entity);
    }

    public function onlyType(string $type): self
    {
        Assert::inArray($type, self::TYPE, sprintf('Unsupported type %s', $type));
        return $this->cloneWithParam('type', $type);
    }

    public function onlyId(array $id): self
    {
        Assert::allNatural($id, 'IDs have not positive integer.');
        Assert::maxCount($id, 500, 'IDs exceed 500 elements.');

        return $this->cloneWithFilter('id', $id);
    }

    public function onlyLogin(array $login): self
    {
        Assert::allString($login, 'Login value must be string');
        Assert::maxCount($login, 500, 'Login exceed 500 elements.');

        return $this->cloneWithFilter('login', $login);
    }

    public function entity(): string
    {
        return $this->entity;
    }
    public function method(): string
    {
        return Method::POST;
    }
}