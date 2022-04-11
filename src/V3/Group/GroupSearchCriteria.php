<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Group;

/**
 * Class UserCriteria
 * @package Ekvio\Integration\Sdk\Common\Group
 */
class GroupSearchCriteria
{
    /**
     * @var array
     */
    private $params = [];
    /**
     * @var array
     */
    private $filters = [];

    /**
     * GroupSearchCriteria constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param array $criteria
     * @return self
     */
    public static function createFrom(array $criteria): self
    {
        $self = new self();
        $self->filters = $self->fillFilters($criteria);
        $self->params = $criteria['params'] ?? $self->params;

        return $self;
    }

    private function fillFilters(array $criteria): array
    {
        $filters = [];

        if(isset($criteria['filters']['root']) && is_array($criteria['filters']['root'])) {
            $filters['root'] = $criteria['filters']['root'];
        }

        return $filters;
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return $this->filters;
    }
}