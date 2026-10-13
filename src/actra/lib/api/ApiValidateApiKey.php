<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiValidateApiKey
{
    public function __construct(ApiClient $apiClient)
    {
        $apiClient->post(
            path: 'validateApiKey'
        );
    }
}
