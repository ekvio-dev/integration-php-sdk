<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\LearningProgram;

use DateTimeImmutable;
use RuntimeException;

/**
 * Class ProgramStatisticCriteria
 * @package Ekvio\Integration\Sdk\V2\LearningProgram
 */
class ProgramStatisticCriteria
{
    private const MAX_FILTER_LOGIN = 500;
    private ?string $programStatus = null;
    private ?string $materialStatus = null;
    private ?string $userStatus = null;
    private array $include = [];
    private ?DateTimeImmutable $afterDate = null;
    private ?DateTimeImmutable $toDate = null;
    private array $login = [];
    private bool $isPost = false;

    /**
     * ProgramSearchCriteria constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param array $criteria
     * @return static
     */
    public static function build(array $criteria = []): self
    {
        $self = new self();
        if (!$criteria) {
            return $self;
        }

        return $self;
    }

    /**
     * @return $this
     */
    public function onlyActiveProgram(): self
    {
        $self = clone $this;
        $self->programStatus = 'active';
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyHideProgram(): self
    {
        $self = clone $this;
        $self->programStatus = 'hide';
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyActiveMaterial(): self
    {
        $self = clone $this;
        $self->materialStatus = 'active';
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyHideMaterial(): self
    {
        $self = clone $this;
        $self->materialStatus = 'hide';
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyActiveUser(): self
    {
        $self = clone $this;
        $self->userStatus = 'active';
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyBlockedUser(): self
    {
        $self = clone $this;
        $self->userStatus = 'blocked';
        return $self;
    }

    /**
     * @param array $include
     * @return $this
     */
    public function withInclude(array $include): self
    {
        $self = clone $this;
        $self->include = array_filter($include);
        return $self;
    }

    /**
     * @param DateTimeImmutable $afterDate
     * @return $this
     */
    public function withAfterDate(DateTimeImmutable $afterDate): self
    {
        $self = clone $this;
        $self->afterDate = $afterDate;
        return $self;
    }

    /**
     * @param DateTimeImmutable $toDate
     * @return $this
     */
    public function withToDate(DateTimeImmutable $toDate): self
    {
        $self = clone $this;
        $self->toDate = $toDate;
        return $self;
    }

    /**
     * @param array $login
     * @return $this
     */
    public function filterByLogin(array $login): self
    {
        if(count($login) > self::MAX_FILTER_LOGIN) {
            throw new RuntimeException(sprintf('Maximum count login is %s', self::MAX_FILTER_LOGIN));
        }

        $self = clone $this;
        $self->login = array_filter($login);
        $self->isPost = true;
        return $self;
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return $this->isPost ? 'POST' : 'GET';
    }

    /**
     * @return array
     */
    public function queryParams(): array
    {
        $params = [];
        if($this->programStatus) {
            $params['program_status'] = $this->programStatus;
        }

        if($this->materialStatus) {
            $params['material_status'] = $this->materialStatus;
        }

        if($this->userStatus) {
            $params['user_status'] = $this->userStatus;
        }

        if($this->include) {
            $params['include'] = implode(',', $this->include);
        }

        if($this->toDate) {
            $params['to_date'] = $this->toDate->format(DATE_ATOM);
        }

        if($this->afterDate) {
            $params['after_date'] = $this->afterDate->format(DATE_ATOM);
        }

        return $params;
    }

    /**
     * @return array
     */
    public function body(): array
    {
        $body = ['filters' => []];
        if($this->login) {
            $body['filters']['login'] = $this->login;
        }

        return $body;
    }
}