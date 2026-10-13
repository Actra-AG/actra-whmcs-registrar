<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiCheckHost
{
    public bool $isAvailable;

    public function __construct(
        ApiClient $apiClient,
        string $registry,
        string $nameserver
    ) {
        $response = $apiClient->get(
            path: 'checkHost',
            query: [
                'registry' => $registry,
                'hostname' => $nameserver,
            ]
        );
        $this->isAvailable = $response->getBool(key: 'isAvailable');
    }
}
