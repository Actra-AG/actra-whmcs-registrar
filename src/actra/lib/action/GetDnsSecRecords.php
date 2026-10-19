<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Override;
use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;
use WHMCS\Module\Registrar\Actra\i18n\Translator;

class GetDnsSecRecords extends AbstractAction
{
    public readonly array $response;

    #[Override]
    protected function execute(): void
    {
        try {
            $isSaved = false;
            if (array_key_exists(
                key: 'add',
                array: $_POST,
            )) {
                new AddDNSSEC(params: $this->params);
                $isSaved = true;
            }
            if (array_key_exists(
                key: 'delete',
                array: $_POST,
            )) {
                new DeleteDNSSEC(params: $this->params);
                $isSaved = true;
            }
            $domainInfo = new ApiInfoDomain(
                apiClient: $this->getApiClient(),
                asciiDomainName: $this->params['domain_punycode']
            );
            $this->response = [
                'status' => 'success',
                'records' => $domainInfo->dsRecordCollection->getDataArray(),
                'templatefile' => 'dnssec',
                'vars' => [
                    'successful' => $isSaved,
                    'records' => $domainInfo->dsRecordCollection->getDataArray(),
                    'domainid' => $this->params['domainid'],
                    // Not $LANG, which holds the texts of WHMCS
                    'actraLang' => Translator::current()->all(),
                ],
            ];
            return;
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $this->getErrorMessage(throwable: $throwable),
            ];
        }
    }
}
