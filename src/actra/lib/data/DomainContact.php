<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\data;

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
