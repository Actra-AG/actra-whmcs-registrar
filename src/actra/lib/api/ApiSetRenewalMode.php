<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use WHMCS\Module\Registrar\Actra\data\RenewalModeEnum;

/**
 * Cancels a domain ("expire") or undoes the cancellation ("renew"). Setting the current mode again changes nothing.
 */
final readonly class ApiSetRenewalMode
{
    /** With "expire": the day the domain ends (Y-m-d, Europe/Zurich); null otherwise. */
    public ?string $deletionDate;

    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName,
        RenewalModeEnum $renewalMode,
    ) {
        $response = $apiClient->patch(
            path: 'setRenewalMode',
            query: [
                'domainName' => $asciiDomainName,
            ],
            body: [
                'renewalMode' => $renewalMode->value,
            ],
            timeoutInSeconds: ApiClient::TIMEOUT_REGISTRY_CHANGE,
        );
        $this->deletionDate = $response->getNullableString(key: 'deletionDate');
    }
}
