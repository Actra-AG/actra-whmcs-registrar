<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiResetAuthCode
{
    public string $authCode;

    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName
    ) {
        $apiClient->call(
            action: 'resetAuthCode',
            postFields: [
                'domainName' => $asciiDomainName,
            ]
        );
        $this->authCode = $apiClient->getFromResponse(key: 'authCode');
    }
}