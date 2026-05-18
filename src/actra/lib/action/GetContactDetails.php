<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;

class GetContactDetails extends AbstractAction
{
    public readonly array $contacts;

    protected function execute(): void
    {
        $apiDomainInfo = new ApiInfoDomain(
            apiClient: $this->getApiClient(),
            asciiDomainName: $this->params['domain_punycode']
        );
        $contacts = [];
        if ($apiDomainInfo->registrant !== null) {
            $contacts['Registrant'] = $apiDomainInfo->registrant->getWhmcsDataArray();
        }
        if ($apiDomainInfo->tech !== null) {
            $contacts['Technical'] = $apiDomainInfo->tech->getWhmcsDataArray();
        }
        if ($apiDomainInfo->billing !== null) {
            $contacts['Billing'] = $apiDomainInfo->billing->getWhmcsDataArray();
        }
        if ($apiDomainInfo->admin !== null) {
            $contacts['Admin'] = $apiDomainInfo->admin->getWhmcsDataArray();
        }
        $this->contacts = $contacts;
    }
}