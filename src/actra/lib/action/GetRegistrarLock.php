<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;

class GetRegistrarLock extends AbstractAction
{
    public readonly array|string $result;

    protected function execute(): void
    {
        try {
            $this->result = (new ApiInfoDomain(
                apiClient: $this->getApiClient(),
                asciiDomainName: $this->params['domain_punycode']
            ))->hasTransferLock ? 'locked' : 'unlocked';
        } catch (Throwable $throwable) {
            $this->result = [
                'error' => $this->getErrorMessage(throwable: $throwable),
            ];
        }
    }
}
