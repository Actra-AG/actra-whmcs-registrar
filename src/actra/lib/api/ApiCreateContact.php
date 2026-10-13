<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use WHMCS\Module\Registrar\Actra\data\DomainContact;

readonly class ApiCreateContact
{
    public string $identifier;

    public function __construct(
        ApiClient $apiClient,
        string $registry,
        DomainContact $domainContact
    ) {
        $response = $apiClient->post(
            path: 'createContact',
            body: [
                'registry' => $registry,
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
