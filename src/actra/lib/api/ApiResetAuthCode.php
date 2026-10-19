<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiResetAuthCode
{
    public string $authCode;

    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName
    ) {
        $response = $apiClient->post(
            path: 'resetAuthCode',
            timeoutInSeconds: ApiClient::TIMEOUT_REGISTRY_CHANGE,
            query: [
                'domainName' => $asciiDomainName,
            ]
        );
        $this->authCode = $response->getString(key: 'authCode');
    }
}
