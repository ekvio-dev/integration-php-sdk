<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\File;


interface File
{
    public function load(string $url): string;

    /**
     * @param UploadFile[] $uploadFiles
     * @return array
     */
    public function upload(array $uploadFiles): array;
}