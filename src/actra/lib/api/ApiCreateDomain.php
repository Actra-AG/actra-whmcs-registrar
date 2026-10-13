<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiCreateDomain
{
    /**
     * @param list<string> $nameservers
     * @param string $adminContact contact handle, empty for none
     * @param string $billingContact contact handle, empty for none
     */
    public function __construct(
        ApiClient $apiClient,
        int $customerNumber,
        string $domainName,
        array $nameservers,
        string $registrant,
        string $adminContact,
        string $techContact,
        string $billingContact,
    ) {
        $body = [
            'customerNumber' => $customerNumber,
            'domainName' => $domainName,
            'nameservers' => $nameservers,
            'registrant' => $registrant,
            'techContact' => $techContact,
        ];
        if ($adminContact !== '') {
            $body['adminContact'] = $adminContact;
        }
        if ($billingContact !== '') {
            $body['billingContact'] = $billingContact;
        }
        $apiClient->post(
            path: 'createDomain',
            body: $body,
        );
    }
}
