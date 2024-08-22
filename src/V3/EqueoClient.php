<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3;

use DateTimeImmutable;
use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\Common\Integration\IntegrationResult;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Webmozart\Assert\Assert;

class EqueoClient
{
    private const STATUS_OK = 200;
    private const REQUEST_RETRY_STATUSES = [502];
    private const INTEGRATION_ENDPOINT = '/v3/integration/';
    private const INTEGRATION_REQUEST_INTERVAL_TIMEOUT = 10;
    private const INTEGRATION_REQUEST_RETRY_COUNT = 100;
    private const INTEGRATION_RETRY_COUNT_STOP = 0;
    private const DEFAULT_CHUNK_SIZE = 500;

    private ClientInterface $client;
    private IntegrationResult $integrationResult;
    private string $host;
    private string $token;
    private int $requestIntervalTimeout = self::INTEGRATION_REQUEST_INTERVAL_TIMEOUT;
    private bool $debug = false;
    private bool $debugRequestBody;
    private int $retryCount;
    private bool $requestInterval = true;
    private int $requestRetryCount = 5;
    private int $requestRetryTimeout = 15;
    private array $httpClientOptions = [
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ],
        'http_errors' => false,
        'verify' => false
    ];

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

    private function configureOptions(array $options): void
    {
        if(array_key_exists('request_interval', $options) && is_bool($options['request_interval'])) {
            $this->requestInterval = $options['request_interval'];
        }

        if(array_key_exists('request_interval_timeout', $options) && (int) $options['request_interval_timeout'] > 0) {
            $this->requestIntervalTimeout = (int) $options['request_interval_timeout'];
        }

        if(array_key_exists('debug', $options)) {
            $this->debug = (bool) $options['debug'];
        }

        if(array_key_exists('debug_request_body', $options)) {
            $this->debugRequestBody = (bool) $options['debug_request_body'];
        }

        if(array_key_exists('http_client', $options) && is_array($options['http_client'])) {
            $this->httpClientOptions = $options['http_client'];
        }

        if (array_key_exists('request_retry_count', $options) && is_int($options['request_retry_count'])) {
            Assert::natural($options['request_retry_count'],'Request retry count must be an positive integer');

            $this->requestRetryCount = $options['request_retry_count'];
        }

        if (array_key_exists('request_retry_timeout', $options) && is_int($options['request_retry_timeout'])) {
            Assert::natural($options['request_retry_timeout'], 'Request retry timeout must be an positive integer');

            $this->requestRetryTimeout = $options['request_retry_timeout'];
        }

        if(array_key_exists('retry_count', $options) && is_int($options['retry_count'])) {
            Assert::natural($options['retry_count']);
            Assert::lessThan($options['retry_count'], self::INTEGRATION_REQUEST_RETRY_COUNT);

            $this->retryCount = $options['retry_count'];
        } else {
            $this->retryCount = self::INTEGRATION_REQUEST_RETRY_COUNT;
        }
    }

    /**
     * @throws ApiException
     */
    public function request(string $method, string $endpoint, array $queryParams = [], array $body = [], array $options = []): array
    {
        $attributes = array_replace_recursive($this->httpClientOptions, [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            $options
        ]);

        try {
            if(!isset($body['multipart']) && in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $attributes['body'] = json_encode($body);
            } elseif (isset($body['multipart'])) {
                unset($attributes['headers']['Content-Type']);
                $attributes = $attributes + $body;
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

            if(!isset($attributes['multipart'])) {
                $this->profile($url, json_encode($body, JSON_UNESCAPED_UNICODE));
            }

            $response = $this->makeRetryRequest($method, $url, $attributes);

            if($response->getStatusCode() !== self::STATUS_OK){
                ApiException::failedRequest(sprintf('For request %s get response with code %s and reason %s', $url, $response->getStatusCode(), $response->getReasonPhrase()));
            }

            $content = $response->getBody()->getContents();
            if(!$content) {
                ApiException::failedRequest(sprintf('For request %s get null response', $url));
            }

            $response = json_decode($content, true, JSON_THROW_ON_ERROR);
            if(!is_array($response)) {
                ApiException::failedRequest(sprintf('For request %s get not array after json_decode()', $url));
            }

            return $response;
        } catch (Throwable $exception) {
            ApiException::failedRequest($exception->getMessage());
        }
    }

    public function getFileFromUrl($url)
    {
        $attributes = array_replace_recursive($this->httpClientOptions, [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
        ]);

        $response = $this->client->request('GET', $url, $attributes);

        if($response->getStatusCode() !== self::STATUS_OK){
            ApiException::failedRequest(sprintf('For request %s get response with code %s and reason %s', $url, $response->getStatusCode(), $response->getReasonPhrase()));
        }

        $content = $response->getBody()->getContents();

        if(!$content) {
            ApiException::failedRequest(sprintf('For request %s get null response', $url));
        }

        return $content;
    }

    /**
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

    public function cursorRequest(string $method, string $endpoint, array $queryParams = [], array $body = []): array
    {
        $response = $this->request($method, $endpoint, $queryParams, $body);
        $this->raiseExceptionIfErrorResponse($response);

        $data = $response['data'];
        while (isset($response['meta']['cursor']['links']['next'])) {
            $nextUrl = $response['meta']['cursor']['links']['next'];

            $response = $this->request($method, $nextUrl, [], $body);
            $this->raiseExceptionIfErrorResponse($response);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    /**
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
     * @throws ApiException
     */
    public function deferredRequest(string $method, string $endpoint, array $queryParams = [], array $body = []): array
    {
        $response = $this->request($method, $endpoint, $queryParams, $body);

        if(isset($response['errors'])) {
            ApiException::apiErrors($response['errors']);
        }

        $integration = $response['data']['integration'] ?? null;
        if(is_null($integration)) {
            ApiException::apiBadFormatResponse('not integration structure for deferred request');
        }

        $integration = (int) $response['data']['integration'];
        if($integration <= 0) {
            ApiException::failedRequest('integration ID must be natural integer');
        }

        return $this->integration($integration, $this->retryCount);
    }

    public function defaultDeferredRequest(string $method, string $endpoint, array $items, array $options = []): array
    {
        $chunkSize = $options['chunk_size'] ?? self::DEFAULT_CHUNK_SIZE;
        Assert::integer($chunkSize, 'Option chunkSize must be an integer');
        Assert::greaterThan($chunkSize, 0, 'Option chunkSize must be greater than zero');
        Assert::lessThanEq($chunkSize, self::DEFAULT_CHUNK_SIZE, 'Option chunkSize must be less than 500');

        $data = [];
        foreach (array_chunk($items, $chunkSize, true) as $chunk) {
            $response = $this->deferredRequest($method, $endpoint, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    /**
     * @throws ApiException
     */
    public function integration(int $integrationId, int $retryCount = self::INTEGRATION_REQUEST_RETRY_COUNT): array
    {
        $currentStep = ($this->retryCount + 1) - $retryCount;
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

            if (isset($content['data'])) {
                return $content;
            }
        }

        if($retryCount > self::INTEGRATION_RETRY_COUNT_STOP) {

            if($this->requestInterval) {
                $this->profile(sprintf('Integration ID: %s, status: %s, sleep timeout: %ss', $integrationId, $status, $this->requestIntervalTimeout));
                sleep($this->requestIntervalTimeout);
            }

            return $this->integration($integrationId, $retryCount - 1);
        }

        ApiException::failedRequest(sprintf('integration %s status %s was not change or data from link %s is empty', $integrationId, $status, $file));
    }

    private function makeRetryRequest(string $method, string $url, array $attributes = []): ResponseInterface
    {
        $this->profile(sprintf('Start request %s %s', $method, $url));
        $response = $this->client->request($method, $url, $attributes);

        $cnt = 1;
        while (in_array($response->getStatusCode(), self::REQUEST_RETRY_STATUSES, true) && $cnt <= $this->requestRetryCount) {
            sleep($this->requestRetryTimeout);

            $this->profile(sprintf('Retry request %s %s. Attempt %s after %ss sleep', $method, $url, $cnt, $this->requestRetryTimeout));
            $response = $this->client->request($method, $url, $attributes);

            $cnt++;
        }

        return $response;
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