<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;

class GetNameservers extends AbstractAction
{
    public readonly array $nameservers;

    protected function execute(): void
    {
        $params = $this->params;
        try {
            $this->nameservers = (new ApiInfoDomain(
                apiClient: $this->getApiClient(),
                asciiDomainName: $params['domain_punycode']
            ))->nameservers;
        } catch (Throwable $throwable) {
            $this->nameservers = [
                'error' => $throwable->getMessage(),
            ];
        }
    }
}
