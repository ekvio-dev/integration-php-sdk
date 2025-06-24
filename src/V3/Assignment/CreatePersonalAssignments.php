<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Assignment;


use Webmozart\Assert\Assert;

class CreatePersonalAssignments
{
    private string $entity;
    private array $assignments;
    public function __construct(string $entity, array $assignments)
    {
        Assert::inArray($entity, ['task', 'media', 'shop', 'info', 'learning_program', 'track', 'event', 'message'], sprintf('Unsupported entity type %s', $entity));
        $this->entity = $entity;
        $this->assignments = $assignments;
    }

    public function entity(): string
    {
        return $this->entity;
    }

    public function assignments(): array
    {
        return $this->assignments;
    }
}