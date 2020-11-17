<?php
declare(strict_types=1);

use Ekvio\Integration\Sdk\V2\EqueoClient;
use Ekvio\Integration\Sdk\V2\Integration\HttpIntegrationResult;
use Ekvio\Integration\Sdk\V2\User\UserApi;
use GuzzleHttp\Client;

require_once __DIR__ . '/../vendor/autoload.php';

$httpClient = new Client([
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ],
    'http_errors' => false
]);

$equeoClient = new EqueoClient(
    $httpClient,
    new HttpIntegrationResult(),
    getenv('INTEGRATION_HOST'),
    getenv('INTEGRATION_TOKEN'),
    [
        'request_interval_timeout' => 10,
        'debug' => true,
        'debug_request_body' => true
    ]
);

$userApi = new UserApi($equeoClient);
$response = $userApi->sync([
    ['login' => 'test', 'first_name' => 'ivan', 'last_name' => 'ivanov'],
    ['login' => 'test2', 'first_name' => 'petr', 'last_name' => 'petrov'],
], [
    'partial_sync' => true,
    'chief_sync' => true
]);

var_dump($response);