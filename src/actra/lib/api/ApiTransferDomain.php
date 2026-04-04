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
        ?string $newOwnerContact
    ) {
        $postFields = [
            'domainName' => $asciiDomainName,
            'authCode' => $authCode,
        ];
        if ($newOwnerContact !== null) {
            $postFields['newOwnerContact'] = $newOwnerContact;
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