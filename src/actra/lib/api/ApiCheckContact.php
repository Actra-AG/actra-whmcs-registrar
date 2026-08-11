<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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
        $apiClient->get(
            path: 'checkContact',
            query: [
                'registry' => $registry,
                'identifier' => $identifier,
            ]
        );
        $this->isAvailable = $apiClient->getFromResponse(key: 'isAvailable');
    }
}