<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\settings\ModuleSettings;

// See https://developers.whmcs.com/domain-registrars/config-options/
class GetConfigArray extends AbstractAction
{
    public readonly array $config;

    protected function execute(): void
    {
        $this->config = [
            'FriendlyName' => [
                'Type' => 'System',
                'Value' => ModuleSettings::FRIENDLY_DISPLAY_NAME,
            ],
            'Description' => [
                'Type' => 'System',
                'Value' => ModuleSettings::DESCRIPTION,
            ],
            ModuleSettings::CONFIG_API_URL => [
                'FriendlyName' => 'API URL',
                'Type' => 'text',
                'Size' => '25',
                'Default' => '',
                'Description' => 'Base URL of the Actra Domains REST API, e.g. https://www.example.com/api/v1',
            ],
            ModuleSettings::CONFIG_API_KEY => [
                'FriendlyName' => 'API Key',
                'Type' => 'password',
                'Size' => '25',
                'Default' => '',
                'Description' => '',
            ],
            ModuleSettings::CONFIG_CUSTOMER_NUMBER => [
                'FriendlyName' => 'Customer Number',
                'Type' => 'text',
                'Size' => '10',
                'Default' => '',
                'Description' => 'Customer number at Actra to be billed for registered and transferred domains.',
            ],
            ModuleSettings::CONFIG_LOG => [
                'FriendlyName' => 'Log',
                'Type' => 'yesno',
                'Description' => 'Tick to enable extra logging to module log file.',
            ],
        ];
    }
}
