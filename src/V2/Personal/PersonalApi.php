<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2\Personal;

use Ekvio\Integration\Sdk\V2\EqueoClient;

/**
 * Class PersonalApi
 */
class PersonalApi implements Personal
{
    private const PERSONAL_ASSIGNMENT_SEARCH_ENDPOINT = '/v2/personals/assignments';
    private EqueoClient $client;
    /**
     * Material constructor.
     * @param EqueoClient $client
     */
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    public function assignments(AssignmentSearchCriteria $criteria): array
    {
        $method = 'GET';
        $body = [];
        if($criteria->filters()) {
            $method = 'POST';
            $body['filters'] = $criteria->filters();
        }

        return $this->client->pagedRequest($method, self::PERSONAL_ASSIGNMENT_SEARCH_ENDPOINT, $criteria->params(), $body);
    }
}