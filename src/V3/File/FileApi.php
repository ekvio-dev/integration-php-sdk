<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\File;


use Ekvio\Integration\Sdk\V3\EqueoClient;
use GuzzleHttp\Psr7\Utils;
use Webmozart\Assert\Assert;

class FileApi implements File
{
    private const FILE_UPLOAD_ENDPOINT = '/v3/file/upload';
    private EqueoClient $client;

    public function __construct(EqueoClient $client)
    {
        $this->client = $client;
    }

    public function load(string $url): string
    {
        return $this->client->getFileFromUrl($url);
    }

    public function upload(array $uploadFiles): array
    {
        $arr = [];
        foreach ($uploadFiles as $uploadFile) {
            Assert::isInstanceOf($uploadFile, UploadFile::class);
            $arr[] = [
                'name' => 'files',
                'contents' => Utils::tryFopen($uploadFile->path(), 'r'),
                'filename' => $uploadFile->filename(),
            ];
        }

        $response = $this->client->request('POST', self::FILE_UPLOAD_ENDPOINT, [], [
            'multipart' => $arr
        ]);

        if (!isset($response['success'])) {
            throw new \RuntimeException('Some errors in upload files');
        }

        foreach ($response['success'] as $index => $result) {
            $file = $uploadFiles[$index];
            $file->addToken($result['token']);
        }

        return $uploadFiles;
    }
}