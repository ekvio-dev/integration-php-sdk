<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Integration;

/**
 * Interface IntegrationResult
 * @package Ekvio\Integration\Sdk\V2\Integration
 */
interface IntegrationResult
{
    /**
     * @param string $url
     * @return string
     */
    public function get(string $url): string;
}