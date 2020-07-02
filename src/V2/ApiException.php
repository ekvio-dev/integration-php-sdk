<?php
declare(strict_types=1);

namespace Ekvio\Integration\Sdk\V2;

use Exception;

/**
 * Class ApiException
 * @package Ekvio\Integration\Sdk\V2
 */
class ApiException extends Exception
{
    /**
     * @param string $message
     * @return static
     * @throws ApiException
     */
    public static function apiFailed(string $message): self
    {
        throw new self($message);
    }

    /**
     * @param array $errors
     * @return static
     * @throws ApiException
     */
    public static function apiErrors(array $errors): self
    {
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = sprintf('%s:%s', $error['code'], $error['message']);
        }

        throw new self(implode(',', $messages));
    }

    /**
     * @param string $message
     * @return ApiException
     * @throws ApiException
     */
    public static function failedRequest(string $message): self
    {
        throw new self(sprintf('Bad request: %s', $message));
    }

    /**
     * @param string $message
     * @return ApiException
     * @throws ApiException
     */
    public static function apiBadFormatResponse(string $message): self
    {
        throw new self(sprintf('Bad response format: %s', $message));
    }

    /**
     * @param string $code
     * @param string $message
     * @param array $logs
     * @return ApiException
     * @throws ApiException
     */
    public static function apiErrorResponse(string $code, string $message, array $logs = []): self
    {
        $log = '';
        if($logs !== []) {
            $log = implode(', ', $logs);
        }
        throw new self(sprintf('Error response: code = %s, message = %s, log = %s', $code, $message, $log));
    }
}