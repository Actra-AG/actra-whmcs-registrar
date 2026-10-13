<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use RuntimeException;
use WHMCS\Module\Registrar\Actra\api\ApiClient;
use WHMCS\Module\Registrar\Actra\i18n\Translator;
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

    /**
     * @throws RuntimeException if the customer number setting is not a positive integer
     */
    protected function getCustomerNumber(): int
    {
        $customerNumber = ModuleSettings::parseCustomerNumber(
            value: $this->getStringParam(key: ModuleSettings::CONFIG_CUSTOMER_NUMBER),
        );
        if ($customerNumber === null) {
            throw new RuntimeException(message: $this->translate(key: 'error.customerNumberInvalid'));
        }

        return $customerNumber;
    }

    /**
     * @param array<string, string> $replacements values for the {name} placeholders of the text
     */
    protected function translate(string $key, array $replacements = []): string
    {
        return Translator::current()->get(key: $key, replacements: $replacements);
    }

    protected function getStringParam(string $key): string
    {
        $value = $this->params[$key] ?? null;

        return is_string(value: $value) ? $value : '';
    }
}
