<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use WHMCS\Module\Registrar\Actra\data\DomainContact;

/**
 * Adds a contact to the contact list of the registrar. Contacts are independent of the registries: the API creates the
 * contact at the registry when a domain is registered or changed with it and reuses identical contacts.
 */
readonly class ApiCreateContact
{
    /** Public contact identifier ("C-" and 12 characters) for createDomain and updateDomain */
    public string $identifier;

    public function __construct(
        ApiClient $apiClient,
        DomainContact $domainContact
    ) {
        $response = $apiClient->post(
            path: 'createContact',
            body: [
                'name' => $domainContact->name,
                'organization' => $domainContact->org,
                'street' => $domainContact->street,
                'addressRow2' => $domainContact->addressRow2,
                'city' => $domainContact->city,
                'zip' => $domainContact->postCode,
                'countryCode' => $domainContact->countryCode,
                'phone' => $domainContact->phone,
                'email' => $domainContact->email,
            ]
        );
        $this->identifier = $response->getString(key: 'identifier');
    }
}
