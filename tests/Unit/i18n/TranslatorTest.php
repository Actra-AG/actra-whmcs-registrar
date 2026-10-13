<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit\i18n;

use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WHMCS\Module\Registrar\Actra\i18n\LanguageEnum;
use WHMCS\Module\Registrar\Actra\i18n\Translator;

final class TranslatorTest extends TestCase
{
    public function testGetReturnsTextOfLanguage(): void
    {
        $this->assertSame('Löschen', Translator::forLanguage(language: LanguageEnum::GERMAN)->get(key: 'dnssec.delete'));
        $this->assertSame('Delete', Translator::forLanguage(language: LanguageEnum::ENGLISH)->get(key: 'dnssec.delete'));
    }

    public function testGetReplacesPlaceholders(): void
    {
        $text = Translator::forLanguage(language: LanguageEnum::GERMAN)->get(
            key: 'error.domainNotAvailable',
            replacements: ['domain' => 'example.ch'],
        );

        $this->assertSame('example.ch: Die Domain ist nicht verfügbar', $text);
    }

    public function testGetThrowsForUnknownKey(): void
    {
        $this->expectException(LogicException::class);

        Translator::forLanguage(language: LanguageEnum::GERMAN)->get(key: 'unknown.key');
    }

    /**
     * @return iterable<string, array{LanguageEnum}>
     */
    public static function languages(): iterable
    {
        foreach (LanguageEnum::cases() as $language) {
            yield $language->value => [$language];
        }
    }

    /**
     * Every language has exactly the texts of English, with the same placeholders, so no text falls back to English.
     */
    #[DataProvider('languages')]
    public function testLanguageFileHasAllTextsOfEnglish(LanguageEnum $language): void
    {
        $english = $this->loadLanguageFile(language: LanguageEnum::ENGLISH);
        $texts = $this->loadLanguageFile(language: $language);

        $this->assertSame(array_keys(array: $english), array_keys(array: $texts));
        foreach ($english as $key => $englishText) {
            $this->assertSame(
                $this->getPlaceholders(text: $englishText),
                $this->getPlaceholders(text: $texts[$key] ?? ''),
                'Placeholders of ' . $key,
            );
            $this->assertNotSame('', trim(string: $texts[$key] ?? ''), 'Text of ' . $key);
        }
    }

    public function testAllReturnsTextsOfLanguage(): void
    {
        $texts = Translator::forLanguage(language: LanguageEnum::GERMAN)->all();

        $this->assertSame('DNSSEC-Verwaltung', $texts['dnssec.title'] ?? null);
    }

    /**
     * @return array<string, string>
     */
    private function loadLanguageFile(LanguageEnum $language): array
    {
        $texts = require dirname(path: __DIR__, levels: 3) . '/src/actra/lang/' . $language->value . '.php';
        $this->assertIsArray($texts);
        $validTexts = [];
        foreach ($texts as $key => $text) {
            $this->assertIsString($key);
            $this->assertIsString($text);
            $validTexts[$key] = $text;
        }

        return $validTexts;
    }

    /**
     * @return list<string>
     */
    private function getPlaceholders(string $text): array
    {
        preg_match_all(pattern: '/\{[a-zA-Z]+\}/', subject: $text, matches: $matches);
        $placeholders = $matches[0];
        sort(array: $placeholders);

        return $placeholders;
    }
}
