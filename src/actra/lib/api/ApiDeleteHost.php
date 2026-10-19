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
        string $nameserver
    ) {
        $apiClient->delete(
            path: 'deleteHost',
            timeoutInSeconds: ApiClient::TIMEOUT_REGISTRY_CHANGE,
            query: [
                'hostname' => $nameserver,
            ]
        );
    }
}
