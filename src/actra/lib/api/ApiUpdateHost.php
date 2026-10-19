<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiUpdateHost
{
    public function __construct(
        ApiClient $apiClient,
        string $nameserver,
        array $newIpv4,
        array $removeIpv4,
        array $newIpv6,
        array $removeIpv6
    ) {
        $apiClient->patch(
            path: 'updateHost',
            timeoutInSeconds: ApiClient::TIMEOUT_REGISTRY_CHANGE,
            query: [
                'hostname' => $nameserver,
            ],
            body: [
                'newIpv4' => $newIpv4,
                'removeIpv4' => $removeIpv4,
                'newIpv6' => $newIpv6,
                'removeIpv6' => $removeIpv6,
            ]
        );
    }
}
