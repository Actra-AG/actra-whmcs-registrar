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
        $postFields = [];
        if ($newNameservers !== null) {
            $postFields['newNameservers'] = $newNameservers;
        }
        if ($removeNameservers !== null) {
            $postFields['removeNameservers'] = $removeNameservers;
        }
        if ($newAdminContact !== null) {
            $postFields['newAdminContact'] = $newAdminContact;
        }
        if ($removeAdminContact !== null) {
            $postFields['removeAdminContact'] = $removeAdminContact;
        }
        if ($newTechContact !== null) {
            $postFields['newTechContact'] = $newTechContact;
        }
        if ($removeTechContact !== null) {
            $postFields['removeTechContact'] = $removeTechContact;
        }
        if ($newBillingContact !== null) {
            $postFields['newBillingContact'] = $newBillingContact;
        }
        if ($removeBillingContact !== null) {
            $postFields['removeBillingContact'] = $removeBillingContact;
        }
        if ($newAuthCode !== null) {
            $postFields['newAuthCode'] = $newAuthCode;
        }
        if ($newRegistrant !== null) {
            $postFields['newRegistrant'] = $newRegistrant;
        }
        if ($postFields === []) {
            return;
        }
        $postFields['domainName'] = $asciiDomainName;
        $apiClient->call(
            action: 'updateDomain',
            postFields: $postFields
        );
    }
}