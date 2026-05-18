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
                registry: 'SWITCH',
                nameserver: $nameserver
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