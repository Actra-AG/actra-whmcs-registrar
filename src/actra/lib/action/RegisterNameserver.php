<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Exception;
use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiCheckHost;
use WHMCS\Module\Registrar\Actra\api\ApiCreateHost;

class RegisterNameserver extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $params = $this->params;
        $nameserver = $params['nameserver_punycode'];
        $ipAddress = $params['ipaddress'];
        $ipv4 = [];
        $ipv6 = [];
        if (str_contains(
            haystack: $ipAddress,
            needle: ':'
        )) {
            $ipv6[] = $ipAddress;
        } else {
            $ipv4[] = $ipAddress;
        }
        try {
            $apiClient = $this->getApiClient();
            if (!(new ApiCheckHost(
                apiClient: $apiClient,
                registry: 'SWITCH',
                nameserver: $nameserver
            ))->isAvailable) {
                throw new Exception(message: $nameserver . ': Nameserver is not available');
            }
            new ApiCreateHost(
                apiClient: $apiClient,
                registry: 'SWITCH',
                nameserver: $nameserver,
                ipv4: $ipv4,
                ipv6: $ipv6
            );
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $throwable->getMessage(),
            ];
            return;
        }

        $this->response = [
            'success' => true,
        ];
    }
}
