<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit\settings;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WHMCS\Module\Registrar\Actra\settings\ModuleSettings;

final class ModuleSettingsTest extends TestCase
{
    /**
     * @return iterable<string, array{string, int|null}>
     */
    public static function customerNumbers(): iterable
    {
        yield 'valid' => ['10042', 10042];
        yield 'surrounding whitespace' => [' 10042 ', 10042];
        yield 'empty' => ['', null];
        yield 'whitespace only' => ['   ', null];
        yield 'zero' => ['0', null];
        yield 'leading zero' => ['010042', null];
        yield 'negative' => ['-10042', null];
        yield 'decimal' => ['10042.5', null];
        yield 'letters' => ['C10042', null];
        yield 'integer overflow' => ['99999999999999999999', null];
    }

    #[DataProvider('customerNumbers')]
    public function testParseCustomerNumber(string $value, ?int $expected): void
    {
        $this->assertSame($expected, ModuleSettings::parseCustomerNumber(value: $value));
    }
}
