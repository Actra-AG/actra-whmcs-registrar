<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\api\ApiDeleteDsRecord;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;

class DeleteDNSSEC extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $asciiDomain = $this->params['domain_punycode'];
        $domainInfo = new ApiInfoDomain(
            apiClient: $this->getApiClient(),
            asciiDomainName: $asciiDomain
        );
        $dsRecord = $domainInfo->dsRecordCollection->getByKeyTag(
            keyTag: (
                array_key_exists(
                    key: 'keytag',
                    array: $_POST
                )
                && is_scalar(value: $_POST['keytag'])
            ) ? (int)$_POST['keytag'] : null

        );
        new ApiDeleteDsRecord(
            apiClient: $this->getApiClient(),
            asciiDomainName: $asciiDomain,
            dsRecord: $dsRecord
        );
    }
}