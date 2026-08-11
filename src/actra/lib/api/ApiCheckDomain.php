<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

readonly class ApiCheckDomain
{
    public bool $isAvailable;

    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName
    ) {
        $apiClient->get(
            path: 'checkDomain',
            query: [
                'domainName' => $asciiDomainName,
            ]
        );
        $this->isAvailable = $apiClient->getFromResponse(key: 'isAvailable');
    }
}