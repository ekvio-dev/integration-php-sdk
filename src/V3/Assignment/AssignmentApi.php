<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Assignment;


use Ekvio\Integration\Sdk\V3\EqueoClient;

class AssignmentApi implements Assignment
{
    private const SEARCH_ASSIGNMENT_ENDPOINT = '/v3/assignments/search/:entity';
    private const CREATE_GROUP_ASSIGNMENT_ENDPOINT = '/v3/assignments/:entity/group';
    private const CREATE_PERSONAL_ASSIGNMENT_ENDPOINT = '/v3/assignments/:entity/personal';

    private EqueoClient $client;
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }
    public function search(SearchAssignmentCriteria $criteria): array
    {
        $endpoint = str_replace(':entity', $criteria->entity(), self::SEARCH_ASSIGNMENT_ENDPOINT);
        $response = $this->client->cursorRequest(
            $criteria->method(),
            $endpoint,
            $criteria->queryParams(),
            $criteria->body()
        );

        return $response['data'];
    }

    public function createPersonal(CreatePersonalAssignments $collection): array
    {
        $data = [];
        $endpoint = str_replace(':entity', $collection->entity(), self::CREATE_PERSONAL_ASSIGNMENT_ENDPOINT);
        foreach (array_chunk($collection->assignments(), 500, true) as $chunk) {
            $response = $this->client->deferredRequest('POST', $endpoint, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    public function createGroup(CreateGroupAssignments $collection): array
    {
        $data = [];
        $endpoint = str_replace(':entity', $collection->entity(), self::CREATE_GROUP_ASSIGNMENT_ENDPOINT);
        foreach (array_chunk($collection->assignments(), 25, true) as $chunk) {
            $response = $this->client->deferredRequest('POST', $endpoint, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }
}