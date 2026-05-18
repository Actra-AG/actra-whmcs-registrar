<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiCheckHost;
use WHMCS\Module\Registrar\Actra\api\ApiCreateHost;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;
use WHMCS\Module\Registrar\Actra\api\ApiUpdateDomain;

class SaveNameservers extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $params = $this->params;
        $asciiDomainName = $params['domain_punycode'];
        try {
            $apiClient = $this->getApiClient();
            $activeNameservers = (new ApiInfoDomain(
                apiClient: $apiClient,
                asciiDomainName: $asciiDomainName
            ))->nameservers;
            $submittedNameservers = [];
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
                $submittedNameservers[] = strtolower(string: $nameserver);
            }
            $addNameservers = array_diff(
                array_values(array: $submittedNameservers),
                array_values(array: $activeNameservers)
            );
            $removeNameservers = array_diff(
                array_values(array: $activeNameservers),
                array_values(array: $submittedNameservers)
            );
            if (
                $addNameservers === []
                && $removeNameservers === []
            ) {
                $this->response = [
                    'success' => true,
                ];
                return;
            }
            foreach ($addNameservers as $nameserver) {
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
            new ApiUpdateDomain(
                apiClient: $apiClient,
                asciiDomainName: $asciiDomainName,
                newNameservers: $addNameservers === [] ? null : $addNameservers,
                removeNameservers: $removeNameservers === [] ? null : $removeNameservers
            );
            $this->response = [
                'success' => true,
            ];
            return;
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $throwable->getMessage()
            ];
        }
    }
}