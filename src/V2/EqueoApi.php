<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2;

use Ekvio\Integration\Sdk\V2\Integration\HttpIntegrationResult;
use Ekvio\Integration\Sdk\V2\Kpi\KpiApi;
use Ekvio\Integration\Sdk\V2\LearningProgram\LearningProgram;
use Ekvio\Integration\Sdk\V2\Material\Material;
use Ekvio\Integration\Sdk\V2\Personal\Personal;
use Ekvio\Integration\Sdk\V2\Task\Task;
use Ekvio\Integration\Sdk\V2\User\UserApi;
use GuzzleHttp\Client;

/**
 * Class EqueoApi
 * @package Ekvio\Integration\Sdk\V2
 */
class EqueoApi
{
    const HTTP_CLIENT_CONNECTION_TIMEOUT = 10;
    /**
     * @var EqueoClient
     */
    private $equeoClient;
    /**
     * @var LearningProgram
     */
    public $program;
    /**
     * @var Material
     */
    public $material;
    /**
     * @var Personal
     */
    public $personal;
    /**
     * @var Task
     */
    public $task;
    /**
     * @var UserApi
     */
    public $userApi;
    /**
     * @var KpiApi
     */
    public $kpiApi;

    /**
     * EqueoApi constructor.
     * @param string $host
     * @param string $token
     * @param array $options
     */
    public function __construct(string $host, string $token, array $options = [])
    {
        $this->equeoClient = $this->buildEqueoClient($host, $token, $options);
        $this->program = new LearningProgram($this->equeoClient);
        $this->material = new Material($this->equeoClient);
        $this->personal = new Personal($this->equeoClient);
        $this->task = new Task($this->equeoClient);
        $this->userApi = new UserApi($this->equeoClient);
        $this->kpiApi = new KpiApi($this->equeoClient);
    }

    /**
     * @param string $host
     * @param string $token
     * @param array $options
     * @return EqueoClient
     */
    private function buildEqueoClient(string $host, string $token, array $options = []): EqueoClient
    {
        $httpClientConfig = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'http_errors' => false,
            'connect_timeout' => self::HTTP_CLIENT_CONNECTION_TIMEOUT
        ];

        $httpClient = new Client(array_merge_recursive($httpClientConfig, $options));
        $result = new HttpIntegrationResult();

        return new EqueoClient($httpClient, $result, $host, $token);
    }
}