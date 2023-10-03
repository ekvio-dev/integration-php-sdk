<?php

declare(strict_types=1);


namespace Ekvio\Integration\Sdk\V3\File;


class UploadFile
{
    private string $path;
    private ?string $filename;
    private ?string $token;
    public function __construct(string $path, ?string $filename = null)
    {
        $this->path = $path;
        $this->filename = $filename;
    }

    public function addToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function token(): ?string
    {
        return $this->token;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function filename(): string
    {
        if ($this->filename) {
            return $this->filename;
        }

        return basename($this->path);
    }
}