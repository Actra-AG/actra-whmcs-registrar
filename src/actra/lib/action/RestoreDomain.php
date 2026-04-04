<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiRestoreDomain;

class RestoreDomain extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        try {
            new ApiRestoreDomain(
                apiClient: $this->getApiClient(),
                asciiDomainName: $this->params['domain_punycode']
            );
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $throwable->getMessage()
            ];
            return;
        }

        $this->response = [
            'success' => true,
        ];
    }
}