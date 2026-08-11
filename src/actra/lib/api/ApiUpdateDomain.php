<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiUpdateDomain
{
    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName,
        ?array $newNameservers = null,
        ?array $removeNameservers = null,
        ?string $newAdminContact = null,
        ?string $removeAdminContact = null,
        ?string $newTechContact = null,
        ?string $removeTechContact = null,
        ?string $newBillingContact = null,
        ?string $removeBillingContact = null,
        ?string $newAuthCode = null,
        ?string $newRegistrant = null
    ) {
        $body = [];
        if ($newNameservers !== null) {
            $body['newNameservers'] = $newNameservers;
        }
        if ($removeNameservers !== null) {
            $body['removeNameservers'] = $removeNameservers;
        }
        if ($newAdminContact !== null) {
            $body['newAdminContact'] = $newAdminContact;
        }
        if ($removeAdminContact !== null) {
            $body['removeAdminContact'] = $removeAdminContact;
        }
        if ($newTechContact !== null) {
            $body['newTechContact'] = $newTechContact;
        }
        if ($removeTechContact !== null) {
            $body['removeTechContact'] = $removeTechContact;
        }
        if ($newBillingContact !== null) {
            $body['newBillingContact'] = $newBillingContact;
        }
        if ($removeBillingContact !== null) {
            $body['removeBillingContact'] = $removeBillingContact;
        }
        if ($newAuthCode !== null) {
            $body['newAuthCode'] = $newAuthCode;
        }
        if ($newRegistrant !== null) {
            $body['newRegistrant'] = $newRegistrant;
        }
        if ($body === []) {
            return;
        }
        $apiClient->patch(
            path: 'updateDomain',
            query: [
                'domainName' => $asciiDomainName,
            ],
            body: $body
        );
    }
}