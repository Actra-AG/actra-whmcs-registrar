<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiCreateHost
{
    /**
     * Creates a host (glue record) below one of the registrar's own domains, at the registry of that domain.
     *
     * @param list<string> $ipv4
     * @param list<string> $ipv6
     */
    public function __construct(
        ApiClient $apiClient,
        string $nameserver,
        array $ipv4,
        array $ipv6,
    ) {
        $apiClient->post(
            path: 'createHost',
            timeoutInSeconds: ApiClient::TIMEOUT_REGISTRY_CHANGE,
            body: [
                'hostname' => $nameserver,
                'ipv4' => $ipv4,
                'ipv6' => $ipv6,
            ],
        );
    }
}
