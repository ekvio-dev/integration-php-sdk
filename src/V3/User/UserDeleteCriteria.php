<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\User;

/**
 * Class UserDeleteCriteria
 * @package Ekvio\Integration\Sdk\V2\User
 */
class UserDeleteCriteria
{
    private $logins = [];
    /**
     * @param array $criteria
     * @return self
     */
    public static function createFrom(array $criteria): self
    {
        $self = new self();
        $self->logins = $criteria['login'] ?? [];

        return $self;
    }

    /**
     * @return array
     */
    public function login(): array
    {
        return $this->logins;
    }
}