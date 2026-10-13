<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiDeleteHost
{
    public function __construct(
        ApiClient $apiClient,
        string $registry,
        string $nameserver
    ) {
        $apiClient->delete(
            path: 'deleteHost',
            query: [
                'registry' => $registry,
                'hostname' => $nameserver,
            ]
        );
    }
}
