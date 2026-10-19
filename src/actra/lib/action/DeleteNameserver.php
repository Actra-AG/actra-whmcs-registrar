<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiDeleteHost;

class DeleteNameserver extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $params = $this->params;
        $nameserver = $params['nameserver'];
        try {
            new ApiDeleteHost(
                apiClient: $this->getApiClient(),
                nameserver: $nameserver
            );
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $this->getErrorMessage(throwable: $throwable),
            ];
            return;
        }

        $this->response = [
            'success' => true,
        ];
    }
}
