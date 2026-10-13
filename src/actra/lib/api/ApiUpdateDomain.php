<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

/**
 * Changes a domain to the given target state; the API calculates the changes at the registry. A null argument leaves
 * the value untouched, an empty contact handle removes the contact.
 */
readonly class ApiUpdateDomain
{
    /**
     * @param list<string>|null $nameservers complete list of nameservers the domain should have afterwards
     */
    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName,
        ?array $nameservers = null,
        ?string $registrant = null,
        ?string $adminContact = null,
        ?string $techContact = null,
        ?string $billingContact = null,
        ?string $newAuthCode = null,
    ) {
        $body = array_filter(
            array: [
                'nameservers' => $nameservers,
                'registrant' => $registrant,
                'adminContact' => $adminContact,
                'techContact' => $techContact,
                'billingContact' => $billingContact,
                'newAuthCode' => $newAuthCode,
            ],
            callback: static fn(array|string|null $value): bool => $value !== null,
        );
        // The API rejects a request that does not change anything
        if ($body === []) {
            return;
        }
        $apiClient->patch(
            path: 'updateDomain',
            query: [
                'domainName' => $asciiDomainName,
            ],
            body: $body,
        );
    }
}
