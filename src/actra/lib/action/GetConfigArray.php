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
                'Value' => $this->translate(key: 'module.description'),
            ],
            ModuleSettings::CONFIG_API_URL => [
                'FriendlyName' => $this->translate(key: 'setting.apiUrl.label'),
                'Type' => 'text',
                'Size' => '25',
                'Default' => '',
                'Description' => $this->translate(key: 'setting.apiUrl.description'),
            ],
            ModuleSettings::CONFIG_API_KEY => [
                'FriendlyName' => $this->translate(key: 'setting.apiKey.label'),
                'Type' => 'password',
                'Size' => '25',
                'Default' => '',
                'Description' => '',
            ],
            ModuleSettings::CONFIG_CUSTOMER_NUMBER => [
                'FriendlyName' => $this->translate(key: 'setting.customerNumber.label'),
                'Type' => 'text',
                'Size' => '10',
                'Default' => '',
                'Description' => $this->translate(key: 'setting.customerNumber.description'),
            ],
            ModuleSettings::CONFIG_LOG => [
                'FriendlyName' => $this->translate(key: 'setting.log.label'),
                'Type' => 'yesno',
                'Description' => $this->translate(key: 'setting.log.description'),
            ],
        ];
    }
}
