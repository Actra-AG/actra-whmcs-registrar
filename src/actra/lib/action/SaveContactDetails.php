<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Override;
use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiClient;
use WHMCS\Module\Registrar\Actra\api\ApiCreateContact;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;
use WHMCS\Module\Registrar\Actra\api\ApiUpdateDomain;
use WHMCS\Module\Registrar\Actra\data\DomainContact;

/**
 * Saves the contacts WHMCS shows (the roles of GetContactDetails). A changed contact is created as a new contact and
 * replaces the current one; the API reuses identical contacts.
 */
class SaveContactDetails extends AbstractAction
{
    public readonly array $response;

    #[Override]
    protected function execute(): void
    {
        $params = $this->params;
        try {
            $asciiDomainName = (string) $params['domain_punycode'];
            $apiClient = $this->getApiClient();
            $apiDomainInfo = new ApiInfoDomain(
                apiClient: $apiClient,
                asciiDomainName: $asciiDomainName
            );
            $contactDetails = is_array(value: $params['contactdetails'] ?? null) ? $params['contactdetails'] : [];
            new ApiUpdateDomain(
                apiClient: $apiClient,
                asciiDomainName: $asciiDomainName,
                registrant: $this->createChangedContact(
                    apiClient: $apiClient,
                    whmcsData: $contactDetails['Registrant'] ?? null,
                    current: $apiDomainInfo->registrant,
                    addIfMissing: true,
                ),
                // Only roles the domain has: a registry without admin or billing contacts (e.g. Switch) rejects them
                adminContact: $this->createChangedContact(
                    apiClient: $apiClient,
                    whmcsData: $contactDetails['Admin'] ?? null,
                    current: $apiDomainInfo->admin,
                    addIfMissing: false,
                ),
                techContact: $this->createChangedContact(
                    apiClient: $apiClient,
                    whmcsData: $contactDetails['Technical'] ?? null,
                    current: $apiDomainInfo->tech,
                    addIfMissing: true,
                ),
                billingContact: $this->createChangedContact(
                    apiClient: $apiClient,
                    whmcsData: $contactDetails['Billing'] ?? null,
                    current: $apiDomainInfo->billing,
                    addIfMissing: false,
                ),
            );
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $this->getErrorMessage(throwable: $throwable),
            ];

            return;
        }
        $this->response = [
            'success' => true,
        ];
    }

    /**
     * Public contact ID of the new contact, or null to leave the role untouched (not sent by WHMCS, unchanged, or
     * missing on the domain and not to be added).
     */
    private function createChangedContact(
        ApiClient $apiClient,
        mixed $whmcsData,
        ?DomainContact $current,
        bool $addIfMissing,
    ): ?string {
        if (!is_array(value: $whmcsData)) {
            return null;
        }
        if ($current === null && !$addIfMissing) {
            return null;
        }
        $contact = DomainContact::fromWhmcsDataArray(data: $whmcsData);
        if ($current !== null && $current->equals(other: $contact)) {
            return null;
        }

        return (new ApiCreateContact(
            apiClient: $apiClient,
            domainContact: $contact,
        ))->identifier;
    }
}
