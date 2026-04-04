<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiCreateHost
{
    public function __construct(
        ApiClient $apiClient,
        string $registry,
        string $nameserver,
        array $ipv4,
        array $ipv6
    ) {
        $apiClient->call(
            action: 'createHost',
            postFields: [
                'registry' => $registry,
                'hostname' => $nameserver,
                'ipv4' => $ipv4,
                'ipv6' => $ipv6,
            ]
        );
    }
}