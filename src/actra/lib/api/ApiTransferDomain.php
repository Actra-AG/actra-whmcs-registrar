<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiTransferDomain
{
    public bool $isActionPending;
    public string $registrant;
    public string $techContact;

    public function __construct(
        ApiClient $apiClient,
        int $customerNumber,
        string $asciiDomainName,
        string $authCode,
        ?array $newOwnerContactData,
        ?array $newAdminContactData,
        ?array $newTechContactData,
        ?array $newBillingContactData,
        ?array $newNameservers
    ) {
        $body = [
            'customerNumber' => $customerNumber,
            'domainName' => $asciiDomainName,
            'authCode' => $authCode,
        ];
        if ($newOwnerContactData !== null) {
            $body['newOwnerContactData'] = $newOwnerContactData;
        }
        if ($newAdminContactData !== null) {
            $body['newAdminContactData'] = $newAdminContactData;
        }
        if ($newTechContactData !== null) {
            $body['newTechContactData'] = $newTechContactData;
        }
        if ($newBillingContactData !== null) {
            $body['newBillingContactData'] = $newBillingContactData;
        }
        if ($newNameservers !== null) {
            $body['newNameservers'] = $newNameservers;
        }

        $response = $apiClient->post(
            path: 'transferDomain',
            timeoutInSeconds: ApiClient::TIMEOUT_REGISTRY_CHANGE,
            body: $body,
        );
        $this->isActionPending = $response->getBool(key: 'isActionPending');
        $this->registrant = $response->getString(key: 'registrant');
        $this->techContact = $response->getString(key: 'techContact');
    }
}
