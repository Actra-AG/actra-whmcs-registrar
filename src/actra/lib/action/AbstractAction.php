<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use RuntimeException;
use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiClient;
use WHMCS\Module\Registrar\Actra\api\ApiException;
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
     * Error message for WHMCS: the contact errors of the API are explained in the language of the user, with the
     * details of the API, an unreachable registry and a rate limit with a request to try again later, an API key
     * without the right for the endpoint as configuration problem; other errors are shown as received.
     */
    protected function getErrorMessage(Throwable $throwable): string
    {
        if (!$throwable instanceof ApiException) {
            return $throwable->getMessage();
        }
        if ($throwable->isContactChangeNeedsReview()) {
            return $this->translate(key: 'error.contactChangeNeedsReview');
        }
        if ($throwable->errorCode === ApiException::ERROR_CODE_REGISTRY_NOT_REACHABLE) {
            return $this->translate(key: 'error.registryNotReachable');
        }
        if ($throwable->errorCode === ApiException::ERROR_CODE_API_KEY_NOT_ALLOWED) {
            return $this->translate(key: 'error.apiKeyNotAllowed');
        }
        if ($throwable->errorCode === ApiException::ERROR_CODE_RATE_LIMITED) {
            return $throwable->retryAfterSeconds === null
                ? $this->translate(key: 'error.rateLimited')
                : $this->translate(
                    key: 'error.rateLimitedRetryAfter',
                    replacements: ['seconds' => (string) $throwable->retryAfterSeconds],
                );
        }
        $key = match ($throwable->errorCode) {
            ApiException::ERROR_CODE_CONTACT_NOT_USABLE => 'error.contactNotUsable',
            ApiException::ERROR_CODE_REGISTRANT_CHANGE_NOT_ACCEPTED => 'error.registrantChangeNotAccepted',
            ApiException::ERROR_CODE_UNKNOWN_CONTACT => 'error.unknownContact',
            default => null,
        };

        return $key === null
            ? $throwable->getMessage()
            : $this->translate(key: $key, replacements: ['details' => $throwable->apiMessage]);
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
