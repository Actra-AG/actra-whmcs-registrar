<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiUpdateHost;

class ModifyNameserver extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $params = $this->params;
        $nameserver = $params['nameserver'];
        $currentIpAddress = $params['currentipaddress'];
        $newIpAddress = $params['newipaddress'];
        $newIpv4 = [];
        $newIpv6 = [];
        if (str_contains(
            haystack: $newIpAddress,
            needle: ':'
        )) {
            $newIpv6[] = $newIpAddress;
        } else {
            $newIpv4[] = $newIpAddress;
        }
        $removeIpv4 = [];
        $removeIpv6 = [];
        if (str_contains(
            haystack: $currentIpAddress,
            needle: ':'
        )) {
            $removeIpv6[] = $currentIpAddress;
        } else {
            $removeIpv4[] = $currentIpAddress;
        }
        try {
            new ApiUpdateHost(
                apiClient: $this->getApiClient(),
                nameserver: $nameserver,
                newIpv4: $newIpv4,
                removeIpv4: $removeIpv4,
                newIpv6: $newIpv6,
                removeIpv6: $removeIpv6,
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
