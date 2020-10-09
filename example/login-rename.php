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
$response = $userApi->rename([
    ['from' => 'Test.01', 'to' => 'Test.001'],
    ['from' => 'Test.02', 'to' => 'Test.002'],
]);

var_dump($response);