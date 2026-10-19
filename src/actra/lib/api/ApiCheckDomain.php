<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiCheckDomain
{
    public bool $isAvailable;

    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName
    ) {
        $response = $apiClient->get(
            path: 'checkDomain',
            timeoutInSeconds: ApiClient::TIMEOUT_REGISTRY_QUERY,
            query: [
                'domainName' => $asciiDomainName,
            ]
        );
        $this->isAvailable = $response->getBool(key: 'isAvailable');
    }
}
