<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiCheckContact
{
    public bool $isAvailable;

    public function __construct(
        ApiClient $apiClient,
        string $registry,
        string $identifier
    ) {
        $response = $apiClient->get(
            path: 'checkContact',
            query: [
                'registry' => $registry,
                'identifier' => $identifier,
            ]
        );
        $this->isAvailable = $response->getBool(key: 'isAvailable');
    }
}
