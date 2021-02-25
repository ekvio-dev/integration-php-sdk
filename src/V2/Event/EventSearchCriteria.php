<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Event;

/**
 * Class EventSearchCriteria
 * @package Ekvio\Integration\Sdk\V2\Event
 */
class EventSearchCriteria
{
    private const METHOD = 'GET';
    private array $fields = [];
    private ?string $status = null;
    private array $type = [];

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
     * @param array $type
     * @return $this
     */
    public function withType(array $type): self
    {
        $self = clone $this;
        $self->type = array_filter($type);
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

        if($this->type) {
            $params['type'] = implode(',', $this->type);
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