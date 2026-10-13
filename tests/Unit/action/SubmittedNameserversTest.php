<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit\action;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WHMCS\Module\Registrar\Actra\action\SubmittedNameservers;

final class SubmittedNameserversTest extends TestCase
{
    /**
     * @return iterable<string, array{array<string, mixed>, list<string>}>
     */
    public static function params(): iterable
    {
        yield 'two nameservers' => [
            ['ns1_punycode' => 'ns1.example.ch', 'ns2_punycode' => 'ns2.example.ch'],
            ['ns1.example.ch', 'ns2.example.ch'],
        ];
        yield 'empty and whitespace entries' => [
            ['ns1_punycode' => 'ns1.example.ch', 'ns2_punycode' => '', 'ns3_punycode' => '  ', 'ns4_punycode' => 'ns4.example.ch'],
            ['ns1.example.ch', 'ns4.example.ch'],
        ];
        yield 'uppercase and surrounding whitespace' => [
            ['ns1_punycode' => ' NS1.Example.CH '],
            ['ns1.example.ch'],
        ];
        yield 'duplicates' => [
            ['ns1_punycode' => 'ns1.example.ch', 'ns2_punycode' => 'NS1.example.ch'],
            ['ns1.example.ch'],
        ];
        yield 'missing entry in between' => [
            ['ns1_punycode' => 'ns1.example.ch', 'ns3_punycode' => 'ns3.example.ch'],
            ['ns1.example.ch', 'ns3.example.ch'],
        ];
        yield 'more than five' => [
            ['ns5_punycode' => 'ns5.example.ch', 'ns6_punycode' => 'ns6.example.ch'],
            ['ns5.example.ch'],
        ];
        yield 'values of another type' => [
            ['ns1_punycode' => null, 'ns2_punycode' => ['ns2.example.ch']],
            [],
        ];
        yield 'none' => [
            [],
            [],
        ];
    }

    /**
     * @param array<string, mixed> $params
     * @param list<string> $expected
     */
    #[DataProvider('params')]
    public function testFromParamsReturnsSubmittedNameservers(array $params, array $expected): void
    {
        $this->assertSame($expected, SubmittedNameservers::fromParams(params: $params));
    }

    public function testEqualIgnoresOrder(): void
    {
        $this->assertTrue(SubmittedNameservers::equal(
            first: ['ns1.example.ch', 'ns2.example.ch'],
            second: ['ns2.example.ch', 'ns1.example.ch'],
        ));
    }

    public function testEqualDetectsChangedNameserver(): void
    {
        $this->assertFalse(SubmittedNameservers::equal(
            first: ['ns1.example.ch', 'ns2.example.ch'],
            second: ['ns1.example.ch', 'ns3.example.ch'],
        ));
    }

    public function testEqualDetectsRemovedNameserver(): void
    {
        $this->assertFalse(SubmittedNameservers::equal(
            first: ['ns1.example.ch', 'ns2.example.ch', 'ns3.example.ch'],
            second: ['ns1.example.ch', 'ns2.example.ch'],
        ));
    }
}
