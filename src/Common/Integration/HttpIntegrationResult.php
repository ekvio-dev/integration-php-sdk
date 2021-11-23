<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\Common\Integration;

use Ekvio\Integration\Sdk\ApiException;

/**
 * Class HttpIntegrationResult
 * @package Ekvio\Integration\Sdk\V2\Integration
 */
class HttpIntegrationResult implements IntegrationResult
{
    /**
     * @var string|null
     */
    private $fileHost;

    /**
     * HttpIntegrationResult constructor.
     * @param string|null $fileHost
     */
    public function __construct(?string $fileHost = null)
    {
        $this->fileHost = $fileHost;
    }

    /**
     * @param string $url
     * @return string
     * @throws ApiException
     */
    public function get(string $url): string
    {
        if($this->fileHost) {
            $url = $this->modifyUrlHost($url);
        }

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

        $content = file_get_contents($url, false, $context);
        if($content === false) {
            ApiException::apiFailed(sprintf('Error in retrieve integration result by %s', $url));
        }

        return (string) $content;
    }

    /**
     * @param string $url
     * @return string
     */
    private function modifyUrlHost(string $url): string
    {
        $url = parse_url($url);
        $path = $url['path'] ?? '';

        return sprintf('%s%s', $this->fileHost, $path);
    }
}