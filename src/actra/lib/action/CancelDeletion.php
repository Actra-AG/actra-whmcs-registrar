<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Override;
use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiSetRenewalMode;
use WHMCS\Module\Registrar\Actra\data\RenewalModeEnum;

/**
 * Undoes RequestDelete: the domain is renewed automatically again.
 */
final class CancelDeletion extends AbstractAction
{
    /** @var array{success: true}|array{error: string} */
    public readonly array $response;

    #[Override]
    protected function execute(): void
    {
        try {
            new ApiSetRenewalMode(
                apiClient: $this->getApiClient(),
                asciiDomainName: $this->getStringParam(key: 'domain_punycode'),
                renewalMode: RenewalModeEnum::RENEW,
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
