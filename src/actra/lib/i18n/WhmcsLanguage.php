<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\i18n;

use Throwable;
use WHMCS\Authentication\CurrentUser;
use WHMCS\Config\Setting;

/**
 * The language WHMCS displays: in the admin area the language of the admin, in the client area the language chosen by
 * the visitor or the client, otherwise (e.g. cron) the default language of WHMCS.
 */
final class WhmcsLanguage
{
    public static function current(): LanguageEnum
    {
        // WHMCS defines ADMINAREA on every page of the admin area
        $isAdminArea = defined(constant_name: 'ADMINAREA');
        try {
            $currentUser = new CurrentUser();
            $userLanguage = $isAdminArea
                ? $currentUser->admin()?->language
                : $currentUser->client()?->language;
            $defaultLanguage = Setting::getValue('Language');
            $defaultLanguage = is_string(value: $defaultLanguage) ? $defaultLanguage : null;
        } catch (Throwable) {
            // Outside of a fully booted WHMCS, e.g. a failing database connection
            $userLanguage = null;
            $defaultLanguage = null;
        }

        // Set by WHMCS when a visitor changes the language of the client area
        $sessionLanguage = $_SESSION['Language'] ?? null;

        return self::resolve(
            isAdminArea: $isAdminArea,
            sessionLanguage: is_string(value: $sessionLanguage) ? $sessionLanguage : null,
            userLanguage: $userLanguage,
            defaultLanguage: $defaultLanguage,
        );
    }

    /**
     * The first language name that is set, in the order WHMCS uses them.
     */
    public static function resolve(
        bool $isAdminArea,
        ?string $sessionLanguage,
        ?string $userLanguage,
        ?string $defaultLanguage,
    ): LanguageEnum {
        $candidates = $isAdminArea
            ? [$userLanguage, $defaultLanguage]
            : [$sessionLanguage, $userLanguage, $defaultLanguage];
        foreach ($candidates as $candidate) {
            if ($candidate !== null && trim(string: $candidate) !== '') {
                return LanguageEnum::fromWhmcsName(name: $candidate);
            }
        }

        return LanguageEnum::ENGLISH;
    }
}
