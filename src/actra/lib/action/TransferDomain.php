<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiTransferDomain;
use WHMCS\Module\Registrar\Actra\api\ApiUpdateDomain;

// See https://developers.whmcs.com/domain-registrars/domain-syncing/
class TransferDomain extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $params = $this->params;
        $asciiDomainName = $params['domain_punycode'];
        try {
            $apiClient = $this->getApiClient();
            $apiTransferDomain = new ApiTransferDomain(
                apiClient: $apiClient,
                asciiDomainName: $asciiDomainName,
                authCode: $params['eppcode'],
                newOwnerContact: null
            );
            if ($apiTransferDomain->isActionPending) {
                $this->response = [
                    'success' => true,
                ];
                return;
            }
            $newRegistrant = new CreateRegistrant(params: $params);
            new ApiUpdateDomain(
                apiClient: $apiClient,
                asciiDomainName: $asciiDomainName,
                newTechContact: $newRegistrant->identifier,
                removeTechContact: $apiTransferDomain->techContact,
                newRegistrant: $newRegistrant->identifier,
            );
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $throwable->getMessage()
            ];
            return;
        }
        $this->response = [
            'success' => true,
        ];
    }
}