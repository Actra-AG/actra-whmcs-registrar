<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Domain\Registrar\Domain;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;

class GetDomainInformation extends AbstractAction
{
    public readonly Domain $domain;

    protected function execute(): void
    {
        $params = $this->params;
        $apiInfoDomain = new ApiInfoDomain(
            apiClient: $this->getApiClient(),
            asciiDomainName: $params['domain_punycode']
        );
        $this->domain = (new Domain())
            ->setDomain(domain: $apiInfoDomain->asciiName)
            ->setNameservers(nameservers: $apiInfoDomain->nameservers)
            ->setRegistrationStatus(
                registrationStatus: $this->mapRegistrationStatus(normalizedStatus: $apiInfoDomain->normalizedStatus)
            )
            ->setExpiryDate(expiryDate: $apiInfoDomain->expiryDate)
            ->setTransferLock(transferLock: $apiInfoDomain->hasTransferLock)
            ->setTransferLockExpiryDate()
            ->setRestorable(restorable: $apiInfoDomain->isRestorable === true)
            ->setIdProtectionStatus(idProtectionStatus: false)
            ->setDnsManagementStatus(dnsManagementStatus: false)
            ->setEmailForwardingStatus(emailForwardingStatus: false)
            ->setIsIrtpEnabled(isIrtpEnabled: $params['tld'] == 'com')
            ->setIrtpOptOutStatus(irtpOptOutStatus: false)
            ->setIrtpTransferLock(irtpTransferLock: false)
            ->setDomainContactChangePending(domainContactChangePending: false)
            ->setPendingSuspension(willDomainSuspend: false)
            ->setDomainContactChangeExpiryDate()
            ->setRegistrantEmailAddress(registrantEmailAddress: null)
            ->setIrtpVerificationTriggerFields(
                fields: [
                    'Registrant' => [
                        'First Name',
                        'Last Name',
                        'Organization Name',
                        'Email',
                    ],
                ]
            );
    }

    /**
     * Maps the normalized status of the API, which collapses the registry-specific status codes of the raw 'status'
     * array into a fixed set of values, to a WHMCS registration status. Unexpected values are mapped to 'unknown'
     * instead of raising an error so that a status added by the API later on does not break the domain details page.
     */
    private function mapRegistrationStatus(string $normalizedStatus): string
    {
        return match ($normalizedStatus) {
            'ok' => 'active',
            'inactive' => 'inactive',
            'suspended' => 'suspended',
            'expired' => 'expired',
            'redemption' => 'redemption',
            'pendingCreate' => 'pending',
            'pendingTransfer' => 'pending transfer',
            'pendingDelete' => 'pending delete',
            default => 'unknown',
        };
    }
}
