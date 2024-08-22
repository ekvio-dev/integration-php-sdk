<?php

use Ekvio\Integration\Sdk\Common\Integration\HttpIntegrationResult;
use Ekvio\Integration\Sdk\V3\EqueoClient;
use Ekvio\Integration\Sdk\V3\Material\MaterialApi;
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

$materialApi = new MaterialApi($equeoClient);
$materialApi->createDocument([
    ['name' => 'test',],
    ['name' => 'test2',],
]);