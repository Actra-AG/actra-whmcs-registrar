<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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
        $apiClient->call(
            action: 'checkHost',
            postFields: [
                'registry' => $registry,
                'hostname' => $nameserver,
            ]
        );
        $this->isAvailable = $apiClient->getFromResponse(key: 'isAvailable');
    }
}