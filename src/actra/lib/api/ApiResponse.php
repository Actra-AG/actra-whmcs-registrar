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
 * Parses the response envelope of the Actra Domains REST API (`success`, `data`, `error`).
 */
final class ApiResponse
{
    /**
     * Returns the `data` object of a successful response.
     *
     * @throws RuntimeException for a body that is not JSON (e.g. the HTML page of a proxy) or an unsuccessful response
     */
    public static function parse(string $path, int $httpStatusCode, string $body): ApiRecord
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

        throw new RuntimeException(message: sprintf(
            'API request "%s" failed with HTTP %d: %s%s',
            $path,
            $httpStatusCode,
            $message,
            $code === '' || $code === '0' ? '' : ' (code ' . $code . ')',
        ));
    }
}
