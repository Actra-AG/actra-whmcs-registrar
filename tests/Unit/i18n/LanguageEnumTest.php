<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit\i18n;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WHMCS\Module\Registrar\Actra\i18n\LanguageEnum;

final class LanguageEnumTest extends TestCase
{
    /**
     * @return iterable<string, array{string, LanguageEnum}>
     */
    public static function whmcsNames(): iterable
    {
        yield 'english' => ['english', LanguageEnum::ENGLISH];
        yield 'german' => ['german', LanguageEnum::GERMAN];
        yield 'uppercase and whitespace' => [' German ', LanguageEnum::GERMAN];
        yield 'unsupported language' => ['french', LanguageEnum::ENGLISH];
        yield 'empty' => ['', LanguageEnum::ENGLISH];
    }

    #[DataProvider('whmcsNames')]
    public function testFromWhmcsName(string $name, LanguageEnum $expected): void
    {
        $this->assertSame($expected, LanguageEnum::fromWhmcsName(name: $name));
    }
}
