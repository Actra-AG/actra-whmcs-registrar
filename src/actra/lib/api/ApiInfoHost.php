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
        string $hostname,
    ) {
        $response = $apiClient->get(
            path: 'infoHost',
            timeoutInSeconds: ApiClient::TIMEOUT_REGISTRY_QUERY,
            query: [
                'name' => $hostname,
            ],
        );
        $createdDate = $response->getString(key: 'createdDate');
        $this->host = new Host(
            name: $response->getString(key: 'name'),
            status: $response->getStringList(key: 'status'),
            ipv4List: $response->getStringList(key: 'ipv4List'),
            ipv6List: $response->getStringList(key: 'ipv6List'),
            createdDate: $createdDate === '' ? null : new DateTimeImmutable(datetime: $createdDate),
        );
    }
}
