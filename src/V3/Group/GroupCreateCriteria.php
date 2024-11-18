<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Group;

/**
 * Class GroupCreateCriteria
 * @package Ekvio\Integration\Sdk\V3\Group
 */
class GroupCreateCriteria
{
    private array $data = [];
    /**
     * @param array $criteria
     * @return self
     */
    public static function createFrom(array $criteria): self
    {
        $self = new self();
        $self->data = $criteria['data'] ?? [];

        return $self;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }
}
