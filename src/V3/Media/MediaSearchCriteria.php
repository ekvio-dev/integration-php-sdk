<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Media;


class MediaSearchCriteria
{
    private const METHOD = 'POST';
    private array $fields = [];
    private array $filters = [];

    private function __construct(){}

    public static function build(array $criteria = []): self
    {
        $self = new self();
        if(!$criteria) {
            return $self;
        }

        $self->fields = $criteria['fields'] ?? [];
        $self->filters = $criteria['filters'] ?? [];

        return $self;
    }

    public function withFields(array $fields): self
    {
        $self = clone $this;
        $self->fields = $fields;

        return $self;
    }

    public function withFilters(array $filters): self
    {
        $self = clone $this;
        $self->filters = $filters;

        return $self;
    }

    public function method(): string
    {
        return self::METHOD;
    }

    public function queryParams(): array
    {
        $params = [];

        if($this->fields) {
            $params['fields'] = implode(',', $this->fields);
        }

        return $params;
    }

    public function body(): array
    {
        if (!$this->filters) {
            return [];
        }

        return [
            'filters' => $this->filters
        ];
    }
}