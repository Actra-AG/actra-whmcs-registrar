<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit\api;

use PHPUnit\Framework\TestCase;
use WHMCS\Module\Registrar\Actra\api\ApiRecord;

final class ApiRecordTest extends TestCase
{
    public function testGettersReturnValuesOfMatchingType(): void
    {
        $record = new ApiRecord(fields: [
            'name' => 'example.ch',
            'locked' => true,
            'keyTag' => 12345,
            'nameserver' => ['ns1.example.ch', 'ns2.example.ch'],
        ]);

        $this->assertSame('example.ch', $record->getString(key: 'name'));
        $this->assertTrue($record->getBool(key: 'locked'));
        $this->assertSame(12345, $record->getInt(key: 'keyTag'));
        $this->assertSame(['ns1.example.ch', 'ns2.example.ch'], $record->getStringList(key: 'nameserver'));
    }

    public function testGettersReturnEmptyValuesForMissingFields(): void
    {
        $record = new ApiRecord(fields: []);

        $this->assertSame('', $record->getString(key: 'name'));
        $this->assertNull($record->getNullableString(key: 'name'));
        $this->assertFalse($record->getBool(key: 'locked'));
        $this->assertNull($record->getNullableBool(key: 'restorable'));
        $this->assertSame(0, $record->getInt(key: 'keyTag'));
        $this->assertSame([], $record->getStringList(key: 'nameserver'));
        $this->assertNull($record->getRecord(key: 'data'));
        $this->assertSame([], $record->getRecords(key: 'contacts'));
    }

    public function testGettersReturnEmptyValuesForFieldsOfAnotherType(): void
    {
        $record = new ApiRecord(fields: [
            'name' => null,
            'locked' => 'true',
            'keyTag' => '12345',
            'nameserver' => 'ns1.example.ch',
            'contacts' => 'none',
        ]);

        $this->assertSame('', $record->getString(key: 'name'));
        $this->assertFalse($record->getBool(key: 'locked'));
        $this->assertSame(0, $record->getInt(key: 'keyTag'));
        $this->assertSame([], $record->getStringList(key: 'nameserver'));
        $this->assertSame([], $record->getRecords(key: 'contacts'));
    }

    public function testGetStringConvertsIntegers(): void
    {
        $record = new ApiRecord(fields: ['code' => 2302]);

        $this->assertSame('2302', $record->getString(key: 'code'));
    }

    public function testGetStringListSkipsOtherTypes(): void
    {
        $record = new ApiRecord(fields: ['status' => ['ok', 1, null, 'inactive']]);

        $this->assertSame(['ok', 'inactive'], $record->getStringList(key: 'status'));
    }

    public function testGetRecordsKeepsKeysOfObjects(): void
    {
        $record = new ApiRecord(fields: [
            'contacts' => [
                'registrant' => ['name' => 'Jane Example'],
                'tech' => ['name' => 'John Example'],
                'invalid' => 'not an object',
            ],
        ]);

        $records = $record->getRecords(key: 'contacts');

        $this->assertSame(['registrant', 'tech'], array_keys(array: $records));
        $this->assertSame('John Example', ($records['tech'] ?? null)?->getString(key: 'name'));
    }
}
