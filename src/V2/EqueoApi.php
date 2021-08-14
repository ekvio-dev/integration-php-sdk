<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2;

use Ekvio\Integration\Sdk\V2\Event\Event;
use Ekvio\Integration\Sdk\V2\Event\EventApi;
use Ekvio\Integration\Sdk\V2\Integration\HttpIntegrationResult;
use Ekvio\Integration\Sdk\V2\Kpi\Kpi;
use Ekvio\Integration\Sdk\V2\Kpi\KpiApi;
use Ekvio\Integration\Sdk\V2\LearningProgram\Program;
use Ekvio\Integration\Sdk\V2\LearningProgram\ProgramApi;
use Ekvio\Integration\Sdk\V2\Material\Material;
use Ekvio\Integration\Sdk\V2\Personal\Personal;
use Ekvio\Integration\Sdk\V2\Personal\PersonalApi;
use Ekvio\Integration\Sdk\V2\Task\Task;
use Ekvio\Integration\Sdk\V2\Training\Training;
use Ekvio\Integration\Sdk\V2\Training\TrainingApi;
use Ekvio\Integration\Sdk\V2\User\User;
use Ekvio\Integration\Sdk\V2\User\UserApi;
use GuzzleHttp\Client;

/**
 * Class EqueoApi
 * @package Ekvio\Integration\Sdk\V2
 */
class EqueoApi
{
    private const HTTP_CLIENT_CONNECTION_TIMEOUT = 10;
    private EqueoClient $equeoClient;
    public Program $program;
    public Material $material;
    public Personal $personal;
    public Task $task;
    public User $userApi;
    public Kpi $kpiApi;
    public Training $training;
    public Event $event;

    /**
     * EqueoApi constructor.
     * @param string $host
     * @param string $token
     * @param array $options
     */
    public function __construct(string $host, string $token, array $options = [])
    {
        $this->equeoClient = $this->buildEqueoClient($host, $token, $options);
        $this->program = new ProgramApi($this->equeoClient);
        $this->material = new Material($this->equeoClient);
        $this->personal = new PersonalApi($this->equeoClient);
        $this->task = new Task($this->equeoClient);
        $this->userApi = new UserApi($this->equeoClient);
        $this->kpiApi = new KpiApi($this->equeoClient);
        $this->training = new TrainingApi($this->equeoClient);
        $this->event = new EventApi($this->equeoClient);
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