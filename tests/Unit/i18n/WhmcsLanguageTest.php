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
use WHMCS\Module\Registrar\Actra\i18n\WhmcsLanguage;

final class WhmcsLanguageTest extends TestCase
{
    /**
     * @return iterable<string, array{bool, string|null, string|null, string|null, LanguageEnum}>
     */
    public static function languages(): iterable
    {
        yield 'client area: language chosen by the visitor' => [false, 'german', 'english', 'english', LanguageEnum::GERMAN];
        yield 'client area: language of the client' => [false, null, 'german', 'english', LanguageEnum::GERMAN];
        yield 'client area: empty session language' => [false, '', 'german', 'english', LanguageEnum::GERMAN];
        yield 'client area: default language' => [false, null, null, 'german', LanguageEnum::GERMAN];
        yield 'client area: unsupported language chosen' => [false, 'french', 'german', 'german', LanguageEnum::ENGLISH];
        yield 'admin area: language of the admin' => [true, 'english', 'german', 'english', LanguageEnum::GERMAN];
        yield 'admin area: session language is ignored' => [true, 'german', null, 'english', LanguageEnum::ENGLISH];
        yield 'admin area: default language' => [true, null, null, 'german', LanguageEnum::GERMAN];
        yield 'nothing known, e.g. cron' => [false, null, null, null, LanguageEnum::ENGLISH];
    }

    #[DataProvider('languages')]
    public function testResolve(
        bool $isAdminArea,
        ?string $sessionLanguage,
        ?string $userLanguage,
        ?string $defaultLanguage,
        LanguageEnum $expected,
    ): void {
        $language = WhmcsLanguage::resolve(
            isAdminArea: $isAdminArea,
            sessionLanguage: $sessionLanguage,
            userLanguage: $userLanguage,
            defaultLanguage: $defaultLanguage,
        );

        $this->assertSame($expected, $language);
    }
}
