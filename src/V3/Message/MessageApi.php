<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V3\Message;

use Ekvio\Integration\Sdk\ApiException;
use Ekvio\Integration\Sdk\V3\EqueoClient;

class MessageApi implements Message
{
    private const MESSAGES_ASSIGNMENT_PERSONAL = '/v3/messages/assignments/personal';
    private const MESSAGES_COPY_ENDPOINT = '/v3/messages/copy';
    private const MESSAGE_ENDPOINT = '/v3/messages';

    private EqueoClient $client;

    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws ApiException
     */
    public function copyMessages(array $messages): array
    {
        $response = $this->client->deferredRequest(
            'POST',
            self::MESSAGES_COPY_ENDPOINT,
            [],
            ['data' => $messages]
        );

        return $response['data'];
    }

    /**
     * @throws ApiException
     */
    public function updateMessages(array $messages): array
    {
        $response = $this->client->deferredRequest(
            'PUT',
            self::MESSAGE_ENDPOINT,
            [],
            ['data' => $messages]
        );

        return $response['data'];
    }

    /**
     * @param array $assignments
     * @return array
     * @throws ApiException
     */
    public function createIndividualAssignments(array $assignments): array
    {
        $response = $this->client->deferredRequest(
            'POST',
            self::MESSAGES_ASSIGNMENT_PERSONAL,
            [],
            ['data' => $assignments]
        );

        return $response['data'];
    }
}
