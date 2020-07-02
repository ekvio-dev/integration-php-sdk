<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Integration;

use Ekvio\Integration\Sdk\V2\ApiException;

/**
 * Class HttpIntegrationResult
 * @package Ekvio\Integration\Sdk\V2\Integration
 */
class HttpIntegrationResult implements IntegrationResult
{
    /**
     * @param string $url
     * @return string
     * @throws ApiException
     */
    public function get(string $url): string
    {
        $content = file_get_contents($url);
        if($content === false) {
            ApiException::apiFailed(sprintf('Error in retrieve integration result by %s', $url));
        }

        return (string) $content;
    }
}