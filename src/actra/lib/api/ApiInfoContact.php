<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use WHMCS\Module\Registrar\Actra\data\DomainContact;

readonly class ApiInfoContact
{
    public DomainContact $domainContact;

    public function __construct(
        ApiClient $apiClient,
        string $registry,
        string $identifier
    ) {
        $response = $apiClient->get(
            path: 'infoContact',
            query: [
                'registry' => $registry,
                'identifier' => $identifier,
            ]
        );
        $this->domainContact = DomainContact::fromApiRecord(record: $response);
    }
}
