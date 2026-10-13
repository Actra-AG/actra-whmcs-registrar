<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiCreateDomain
{
    public function __construct(
        ApiClient $apiClient,
        string $domainName,
        array $nameservers,
        string $registrant,
        string $adminContact,
        string $techContact,
        string $billingContact
    ) {
        $apiClient->post(
            path: 'createDomain',
            body: [
                'domainName' => $domainName,
                'nameservers' => $nameservers,
                'registrant' => $registrant,
                'adminContact' => $adminContact,
                'techContact' => $techContact,
                'billingContact' => $billingContact,
            ]
        );
    }
}
