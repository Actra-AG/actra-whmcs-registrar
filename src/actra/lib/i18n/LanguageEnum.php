<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\i18n;

/**
 * Languages of the module, by the name of the WHMCS language (`lang/<name>.php` of WHMCS).
 */
enum LanguageEnum: string
{
    case ENGLISH = 'english';
    case GERMAN = 'german';

    /**
     * English for every language the module does not support.
     */
    public static function fromWhmcsName(string $name): self
    {
        return self::tryFrom(value: strtolower(string: trim(string: $name))) ?? self::ENGLISH;
    }
}
