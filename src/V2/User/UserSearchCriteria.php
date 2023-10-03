<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\User;

/**
 * Class UserCriteria
 * @package Ekvio\Integration\Sdk\V2\User
 */
class UserSearchCriteria
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
     * UserSearchCriteria constructor.
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

        if(isset($criteria['filters']['login']) && is_array($criteria['filters']['login'])) {
            $filters['login'] = $criteria['filters']['login'];
        }

        if(isset($criteria['filters']['group']) && is_array($criteria['filters']['group'])) {
            $filters['group'] = $criteria['filters']['group'];
        }

        if (isset($criteria['filters']['email']) && is_array($criteria['filters']['email'])) {
            $filters['email'] = $criteria['filters']['email'];
        }

        if (isset($criteria['filters']['phone']) && is_array($criteria['filters']['phone'])) {
            $filters['phone'] = $criteria['filters']['phone'];
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