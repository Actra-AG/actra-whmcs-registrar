<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;

// See https://developers.whmcs.com/domain-registrars/domain-syncing/
class Sync extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $params = $this->params;
        $apiInfoDomain = new ApiInfoDomain(
            apiClient: $this->getApiClient(),
            asciiDomainName: $params['domain_punycode']
        );
        $this->response = [
            'expirydate' => $apiInfoDomain->expiryDate->format(format: 'Y-m-d'),
            'active' => true,
        ];
    }
}