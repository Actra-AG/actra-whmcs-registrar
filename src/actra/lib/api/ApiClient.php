<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use RuntimeException;
use WHMCS\Module\Registrar\Actra\action\Log;

class ApiClient
{
    // Requests answered by the API itself (domain information from its cache, contacts)
    public const int TIMEOUT_DEFAULT = 10;
    // Requests that query the registry (availability, host information); longer than the registry timeout of the
    // API (up to 30 seconds), so that its answer for an unreachable registry arrives
    public const int TIMEOUT_REGISTRY_QUERY = 45;
    // Requests that change objects at the registry (domains, hosts, DS records, auth codes)
    public const int TIMEOUT_REGISTRY_CHANGE = 60;

    public function __construct(
        private readonly string $apiUrl,
        private readonly string $apiKey,
        public readonly bool $log,
    ) {}

    /**
     * @param array<string, string|int> $query
     */
    public function get(
        string $path,
        array $query = [],
        int $timeoutInSeconds = ApiClient::TIMEOUT_DEFAULT,
    ): ApiRecord {
        return $this->request(
            method: 'GET',
            path: $path,
            query: $query,
            body: null,
            timeoutInSeconds: $timeoutInSeconds,
        );
    }

    /**
     * @param array<string, string|int> $query
     * @param array<string, mixed> $body JSON request body
     */
    public function post(
        string $path,
        array $query = [],
        array $body = [],
        int $timeoutInSeconds = ApiClient::TIMEOUT_DEFAULT,
    ): ApiRecord {
        return $this->request(
            method: 'POST',
            path: $path,
            query: $query,
            body: $body,
            timeoutInSeconds: $timeoutInSeconds,
        );
    }

    /**
     * @param array<string, string|int> $query
     * @param array<string, mixed> $body JSON request body
     */
    public function patch(
        string $path,
        array $query = [],
        array $body = [],
        int $timeoutInSeconds = ApiClient::TIMEOUT_DEFAULT,
    ): ApiRecord {
        return $this->request(
            method: 'PATCH',
            path: $path,
            query: $query,
            body: $body,
            timeoutInSeconds: $timeoutInSeconds,
        );
    }

    /**
     * @param array<string, string|int> $query
     */
    public function delete(
        string $path,
        array $query = [],
        int $timeoutInSeconds = ApiClient::TIMEOUT_DEFAULT,
    ): ApiRecord {
        return $this->request(
            method: 'DELETE',
            path: $path,
            query: $query,
            body: null,
            timeoutInSeconds: $timeoutInSeconds,
        );
    }

    /**
     * @param array<string, string|int> $query
     * @param array<string, mixed>|null $body JSON request body
     */
    private function request(
        string $method,
        string $path,
        array $query,
        ?array $body,
        int $timeoutInSeconds,
    ): ApiRecord {
        $url = rtrim(
            string: $this->apiUrl,
            characters: '/',
        ) . '/' . ltrim(
            string: $path,
            characters: '/',
        );

        if ($query !== []) {
            $url .= '?' . http_build_query(data: $query);
        }

        $ch = curl_init();
        curl_setopt(
            handle: $ch,
            option: CURLOPT_URL,
            value: $url,
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_CUSTOMREQUEST,
            value: $method,
        );

        if ($body !== null) {
            curl_setopt(
                handle: $ch,
                option: CURLOPT_POSTFIELDS,
                value: json_encode(
                    value: $body,
                    flags: JSON_THROW_ON_ERROR,
                ),
            );
        }

        curl_setopt(
            handle: $ch,
            option: CURLOPT_RETURNTRANSFER,
            value: true,
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_SSL_VERIFYHOST,
            value: 2,
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_SSL_VERIFYPEER,
            value: true,
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_TIMEOUT,
            value: $timeoutInSeconds,
        );
        curl_setopt(
            handle: $ch,
            option: CURLOPT_HTTPHEADER,
            value: [
                'Authorization: Bearer ' . $this->apiKey,
                'Accept: application/json',
                'Content-Type: application/json',
            ],
        );

        $response = curl_exec(handle: $ch);
        $errorNumber = curl_errno(handle: $ch);
        if ($errorNumber !== 0) {
            throw new RuntimeException(
                message: 'Connection Error: ' . $errorNumber . ' - ' . curl_error(handle: $ch),
            );
        }

        $httpStatusCode = curl_getinfo(
            handle: $ch,
            option: CURLINFO_HTTP_CODE,
        );
        $responseBody = is_string(value: $response) ? $response : '';

        // Logged before parsing, so invalid and failed responses are logged too
        if ($this->log) {
            Log::execute(
                className: static::class,
                params: [
                    'path' => $path,
                    'method' => $method,
                    'query' => $query,
                    'body' => $body,
                    'httpStatusCode' => $httpStatusCode,
                    'response' => $responseBody,
                ],
            );
        }

        return ApiResponse::parse(
            path: $path,
            httpStatusCode: $httpStatusCode,
            body: $responseBody,
        );
    }
}
