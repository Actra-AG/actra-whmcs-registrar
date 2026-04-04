<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiUpdateHost
{
    public function __construct(
        ApiClient $apiClient,
        string $registry,
        string $nameserver,
        array $newIpv4,
        array $removeIpv4,
        array $newIpv6,
        array $removeIpv6
    ) {
        $apiClient->call(
            action: 'updateHost',
            postFields: [
                'registry' => $registry,
                'hostname' => $nameserver,
                'newIpv4' => $newIpv4,
                'removeIpv4' => $removeIpv4,
                'newIpv6' => $newIpv6,
                'removeIpv6' => $removeIpv6,
            ]
        );
    }
}