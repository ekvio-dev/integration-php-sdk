<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Personal;

/**
 * Class SearchAssignmentCriteria
 * @package Ekvio\Integration\Sdk\V2\Personal
 */
class AssignmentSearchCriteria
{
    private array $filters = [];
    private array $params = [];
    /**
     * UserSearchCriteria constructor.
     */
    private function __construct(){}

    /**
     * @param array $criteria
     * @return self
     */
    public static function createFrom(array $criteria): self
    {
        $self = new self();
        $self->filters = $self->buildFilters($criteria);
        $self->params = isset($criteria['params']) && is_array($criteria['params']) ? $criteria['params'] : [];

        return $self;
    }

    private function buildFilters(array $criteria): array
    {
        $filters = [];

        if(isset($criteria['filters']['login']) && is_array($criteria['filters']['login'])) {
            $filters['login'] = $criteria['filters']['login'];
        }

        return $filters;
    }

    public function filters(): array
    {
        return $this->filters;
    }

    public function params(): array
    {
        return $this->params;
    }
}