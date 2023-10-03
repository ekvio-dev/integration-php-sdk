<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Information;


use Ekvio\Integration\Sdk\V3\EqueoClient;
use Webmozart\Assert\Assert;

class InformationApi implements Information
{
    private const CHUNK = 500;
    private const INFORMATION_ENDPOINT = '/v3/informations';
    private const INFORMATION_SORT_ENDPOINT = '/v3/informations/sort/';
    private const SORT_ATTRIBUTES = ['name'];
    private EqueoClient $client;
    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    public function search(SearchInformationCriteria $criteria): array
    {
        return $this->client->pagedRequest('GET', self::INFORMATION_ENDPOINT, $criteria->params());
    }

    public function create(array $information): array
    {
        $data = [];
        foreach (array_chunk($information, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('POST', self::INFORMATION_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    public function update(array $information): array
    {
        $data = [];
        foreach (array_chunk($information, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('PUT', self::INFORMATION_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    public function delete(array $information): array
    {
        $data = [];
        foreach (array_chunk($information, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('DELETE', self::INFORMATION_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    public function sort(string $attribute = 'name'): array
    {
        Assert::inArray($attribute, self::SORT_ATTRIBUTES);

        return $this->client->deferredRequest('POST', self::INFORMATION_SORT_ENDPOINT . $attribute);
    }
}