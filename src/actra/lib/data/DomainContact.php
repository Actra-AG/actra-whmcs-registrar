<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\data;

use WHMCS\Module\Registrar\Actra\api\ApiRecord;

readonly class DomainContact
{
    public function __construct(
        public string $name,
        public string $org,
        public string $street,
        public string $addressRow2,
        public string $city,
        public string $postCode,
        public string $countryCode,
        public string $email,
        public string $phone,
    ) {}

    /**
     * Contact of an API response (`Contact` schema).
     */
    public static function fromApiRecord(ApiRecord $record): self
    {
        return new self(
            name: $record->getString(key: 'name'),
            org: $record->getString(key: 'org'),
            street: $record->getString(key: 'street'),
            addressRow2: $record->getString(key: 'addressRow2'),
            city: $record->getString(key: 'city'),
            postCode: $record->getString(key: 'zipCode'),
            countryCode: $record->getString(key: 'countryCode'),
            email: $record->getString(key: 'email'),
            phone: $record->getString(key: 'phone'),
        );
    }

    /**
     * Contact of the WHMCS contact details (`contactdetails` of SaveContactDetails, the keys of getWhmcsDataArray()).
     *
     * @param array<array-key, mixed> $data
     */
    public static function fromWhmcsDataArray(array $data): self
    {
        $value = static fn(string $key): string => is_scalar(value: $data[$key] ?? null) ? (string) $data[$key] : '';

        return new self(
            name: $value('Name'),
            org: $value('Organization'),
            street: $value('Street 1'),
            addressRow2: $value('Street 2'),
            city: $value('City'),
            postCode: $value('Postcode'),
            countryCode: $value('Country'),
            email: $value('Email'),
            phone: $value('Phone'),
        );
    }

    public function getWhmcsDataArray(): array
    {
        return [
            'Name' => $this->name,
            'Organization' => $this->org,
            'Street 1' => $this->street,
            'Street 2' => $this->addressRow2,
            'City' => $this->city,
            'State' => '',
            'Postcode' => $this->postCode,
            'Country' => $this->countryCode,
            'Email' => $this->email,
            'Phone' => $this->phone,
        ];
    }

    public function equals(DomainContact $other): bool
    {
        return (
            $this->name === $other->name
            && $this->org === $other->org
            && $this->street === $other->street
            && $this->addressRow2 === $other->addressRow2
            && $this->city === $other->city
            && $this->postCode === $other->postCode
            && $this->countryCode === $other->countryCode
            && $this->email === $other->email
            && $this->phone === $other->phone
        );
    }
}
