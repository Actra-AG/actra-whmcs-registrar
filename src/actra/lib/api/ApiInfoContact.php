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
        $apiClient->get(
            path: 'infoContact',
            query: [
                'registry' => $registry,
                'identifier' => $identifier,
            ]
        );
        $this->domainContact = new DomainContact(
            name: $apiClient->getFromResponse(key: 'name'),
            org: $apiClient->getFromResponse(key: 'org'),
            street: $apiClient->getFromResponse(key: 'street'),
            addressRow2: $apiClient->getFromResponse(key: 'addressRow2'),
            city: $apiClient->getFromResponse(key: 'city'),
            postCode: $apiClient->getFromResponse(key: 'zipCode'),
            countryCode: $apiClient->getFromResponse(key: 'countryCode'),
            email: $apiClient->getFromResponse(key: 'email'),
            phone: $apiClient->getFromResponse(key: 'phone')
        );
    }
}
