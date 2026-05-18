<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiResetAuthCode;

class GetEppCode extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        try {
            $this->response = [
                'eppcode' => (new ApiResetAuthCode(
                    apiClient: $this->getApiClient(),
                    asciiDomainName: $this->params['domain_punycode']
                ))->authCode,
            ];
            return;
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $throwable->getMessage()
            ];
        }
    }
}