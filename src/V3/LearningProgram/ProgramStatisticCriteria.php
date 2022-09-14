<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\LearningProgram;

use DateTimeImmutable;
use RuntimeException;

/**
 * Class ProgramStatisticCriteria
 * @package Ekvio\Integration\Sdk\V3\LearningProgram
 */
class ProgramStatisticCriteria
{
    private const MAX_FILTER_COUNT = 500;
    private ?string $programStatus = null;
    private ?string $materialStatus = null;
    private ?string $userStatus = null;
    private array $include = [];
    private ?DateTimeImmutable $afterDate = null;
    private ?DateTimeImmutable $toDate = null;
    private array $login = [];
    private array $program = [];
    private array $category = [];
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
     * @param array $logins
     * @return $this
     */
    public function filterByLogin(array $logins): self
    {
        if(count($logins) > self::MAX_FILTER_COUNT) {
            throw new RuntimeException(sprintf('Maximum count login is %s', self::MAX_FILTER_COUNT));
        }

        $self = clone $this;
        $self->login = array_filter($logins, function ($login) {
            return is_string($login) && mb_strlen($login) > 0;
        });
        return $self;
    }

    public function filterById(array $programs): self
    {
        if(count($programs) > self::MAX_FILTER_COUNT) {
            throw new RuntimeException(sprintf('Maximum count id is %s', self::MAX_FILTER_COUNT));
        }

        $self = clone $this;
        $self->program = array_filter($programs, function ($programId) {
            return is_int($programId) && $programId > 0;
        });
        return $self;
    }

    public function filterByCategories(array $categories): self
    {
        if(count($categories) > self::MAX_FILTER_COUNT) {
            throw new RuntimeException(sprintf('Maximum count category is %s', self::MAX_FILTER_COUNT));
        }

        $self = clone $this;
        $self->category = array_filter($categories, function ($categoryId) {
            return is_int($categoryId) && $categoryId > 0;
        });
        return $self;
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return (count($this->login) > 0 || count($this->program) > 0) ? 'POST' : 'GET';
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

        if($this->program) {
            $body['filters']['program'] = $this->program;
        }

        if($this->category) {
            $body['filters']['category'] = $this->category;
        }

        return $body;
    }
}