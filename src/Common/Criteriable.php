<?php

declare(strict_types=1);

namespace Ekvio\Integration\Sdk\Common;

interface Criteriable
{
    public function method(): string;
    public function queryParams(): array;
    public function body(): array;
}