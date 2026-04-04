<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\settings;

class ModuleSettings
{
    public const string FRIENDLY_DISPLAY_NAME = 'Actra Registrar Module for WHMCS';
    public const string DESCRIPTION = 'This module can be used to manage domains with Actra as registrar.';
    public const string API_VERSION = '1.1';
    public const string CONFIG_API_URL = 'APIUrl';
    public const string CONFIG_API_USERNAME = 'APIUsername';
    public const string CONFIG_API_KEY = 'APIKey';
    public const string CONFIG_LOG = 'Log';
}