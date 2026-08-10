<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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
        string $asciiDomainName,
        string $authCode,
        ?array $newOwnerContactData,
        ?array $newAdminContactData,
        ?array $newTechContactData,
        ?array $newBillingContactData,
        ?array $newNameservers
    ) {
        $postFields = [
            'domainName' => $asciiDomainName,
            'authCode' => $authCode,
        ];
        if ($newOwnerContactData !== null) {
            $postFields['newOwnerContactData'] = json_encode(
                value: $newOwnerContactData,
                flags: JSON_THROW_ON_ERROR
            );
        }
        if ($newAdminContactData !== null) {
            $postFields['newAdminContactData'] = json_encode(
                value: $newAdminContactData,
                flags: JSON_THROW_ON_ERROR
            );
        }
        if ($newTechContactData !== null) {
            $postFields['newTechContactData'] = json_encode(
                value: $newTechContactData,
                flags: JSON_THROW_ON_ERROR
            );
        }
        if ($newBillingContactData !== null) {
            $postFields['newBillingContactData'] = json_encode(
                value: $newBillingContactData,
                flags: JSON_THROW_ON_ERROR
            );
        }
        if ($newNameservers !== null) {
            $postFields['newNameservers'] = $newNameservers;
        }
        $apiClient->call(
            action: 'transferDomain',
            postFields: $postFields
        );
        $this->isActionPending = $apiClient->getFromResponse(key: 'isActionPending');
        $this->registrant = $apiClient->getFromResponse(key: 'registrant');
        $this->techContact = $apiClient->getFromResponse(key: 'techContact');
    }
}