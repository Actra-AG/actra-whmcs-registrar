<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiValidateApiKey
{
    public function __construct(
        ApiClient $apiClient,
        string $username
    ) {
        $apiClient->call(
            action: 'validateApiKey',
            postFields: [
                'username' => $username,
            ]
        );
    }
}