<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\settings;

class ModuleSettings
{
    public const string FRIENDLY_DISPLAY_NAME = 'Actra Registrar Module for WHMCS';
    public const string API_VERSION = '1.1';
    public const string CONFIG_API_URL = 'APIUrl';
    public const string CONFIG_API_KEY = 'APIKey';
    public const string CONFIG_CUSTOMER_NUMBER = 'CustomerNumber';
    public const string CONFIG_LOG = 'Log';

    /**
     * The customer number of the module setting, null if it is not a positive integer.
     */
    public static function parseCustomerNumber(string $value): ?int
    {
        $value = trim(string: $value);
        if (preg_match(pattern: '/^[1-9][0-9]{0,17}$/', subject: $value) !== 1) {
            return null;
        }

        return (int) $value;
    }
}
