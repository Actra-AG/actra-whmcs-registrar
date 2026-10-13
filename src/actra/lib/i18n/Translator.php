<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\i18n;

use LogicException;

/**
 * Texts of the module from `lang/<language>.php`. A text missing in a language falls back to English.
 */
final class Translator
{
    /** @var array<string, array<string, string>> texts by language, each file is read once per request */
    private static array $textsByLanguage = [];

    private function __construct(
        private readonly LanguageEnum $language,
    ) {}

    public static function forLanguage(LanguageEnum $language): self
    {
        return new self(language: $language);
    }

    /**
     * The translator for the language of the current WHMCS area and user.
     */
    public static function current(): self
    {
        return new self(language: WhmcsLanguage::current());
    }

    /**
     * @param array<string, string> $replacements values for the {name} placeholders of the text
     */
    public function get(string $key, array $replacements = []): string
    {
        $text = self::getTexts(language: $this->language)[$key]
            ?? self::getTexts(language: LanguageEnum::ENGLISH)[$key]
            ?? throw new LogicException(message: 'Missing text: ' . $key);
        $placeholders = [];
        foreach ($replacements as $name => $value) {
            $placeholders['{' . $name . '}'] = $value;
        }

        return strtr($text, $placeholders);
    }

    /**
     * All texts of the language, completed with the English texts missing in it.
     *
     * @return array<string, string>
     */
    public function all(): array
    {
        return [
            ...self::getTexts(language: LanguageEnum::ENGLISH),
            ...self::getTexts(language: $this->language),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function getTexts(LanguageEnum $language): array
    {
        if (!array_key_exists(key: $language->value, array: self::$textsByLanguage)) {
            $texts = require dirname(path: __DIR__, levels: 2) . '/lang/' . $language->value . '.php';
            if (!is_array(value: $texts)) {
                throw new LogicException(message: 'Invalid language file: ' . $language->value);
            }
            $validTexts = [];
            foreach ($texts as $key => $text) {
                if (is_string(value: $key) && is_string(value: $text)) {
                    $validTexts[$key] = $text;
                }
            }
            self::$textsByLanguage[$language->value] = $validTexts;
        }

        return self::$textsByLanguage[$language->value];
    }
}
