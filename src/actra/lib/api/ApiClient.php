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

    public function call(
        string $action,
        array $postFields,
        int $timeoutInSeconds = 10
    ): void {
        $ch = curl_init();
        curl_setopt(
            handle: $ch,
            option: CURLOPT_URL,
            value: $this->apiUrl . $action
        );
        if ($postFields === []) {
            curl_setopt(
                handle: $ch,
                option: CURLOPT_HTTPGET,
                value: 1
            );
        } else {
            curl_setopt(
                handle: $ch,
                option: CURLOPT_POST,
                value: 1
            );
            curl_setopt(
                handle: $ch,
                option: CURLOPT_POSTFIELDS,
                value: http_build_query(data: $postFields)
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
        if(defined(constant_name: 'CURLOPT_XOAUTH2_BEARER')) {
            curl_setopt(
                handle: $ch,
                option: CURLOPT_HTTPAUTH,
                value: CURLAUTH_BEARER
            );
            curl_setopt(
                handle: $ch,
                option: CURLOPT_XOAUTH2_BEARER,
                value: $this->apiKey
            );
        } else {
            curl_setopt(
                handle: $ch,
                option: CURLOPT_HTTPHEADER,
                value: [
                    'Authorization: Bearer ' . $this->apiKey,
                    'Content-Type: application/x-www-form-urlencoded'
                ]
            );
        }
        $response = curl_exec(handle: $ch);
        if (curl_errno(handle: $ch)) {
            throw new Exception(
                message: 'Connection Error: ' . curl_errno(handle: $ch) . ' - ' . curl_error(handle: $ch)
            );
        }
        curl_close(handle: $ch);
        $this->responseObject = json_decode(json: $response);
        if ($this->log) {
            Log::execute(
                className: static::class,
                params: [
                    'action' => $action,
                    'postFields' => $postFields,
                    'response' => $response
                ]
            );
        }
        if (
            $this->responseObject === null
            && json_last_error() !== JSON_ERROR_NONE
        ) {
            throw new Exception(message: 'Bad response received from API');
        }
        if ($this->responseObject->status !== 'success') {
            throw new Exception(message: $this->responseObject->error->message);
        }
    }

    public function getFromResponse(string $key): string|array|bool|object
    {
        return property_exists(
            object_or_class: $this->responseObject->result,
            property: $key
        ) ? $this->responseObject->result->{$key} : '';
    }

    public static function generateEppAuthCode(): string
    {
        $characters = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ-+/*';
        $charactersLength = strlen(string: $characters);
        $authCode = '';
        for ($i = 0; $i < 16; $i++) {
            $authCode .= $characters[random_int(min: 0, max: $charactersLength - 1)];
        }

        return $authCode;
    }
}