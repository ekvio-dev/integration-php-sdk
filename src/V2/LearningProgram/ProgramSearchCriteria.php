<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\LearningProgram;

/**
 * Class ProgramSearchCriteria
 * @package Ekvio\Integration\Sdk\V2\LearningProgram
 */
class ProgramSearchCriteria
{
    private const METHOD = 'GET';
    private array $fields = [];
    private array $include = [];
    private ?string $programStatus = null;
    private ?string $materialStatus = null;
    /**
     * ProgramSearchCriteria constructor.
     */
    private function __construct(){}

    /**
     * @param array $criteria
     * @return static
     */
    public static function build(array $criteria = []): self
    {
        $self = new self();
        if(!$criteria) {
            return $self;
        }

        return $self;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function withFields(array $fields): self
    {
        $self = clone $this;
        $self->fields = $fields;
        return $self;
    }

    /**
     * @param array $include
     * @return $this
     */
    public function withInclude(array $include): self
    {
        $self = clone $this;
        $self->include = $include;
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
     * @return string
     */
    public function method(): string
    {
        return self::METHOD;
    }

    /**
     * @return array
     */
    public function queryParams(): array
    {
        $params = [];

        if($this->fields) {
            $params['fields'] = implode(',', $this->fields);
        }

        if($this->programStatus) {
            $params['program_status'] = $this->programStatus;
        }

        if($this->materialStatus) {
            $params['material_status'] = $this->materialStatus;
        }

        if($this->include) {
            $params['include'] = implode(',', $this->include);
        }

        return $params;
    }

    /**
     * @return array
     */
    public function body(): array
    {
        return [];
    }
}