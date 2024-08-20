<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\Common;

use DateTimeInterface;

abstract class Criteria implements Criteriable
{
    protected array $params = [];
    protected array $filters = [];

    public function queryParams(): array
    {
        $params = [];
        foreach ($this->params as $field => $value) {
            if(is_array($value)) {
                $value = implode(',', $value);
            }

            if($value instanceof DateTimeInterface) {
                $value = $value->format(DateTimeInterface::ATOM);
            }

            $params[$field] = $value;
        }

        return $params;
    }

    public function body(): array
    {
        if(!$this->filters) {
            return [];
        }

        $body = [];
        foreach ($this->filters as $filter => $value) {
            $body['filters'][$filter] = $value;
        }

        return $body;
    }

    protected function cloneWithParam(string $name, $value): self
    {
        $self = clone $this;
        $self->params[$name] = $value;
        return $self;
    }

    protected function cloneWithFilter(string $name, $value): self
    {
        $self = clone $this;
        $self->filters[$name] = $value;
        return $self;
    }
}