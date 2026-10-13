<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Override;
use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;
use WHMCS\Module\Registrar\Actra\api\ApiUpdateDomain;

class SaveNameservers extends AbstractAction
{
    /** @var array{success: true}|array{error: string} */
    public readonly array $response;

    #[Override]
    protected function execute(): void
    {
        $asciiDomainName = $this->getStringParam(key: 'domain_punycode');
        try {
            $apiClient = $this->getApiClient();
            $activeNameservers = array_map(
                callback: strtolower(...),
                array: array_values(array: (new ApiInfoDomain(
                    apiClient: $apiClient,
                    asciiDomainName: $asciiDomainName,
                ))->nameservers),
            );
            $submittedNameservers = SubmittedNameservers::fromParams(params: $this->params);
            // The API expects the complete list and rejects a request that does not change anything
            if (!SubmittedNameservers::equal(first: $activeNameservers, second: $submittedNameservers)) {
                new ApiUpdateDomain(
                    apiClient: $apiClient,
                    asciiDomainName: $asciiDomainName,
                    nameservers: $submittedNameservers,
                );
            }
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $throwable->getMessage(),
            ];

            return;
        }
        $this->response = [
            'success' => true,
        ];
    }
}
