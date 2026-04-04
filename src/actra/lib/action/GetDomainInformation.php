<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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
        $this->domain = (new Domain)
            ->setDomain(domain: $apiInfoDomain->asciiName)
            ->setNameservers(nameservers: $apiInfoDomain->nameservers)
            ->setRegistrationStatus(
                registrationStatus: match ($apiInfoDomain->domainStatus) {
                    'ok', 'clientTransferProhibited', 'serverTransferProhibited' => 'active',
                    'inactive' => 'inactive',
                }
            )
            ->setExpiryDate(expiryDate: $apiInfoDomain->expiryDate)
            ->setTransferLock(transferLock: $apiInfoDomain->hasTransferLock)
            ->setTransferLockExpiryDate()
            ->setRestorable(restorable: false)
            ->setIdProtectionStatus(idProtectionStatus: false)
            ->setDnsManagementStatus(dnsManagementStatus: false)
            ->setEmailForwardingStatus(emailForwardingStatus: false)
            ->setIsIrtpEnabled(isIrtpEnabled: $params['tld'] == '.com')
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
}