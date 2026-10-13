<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Override;
use RuntimeException;
use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiCheckDomain;
use WHMCS\Module\Registrar\Actra\api\ApiCreateDomain;

class RegisterDomain extends AbstractAction
{
    /** @var array{success: true}|array{error: string} */
    public readonly array $response;

    #[Override]
    protected function execute(): void
    {
        $domainName = $this->getStringParam(key: 'domain_punycode');
        try {
            $apiClient = $this->getApiClient();
            if (!(new ApiCheckDomain(
                apiClient: $apiClient,
                asciiDomainName: $domainName,
            ))->isAvailable) {
                throw new RuntimeException(message: $domainName . ': Domain is not available');
            }
            // Glue records are created with RegisterNameserver; the API creates no hosts of other registrars
            $registrant = new CreateRegistrant(params: $this->params);
            new ApiCreateDomain(
                apiClient: $apiClient,
                customerNumber: $this->getCustomerNumber(),
                domainName: $domainName,
                nameservers: SubmittedNameservers::fromParams(params: $this->params),
                registrant: $registrant->identifier,
                adminContact: '',
                techContact: $registrant->identifier,
                billingContact: '',
            );
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
