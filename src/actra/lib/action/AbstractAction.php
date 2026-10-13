<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\api\ApiClient;
use WHMCS\Module\Registrar\Actra\settings\ModuleSettings;

abstract class AbstractAction
{
    private static ?ApiClient $apiClient = null;

    public function __construct(protected readonly array $params)
    {
        if ($params[ModuleSettings::CONFIG_LOG] === 'on') {
            Log::execute(
                className: static::class,
                params: $this->params
            );
        }
        $this->execute();
    }

    abstract protected function execute(): void;

    protected function getApiClient(): ApiClient
    {
        $params = $this->params;
        if (AbstractAction::$apiClient === null) {
            AbstractAction::$apiClient = new ApiClient(
                apiUrl: $params[ModuleSettings::CONFIG_API_URL],
                apiKey: $params[ModuleSettings::CONFIG_API_KEY],
                log: $params[ModuleSettings::CONFIG_LOG] === 'on'
            );
        }
        return AbstractAction::$apiClient;
    }
}
