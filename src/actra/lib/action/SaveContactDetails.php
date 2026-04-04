<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiCreateContact;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;
use WHMCS\Module\Registrar\Actra\api\ApiUpdateDomain;
use WHMCS\Module\Registrar\Actra\data\DomainContact;

class SaveContactDetails extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $params = $this->params;
        try {
            $asciiDomainName = (string)$params['domain_punycode'];
            $apiClient = $this->getApiClient();
            $apiDomainInfo = new ApiInfoDomain(
                apiClient: $apiClient,
                asciiDomainName: $asciiDomainName
            );
            $newContactDetails = $params['contactdetails'];
            $whmcsNewRegistrant = new DomainContact(
                name: $newContactDetails['Registrant']['Name'],
                org: $newContactDetails['Registrant']['Organization'],
                street: $newContactDetails['Registrant']['Street 1'],
                addressRow2: $newContactDetails['Registrant']['Street 2'],
                city: $newContactDetails['Registrant']['City'],
                postCode: $newContactDetails['Registrant']['Postcode'],
                countryCode: $newContactDetails['Registrant']['Country'],
                email: $newContactDetails['Registrant']['Email'],
                phone: $newContactDetails['Registrant']['Phone'],
            );
            $newRegistrant = null;
            $newTechContact = null;
            $removeTechContact = null;
            if (!$apiDomainInfo->registrant->equals(other: $whmcsNewRegistrant)) {
                $newRegistrant = (new ApiCreateContact(
                    apiClient: $apiClient,
                    registry: 'SWITCH',
                    domainContact: $whmcsNewRegistrant
                ))->identifier;
            }
            $whmcsNewTechnicalContact = new DomainContact(
                name: $newContactDetails['Technical']['Name'],
                org: $newContactDetails['Technical']['Organization'],
                street: $newContactDetails['Technical']['Street 1'],
                addressRow2: $newContactDetails['Technical']['Street 2'],
                city: $newContactDetails['Technical']['City'],
                postCode: $newContactDetails['Technical']['Postcode'],
                countryCode: $newContactDetails['Technical']['Country'],
                email: $newContactDetails['Technical']['Email'],
                phone: $newContactDetails['Technical']['Phone'],
            );
            if (
                $apiDomainInfo->tech === null
                || !$apiDomainInfo->tech->equals(other: $whmcsNewTechnicalContact)
            ) {
                $newTechContact = new ApiCreateContact(
                    apiClient: $apiClient,
                    registry: 'SWITCH',
                    domainContact: $whmcsNewTechnicalContact
                );
                if ($apiDomainInfo->tech !== null) {
                    $removeTechContact = $apiDomainInfo->techIdentifier;
                }
            }
            new ApiUpdateDomain(
                apiClient: $apiClient,
                asciiDomainName: $asciiDomainName,
                newTechContact: $newTechContact?->identifier,
                removeTechContact: $removeTechContact,
                newRegistrant: $newRegistrant,
            );
            $this->response = [
                'success' => true,
            ];
            return;
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $throwable->getMessage()
            ];
        }
    }
}