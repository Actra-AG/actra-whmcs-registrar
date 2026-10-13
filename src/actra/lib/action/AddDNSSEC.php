<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\api\ApiAddDsRecord;
use WHMCS\Module\Registrar\Actra\data\DsRecord;

class AddDNSSEC extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        new ApiAddDsRecord(
            apiClient: $this->getApiClient(),
            asciiDomainName: $this->params['domain_punycode'],
            dsRecord: new DsRecord(
                keyTag: (
                    array_key_exists(
                        key: 'keytag',
                        array: $_POST
                    )
                    && is_scalar(value: $_POST['keytag'])
                ) ? (int) $_POST['keytag'] : null,
                algorithm: (
                    array_key_exists(
                        key: 'alg',
                        array: $_POST
                    )
                    && is_scalar(value: $_POST['alg'])
                ) ? (int) $_POST['alg'] : null,
                digestType: (
                    array_key_exists(
                        key: 'digestType',
                        array: $_POST
                    )
                    && is_scalar(value: $_POST['digestType'])
                ) ? (int) $_POST['digestType'] : null,
                digest: (
                    array_key_exists(
                        key: 'digest',
                        array: $_POST
                    )
                    && is_scalar(value: $_POST['digest'])
                ) ? (string) $_POST['digest'] : null
            )
        );
    }
}
