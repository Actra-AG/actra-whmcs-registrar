<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit\data;

use PHPUnit\Framework\TestCase;
use WHMCS\Module\Registrar\Actra\api\ApiRecord;
use WHMCS\Module\Registrar\Actra\data\DomainContact;

final class DomainContactTest extends TestCase
{
    public function testFromApiRecordMapsContactSchema(): void
    {
        $record = new ApiRecord(fields: [
            'identifier' => 'AC-0B6ACD03D8',
            'name' => 'Jane Example',
            'org' => 'Example Ltd',
            'street' => 'Example Street 1',
            'addressRow2' => 'PO Box',
            'city' => 'Zurich',
            'zipCode' => '8000',
            'countryCode' => 'CH',
            'email' => 'jane@example.com',
            'phone' => '+41.441234567',
        ]);

        $contact = DomainContact::fromApiRecord(record: $record);

        $this->assertSame([
            'Name' => 'Jane Example',
            'Organization' => 'Example Ltd',
            'Street 1' => 'Example Street 1',
            'Street 2' => 'PO Box',
            'City' => 'Zurich',
            'State' => '',
            'Postcode' => '8000',
            'Country' => 'CH',
            'Email' => 'jane@example.com',
            'Phone' => '+41.441234567',
        ], $contact->getWhmcsDataArray());
    }

    public function testFromApiRecordUsesEmptyStringsForMissingFields(): void
    {
        $contact = DomainContact::fromApiRecord(record: new ApiRecord(fields: ['name' => 'Jane Example']));

        $this->assertSame('Jane Example', $contact->name);
        $this->assertSame('', $contact->org);
        $this->assertSame('', $contact->addressRow2);
    }

    public function testFromWhmcsDataArrayIsTheCounterpartOfGetWhmcsDataArray(): void
    {
        $data = [
            'Name' => 'Jane Example',
            'Organization' => 'Example Ltd',
            'Street 1' => 'Example Street 1',
            'Street 2' => 'PO Box',
            'City' => 'Zurich',
            'State' => '',
            'Postcode' => '8000',
            'Country' => 'CH',
            'Email' => 'jane@example.com',
            'Phone' => '+41.441234567',
        ];

        $this->assertSame($data, DomainContact::fromWhmcsDataArray(data: $data)->getWhmcsDataArray());
    }

    public function testFromWhmcsDataArrayUsesEmptyStringsForMissingFields(): void
    {
        $contact = DomainContact::fromWhmcsDataArray(data: ['Name' => 'Jane Example', 'Street 2' => null]);

        $this->assertSame('Jane Example', $contact->name);
        $this->assertSame('', $contact->addressRow2);
        $this->assertSame('', $contact->phone);
    }
}
