<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Exception;
use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiCheckDomain;
use WHMCS\Module\Registrar\Actra\api\ApiCheckHost;
use WHMCS\Module\Registrar\Actra\api\ApiCreateDomain;
use WHMCS\Module\Registrar\Actra\api\ApiCreateHost;

class RegisterDomain extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $params = $this->params;
        $domainName = $params['domain_punycode'];
        try {
            $apiClient = $this->getApiClient();
            if (!(new ApiCheckDomain(
                apiClient: $apiClient,
                asciiDomainName: $domainName
            ))->isAvailable) {
                throw new Exception(message: $domainName . ': Domain is not available');
            }
            $nameservers = [];
            for ($i = 1; $i <= 5; $i++) {
                $key = 'ns' . $i . '_punycode';
                if (!array_key_exists(
                    key: $key,
                    array: $params
                )) {
                    break;
                }
                $nameserver = trim(string: $params[$key]);
                if ($nameserver === '') {
                    continue;
                }
                $nameservers[] = $nameserver;
                if (!(new ApiCheckHost(
                    apiClient: $apiClient,
                    registry: 'SWITCH',
                    nameserver: $nameserver
                ))->isAvailable) {
                    continue;
                }
                new ApiCreateHost(
                    apiClient: $apiClient,
                    registry: 'SWITCH',
                    nameserver: $nameserver,
                    ipv4: [],
                    ipv6: []
                );
            }
            $registrant = new CreateRegistrant(params: $params);
            new ApiCreateDomain(
                apiClient: $apiClient,
                domainName: $domainName,
                nameservers: $nameservers,
                registrant: $registrant->identifier,
                adminContact: '',
                techContact: $registrant->identifier,
                billingContact: ''
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
