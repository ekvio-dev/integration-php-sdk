<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Information;


class SearchInformationCriteria
{
    private array $params;
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function params(): array
    {
        return $this->params;
    }
}