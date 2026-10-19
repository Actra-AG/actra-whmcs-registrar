<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiTransferDomain;

// See https://developers.whmcs.com/domain-registrars/domain-syncing/
class TransferDomain extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        $params = $this->params;
        $asciiDomainName = $params['domain_punycode'];
        try {
            $ownerContactData = $this->getRegistrantContactData(params: $params);
            $nameservers = SubmittedNameservers::fromParams(params: $params);

            new ApiTransferDomain(
                apiClient: $this->getApiClient(),
                customerNumber: $this->getCustomerNumber(),
                asciiDomainName: $asciiDomainName,
                authCode: $params['eppcode'],
                newOwnerContactData: $ownerContactData,
                newAdminContactData: $this->getRoleContactData(
                    params: $params,
                    prefix: 'admin',
                    fallbackContactData: $ownerContactData
                ),
                newTechContactData: $this->getRoleContactData(
                    params: $params,
                    prefix: 'tech',
                    fallbackContactData: $ownerContactData
                ),
                newBillingContactData: $this->getRoleContactData(
                    params: $params,
                    prefix: 'billing',
                    fallbackContactData: $ownerContactData
                ),
                newNameservers: $nameservers === [] ? null : $nameservers,
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

    private function getRegistrantContactData(array $params): array
    {
        return [
            'firstName' => $params['firstname'],
            'lastName' => $params['lastname'],
            'org' => $params['companyname'],
            'street' => $params['address1'],
            'addressRow2' => $params['address2'],
            'city' => $params['city'],
            'zipCode' => $params['postcode'],
            'countryCode' => $params['countrycode'],
            'phone' => $params['fullphonenumber'],
            'email' => $params['email'],
            'vat' => $params['tax_id'] ?? '',
            'socialSecurityNumber' => '',
        ];
    }

    private function getRoleContactData(
        array $params,
        string $prefix,
        array $fallbackContactData
    ): array {
        if (!$this->hasRoleContactData(
            params: $params,
            prefix: $prefix
        )) {
            return $fallbackContactData;
        }

        return [
            'firstName' => $params[$prefix . 'firstname'],
            'lastName' => $params[$prefix . 'lastname'],
            'org' => $params[$prefix . 'companyname'] ?? '',
            'street' => $params[$prefix . 'address1'],
            'addressRow2' => $params[$prefix . 'address2'] ?? '',
            'city' => $params[$prefix . 'city'],
            'zipCode' => $params[$prefix . 'postcode'],
            'countryCode' => $params[$prefix . 'country'],
            'phone' => $params[$prefix . 'fullphonenumber'],
            'email' => $params[$prefix . 'email'],
            'vat' => $params[$prefix . 'tax_id'] ?? '',
            'socialSecurityNumber' => '',
        ];
    }

    private function hasRoleContactData(
        array $params,
        string $prefix
    ): bool {
        foreach ([
            $prefix . 'firstname',
            $prefix . 'lastname',
            $prefix . 'address1',
            $prefix . 'city',
            $prefix . 'postcode',
            $prefix . 'country',
            $prefix . 'email',
            $prefix . 'fullphonenumber',
        ] as $key) {
            if (
                !array_key_exists(
                    key: $key,
                    array: $params
                )
                || trim(string: (string) $params[$key]) === ''
            ) {
                return false;
            }
        }

        return true;
    }
}
