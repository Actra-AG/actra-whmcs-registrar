<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use JsonException;
use RuntimeException;

/**
 * Parses the response envelope of the Actra registrar API (`success`, `data`, `error`).
 */
final class ApiResponse
{
    /**
     * Returns the `data` object of a successful response.
     *
     * @throws RuntimeException for a body that is not JSON (e.g. the HTML page of a proxy)
     * @param string|null $retryAfter value of the `Retry-After` header, if sent
     * @throws ApiException for an unsuccessful response
     */
    public static function parse(string $path, int $httpStatusCode, string $body, ?string $retryAfter): ApiRecord
    {
        try {
            $decoded = json_decode(json: $body, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new RuntimeException(
                message: sprintf('API request "%s" returned an invalid response with HTTP %d', $path, $httpStatusCode),
                previous: $jsonException,
            );
        }
        $response = new ApiRecord(fields: is_array(value: $decoded) ? $decoded : []);
        if ($response->getBool(key: 'success')) {
            return $response->getRecord(key: 'data') ?? new ApiRecord(fields: []);
        }
        $error = $response->getRecord(key: 'error');
        $message = $error?->getNullableString(key: 'message') ?? 'API request failed';
        $code = $error?->getString(key: 'code') ?? '';

        throw new ApiException(
            message: sprintf(
                'API request "%s" failed with HTTP %d: %s%s',
                $path,
                $httpStatusCode,
                $message,
                $code === '' || $code === '0' ? '' : ' (code ' . $code . ')',
            ),
            httpStatusCode: $httpStatusCode,
            errorCode: $code,
            apiMessage: $message,
            retryAfterSeconds: ApiResponse::parseRetryAfter(value: $retryAfter),
        );
    }

    /**
     * Seconds of a `Retry-After` header; null for a missing header or an HTTP date, which the API does not send.
     */
    public static function parseRetryAfter(?string $value): ?int
    {
        $value = trim(string: $value ?? '');

        return preg_match(pattern: '/^\d{1,9}$/', subject: $value) === 1 ? (int) $value : null;
    }
}
