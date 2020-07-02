<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2;

/**
 * Class EqueoApiResponse
 * @package Ekvio\Integration\Sdk\V2
 */
class EqueoApiResponse
{
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var array
     */
    private $errors = [];

    /**
     * EqueoApiResponse constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param string $response
     * @return static
     */
    public static function createFromJsonResponse(string $response): self
    {
        $content = json_decode($response, true);

        $response = new self();
        if(isset($content['errors'])) {
            $response->errors = $content['errors'];
        }

        if(isset($content['data'])) {
            $response->data = $content['data'];
        }

        return $response;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return $this->data();
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return count($this->errors) > 0;
    }
}