<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Training;

/**
 * Class TrainingSearchCriteria
 * @package Ekvio\Integration\Sdk\V2\Training
 */
class TrainingSearchCriteria
{
    private const METHOD = 'GET';
    private array $fields = [];
    private ?string $status = null;
    /**
     * TrainingSearchCriteria constructor.
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
        $self->fields = array_filter($fields);
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyActive(): self
    {
        $self = clone $this;
        $self->status = 'active';
        return $self;
    }

    /**
     * @return $this
     */
    public function onlyHide(): self
    {
        $self = clone $this;
        $self->status = 'hide';
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

        if($this->status) {
            $params['status'] = $this->status;
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