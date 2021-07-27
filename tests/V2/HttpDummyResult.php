<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\Tests\V2;

use Ekvio\Integration\Sdk\V2\Integration\IntegrationResult;

class HttpDummyResult implements IntegrationResult
{
    public function get(string $url): string
    {
        return $url;
    }
}