<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use Exception;
use stdClass;
use WHMCS\Module\Registrar\Actra\action\Log;

class ApiClient
{
    private stdClass $responseObject;

    public function __construct(
        private readonly string $apiUrl,
        private readonly string $apiKey,
        public readonly bool $log
    ) {
    }

    public function get(
        string $path,
        array $query = [],
        int $timeoutInSeconds = 10
    ): void {
        $this->request(
            method: 'GET',
            path: $path,
            query: $query,
            body: null,
            timeoutInSeconds: $timeoutInSeconds
        );
    }

    public function post(
        string $path,
        array $query = [],
        array $body = [],
        int $timeoutInSeconds = 10
    ): void {
        $this->request(
            method: 'POST',
            path: $path,
            query: $query,
            body: $body,
            timeoutInSeconds: $timeoutInSeconds
        );
    }

    public function patch(
        string $path,
        array $query = [],
        array $body = [],
        int $timeoutInSeconds = 10
    ): void {
        $this->request(
            method: 'PATCH',
            path: $path,
            query: $query,
            body: $body,
            timeoutInSeconds: $timeoutInSeconds
        );
    }

    public function delete(
        string $path,
        array $query = [],
        int $timeoutInSeconds = 10
    ): void {
        $this->request(
            method: 'DELETE',
            path: $path,
            query: $query,
            body: null,
            timeoutInSeconds: $timeoutInSeconds
        );
    }

    private function request(
        string $method,
        string $path,
        array $query,
        ?array $body,
        int $timeoutInSeconds
    ): void {
        $url = rtrim(
                string: $this->apiUrl,
                characters: '/'
            ) . '/' . ltrim(
                string: $path,
                characters: '/'
            );

        if ($query !== []) {
            $url .= '?' . http_build_query(data: $query);
        }

        $ch = curl_init();
        curl_setopt(
            handle: $ch,
            option: CURLOPT_URL,
            value: $url
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_CUSTOMREQUEST,
            value: $method
        );

        if ($body !== null) {
            curl_setopt(
                handle: $ch,
                option: CURLOPT_POSTFIELDS,
                value: json_encode(
                    value: $body,
                    flags: JSON_THROW_ON_ERROR
                )
            );
        }

        curl_setopt(
            handle: $ch,
            option: CURLOPT_RETURNTRANSFER,
            value: 1
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_SSL_VERIFYHOST,
            value: 2
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_SSL_VERIFYPEER,
            value: 1
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_TIMEOUT,
            value: $timeoutInSeconds
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_HTTPHEADER,
            value: [
                'Authorization: Bearer ' . $this->apiKey,
                'Accept: application/json',
                'Content-Type: application/json',
            ]
        );

        $response = curl_exec(handle: $ch);
        if (curl_errno(handle: $ch)) {
            throw new Exception(
                message: 'Connection Error: ' . curl_errno(handle: $ch) . ' - ' . curl_error(handle: $ch)
            );
        }

        $httpStatusCode = curl_getinfo(
            handle: $ch,
            option: CURLINFO_HTTP_CODE
        );
        curl_close(handle: $ch);

        $this->responseObject = json_decode(
            json: (string)$response,
            flags: JSON_THROW_ON_ERROR
        );

        if ($this->log) {
            Log::execute(
                className: static::class,
                params: [
                    'path' => $path,
                    'method' => $method,
                    'query' => $query,
                    'body' => $body,
                    'httpStatusCode' => $httpStatusCode,
                    'response' => $response,
                ]
            );
        }

        if ($this->responseObject->success !== true) {
            $message = 'API request failed';
            if (
                property_exists(
                    object_or_class: $this->responseObject,
                    property: 'error'
                )
                && property_exists(
                    object_or_class: $this->responseObject->error,
                    property: 'message'
                )
            ) {
                $message = $this->responseObject->error->message;
            }

            throw new Exception(
                message: sprintf(
                    'API request "%s" failed with HTTP %d: %s',
                    $path,
                    $httpStatusCode,
                    $message
                )
            );
        }
    }

    public function getFromResponse(string $key): string|array|bool|object
    {
        if (
            !property_exists(
                object_or_class: $this->responseObject,
                property: 'data'
            )
            || !is_object(value: $this->responseObject->data)
        ) {
            return '';
        }

        return property_exists(
            object_or_class: $this->responseObject->data,
            property: $key
        ) ? $this->responseObject->data->{$key} : '';
    }
}