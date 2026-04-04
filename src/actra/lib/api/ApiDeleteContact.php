<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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
        $apiClient->call(
            action: 'deleteContact',
            postFields: [
                'registry' => $registry,
                'identifier' => $identifier,
            ]
        );
    }
}