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
            $checkDomain = new ApiCheckDomain(
                apiClient: $apiClient,
                asciiDomainName: $domainName,
            );
            if (!$checkDomain->isAvailable) {
                throw new RuntimeException(message: $this->translate(
                    key: 'error.domainNotAvailable',
                    replacements: ['domain' => $domainName],
                ));
            }
            // WHMCS would charge the normal price of the TLD; a registry that does not tell may charge a premium too
            if ($checkDomain->isPremium !== false) {
                throw new RuntimeException(message: $this->translate(
                    key: $checkDomain->isPremium === true ? 'error.domainPremium' : 'error.domainPremiumUnknown',
                    replacements: ['domain' => $domainName],
                ));
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
                'error' => $this->getErrorMessage(throwable: $throwable),
            ];

            return;
        }

        $this->response = [
            'success' => true,
        ];
    }
}
