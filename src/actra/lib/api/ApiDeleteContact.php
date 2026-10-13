<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiDeleteContact
{
    public function __construct(
        ApiClient $apiClient,
        string $registry,
        string $identifier
    ) {
        $apiClient->delete(
            path: 'deleteContact',
            query: [
                'registry' => $registry,
                'identifier' => $identifier,
            ]
        );
    }
}
