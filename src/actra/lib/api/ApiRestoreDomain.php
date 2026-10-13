<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiRestoreDomain
{
    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName
    ) {
        $apiClient->post(
            path: 'restoreDomain',
            query: [
                'domainName' => $asciiDomainName,
            ]
        );
    }
}
