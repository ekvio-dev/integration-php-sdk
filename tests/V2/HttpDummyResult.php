<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\Tests\V2;

use Ekvio\Integration\Sdk\V2\Integration\IntegrationResult;

class HttpDummyResult implements IntegrationResult
{
    private ?string $jsonResult;

    public function __construct(?string $jsonResult = null)
    {
        $this->jsonResult = $jsonResult;
    }

    public function get(string $url): string
    {
        if($this->jsonResult) {
            return $this->jsonResult;
        }

        return $url;
    }
}