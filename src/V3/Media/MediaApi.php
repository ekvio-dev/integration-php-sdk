<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\Media;


use Ekvio\Integration\Sdk\V3\EqueoClient;

class MediaApi implements Media
{
    private const CHUNK = 100;
    private const MEDIA_SEARCH_ENDPOINT = '/v3/media/search';
    private const MEDIA_CREATE_ENDPOINT = '/v3/media';
    private EqueoClient $client;

    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }
    public function create(array $media): array
    {
        $data = [];
        foreach (array_chunk($media, self::CHUNK, true) as $chunk) {
            $response = $this->client->deferredRequest('POST', self::MEDIA_CREATE_ENDPOINT, [], [
                'data' => $chunk
            ]);

            $data = array_merge($data, $response['data']);
        }

        return $data;
    }

    public function search(MediaSearchCriteria $criteria): array
    {
        return $this->client->pagedRequest(
            $criteria->method(),
            self::MEDIA_SEARCH_ENDPOINT,
            $criteria->queryParams(),
            $criteria->body()
        );
    }
}