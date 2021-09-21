<?php
declare(strict_types=1);

use Ekvio\Integration\Sdk\V2\EqueoClient;
use Ekvio\Integration\Sdk\V2\Integration\HttpIntegrationResult;
use Ekvio\Integration\Sdk\V2\User\UserApi;
use Ekvio\Integration\Sdk\V2\User\UserSearchCriteria;
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
        'debug_request_body' => true,
    ]
);

$userApi = new UserApi($equeoClient);
$users = $userApi->search(UserSearchCriteria::createFrom([
    'params' => [
        'fields' => ['login', 'forms'],
        'include' => ['forms']
    ],
    'filters' => [
        'login' => ['Test01', 'Test02', '500500', 'svolkov', 'sidorov_ag', 'sergeev_id']
    ]
]));

var_dump($users);