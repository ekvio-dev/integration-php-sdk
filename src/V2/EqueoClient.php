<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2;

use DateTimeImmutable;
use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V2\Integration\IntegrationResult;
use Psr\Http\Client\ClientInterface;
use Throwable;
use Webmozart\Assert\Assert;

/**
 * Class EqueoClient
 * @package Ekvio\Integration\Sdk\V2
 */
class EqueoClient
{
    private const INTEGRATION_ENDPOINT = '/v2/integration/';
    private const REQUEST_INTERVAL_TIMEOUT = 10;
    private const REQUEST_MAX_COUNT = 100;
    private const REQUEST_MAX_COUNT_BOUNDARY = 0;

    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var IntegrationResult
     */
    private $integrationResult;
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $requestIntervalTimeout = self::REQUEST_INTERVAL_TIMEOUT;

    /**
     * @var bool application debug mode
     */
    private $debug;

    /**
     * @var bool profile request body params in debug mode
     */
    private $debugRequestBody;

    /**
     * Equeo constructor.
     * @param ClientInterface $client
     * @param IntegrationResult $integrationResult
     * @param string $host
     * @param string $token
     * @param array $options
     */
    public function __construct(ClientInterface $client, IntegrationResult $integrationResult, string $host, string $token, array $options = [])
    {
        Assert::notEmpty($host, 'API host required');
        Assert::notEmpty($token, 'API token required');

        $this->client = $client;
        $this->integrationResult = $integrationResult;
        $this->host = $host;
        $this->token = $token;
        $this->configureOptions($options);
    }

    /**
     * @param array $options
     */
    private function configureOptions(array $options): void
    {
        if(array_key_exists('request_interval_timeout', $options) && (int) $options['request_interval_timeout'] > 0) {
            $this->requestIntervalTimeout = (int) $options['request_interval_timeout'];
        }

        if(array_key_exists('debug', $options)) {
            $this->debug = (bool) $options['debug'];
        }

        if(array_key_exists('debug_request_body', $options)) {
            $this->debugRequestBody = (bool) $options['debug_request_body'];
        }
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $queryParams
     * @param array $body
     * @return array
     * @throws ApiException
     */
    public function request(string $method, string $endpoint, array $queryParams = [], array $body = []): array
    {
        $attributes = [
            'verify' => false,
            'headers' => [
                'Authorization' => "Bearer {$this->token}"
            ],
        ];

        try {
            if(in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $attributes['body'] = json_encode($body);
            }

            $url = sprintf('%s%s', $this->host, $endpoint);

            if($queryParams) {
                $url .= '?';
                foreach ($queryParams as $param => $values) {
                    if(is_array($values)) {
                        $values = implode(',', $values);
                    }

                    $url .= sprintf('%s=%s&', $param, $values);
                }
                $url = rtrim($url, '&');
            }

            $this->profile($url, json_encode($body, JSON_UNESCAPED_UNICODE));

            $response = $this->client->request($method, $url, $attributes);
            return json_decode($response->getBody()->getContents(), true);
        } catch (Throwable $exception) {
            ApiException::failedRequest($exception->getMessage());
        }
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $queryParams
     * @param array $body
     * @return array
     * @throws ApiException
     */
    public function pagedRequest(string $method, string $endpoint, array $queryParams = [], array $body = []): array
    {
        $response = $this->request($method, $endpoint, $queryParams, $body);
        $this->raiseExceptionIfErrorResponse($response);

        $data = $response['data'];
        while (isset($response['meta']['pagination']['links']['next'])) {
            $nextUrl = $response['meta']['pagination']['links']['next'];

            $response = $this->request($method, $nextUrl, [], $body);
            $this->raiseExceptionIfErrorResponse($response);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    /**
     * @param array $response
     * @throws ApiException
     */
    private function raiseExceptionIfErrorResponse(array $response): void
    {
        if(isset($response['errors'])) {
            ApiException::apiErrors($response['errors']);
        }

        if(!isset($response['data'])) {
            ApiException::failedRequest('No section data in response from Equeo API');
        }
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $queryParams
     * @param array $body
     * @return array
     * @throws ApiException
     *
     */
    public function deferredRequest(string $method, string $endpoint, array $queryParams = [], array $body = []): array
    {
        $response = $this->request($method, $endpoint, $queryParams, $body);

        if(isset($response['errors'])) {
            ApiException::apiErrors($response['errors']);
        }

        $integration = (int) $response['data']['integration'];

        return $this->integration($integration);
    }

    /**
     * @param int $integrationId
     * @param int $maxCountRequest
     * @return array
     * @throws ApiException
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function integration(int $integrationId, int $maxCountRequest = self::REQUEST_MAX_COUNT): array
    {
        $currentStep = self::REQUEST_MAX_COUNT - $maxCountRequest + 1;
        $this->profile(sprintf('Checking integration task status. Step: %s', $currentStep));

        $uri = sprintf('%s%s', self::INTEGRATION_ENDPOINT, $integrationId);
        $response = $this->request('GET', $uri);

        if(isset($response['errors'])) {
            ApiException::apiErrors($response['errors']);
        }

        $status = $response['data']['status'] ?? null;
        $file = $response['data']['file'] ?? null;

        if(!$status) {
            ApiException::failedRequest(sprintf('Integration status not found in response: %s', json_encode($response)));
        }

        if($status === 'fail') {
            ApiException::failedRequest("Integration failed in {$currentStep} step. Log: {$file}");
        }

        if($status === 'completed') {
            $this->profile(sprintf('Integration ID: %s, status: %s', $integrationId, $status));
            if(!$file) {
                ApiException::apiBadFormatResponse(sprintf('For integration task %s status "completed", but not file link', $integrationId));
            }

            $this->profile(sprintf('Get integration task result in %s', $file));
            $content = json_decode($this->integrationResult->get($file), true);

            if(isset($content['errors'])) {
                ApiException::apiErrors($content['errors']);
            }
            return $content;
        }

        if(self::REQUEST_MAX_COUNT_BOUNDARY < $maxCountRequest) {
            $this->profile(sprintf('Integration ID: %s, status: %s, sleep timeout: %ss', $integrationId, $status, $this->requestIntervalTimeout));
            sleep($this->requestIntervalTimeout);
            return $this->integration($integrationId, $maxCountRequest - 1);
        }

        ApiException::failedRequest(sprintf('For integration %s status %s was not change', $integrationId, $status));
    }

    /**
     * Profile equeo client request
     * @param string $message
     * @param string $bodyRequest
     */
    private function profile(string $message, string $bodyRequest = 'no body parameters'): void
    {
        if($this->debug) {
            $parameters = 'enable option debug_request_body to display request body parameters';
            if($this->debugRequestBody) {
                $parameters = $bodyRequest;
            }

            fwrite(
                STDOUT,
                sprintf('[debug][%s]Request: %s, Parameters: %s'.PHP_EOL,
                    (new DateTimeImmutable())->format('Y-m-d\TH:i:s.uP'),
                    $message,
                    $parameters
                ));
        }
    }
}