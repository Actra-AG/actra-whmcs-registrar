<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use DateTimeImmutable;
use WHMCS\Module\Registrar\Actra\data\Host;

readonly class ApiInfoHost
{
    public Host $host;

    public function __construct(
        ApiClient $apiClient,
        string $registry,
        string $hostname
    ) {
        $apiClient->get(
            path: 'infoHost',
            query: [
                'registry' => $registry,
                'name' => $hostname,
            ]
        );
        $this->host = new Host(
            name: $apiClient->getFromResponse(key: 'name'),
            roid: $apiClient->getFromResponse(key: 'roid'),
            status: $apiClient->getFromResponse(key: 'status'),
            ipv4List: $apiClient->getFromResponse(key: 'ipv4List'),
            ipv6List: $apiClient->getFromResponse(key: 'ipv6List'),
            createdDate: new DateTimeImmutable(datetime: $apiClient->getFromResponse(key: 'createdDate')),
        );
    }
}
