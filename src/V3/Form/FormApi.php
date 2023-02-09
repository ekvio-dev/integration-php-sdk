<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Form;

use Ekvio\Integration\Sdk\V3\EqueoClient;

class FormApi implements Form
{
    private const FORM_AUTO_ASSIGNMENTS_SEARCH_ENDPOINT = '/v3/forms/auto-assignments/search';
    private const FORM_AUTO_ASSIGNMENTS_ENDPOINT = '/v3/forms/auto-assignments';

    private EqueoClient $client;

    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    public function searchAutoAssignments(FormSearchAutoAssignmentsCriteria $criteria): array
    {
        $response = $this->client->request(
            $criteria->method(),
            self::FORM_AUTO_ASSIGNMENTS_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response['data'];
    }

    public function createAutoAssignments(array $autoAssignments): array
    {
        $response = $this->client->deferredRequest(
            'POST',
            self::FORM_AUTO_ASSIGNMENTS_ENDPOINT,
            [],
            ['data' => $autoAssignments]
        );

        return $response['data'];
    }

    public function updateAutoAssignments(array $autoAssignments): array
    {
        $response = $this->client->deferredRequest(
            'PUT',
            self::FORM_AUTO_ASSIGNMENTS_ENDPOINT,
            [],
            ['data' => $autoAssignments]
        );

        return $response['data'];
    }

    public function deleteAutoAssignments(array $autoAssignments): array
    {
        $response = $this->client->deferredRequest(
            'DELETE',
            self::FORM_AUTO_ASSIGNMENTS_ENDPOINT,
            [],
            ['data' => $autoAssignments]
        );

        return $response['data'];
    }
}