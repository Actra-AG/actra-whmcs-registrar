<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;

class GetDnsSecRecords extends AbstractAction
{
    public readonly array $response;

    public function __construct(
        array $params,
        private readonly bool $successful
    ) {
        parent::__construct(params: $params);
    }

    protected function execute(): void
    {
        try {
            if (array_key_exists(
                key: 'add',
                array: $_POST
            )) {
                new AddDNSSEC(params: $this->params);
            }
            if (array_key_exists(
                key: 'delete',
                array: $_POST
            )) {
                new DeleteDNSSEC(params: $this->params);
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
                    'successful' => $this->successful,
                    'records' => $domainInfo->dsRecordCollection->getDataArray(),
                    'domainid' => $this->params['domainid'],
                ]
            ];
            return;
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $throwable->getMessage()
            ];
        }
    }
}