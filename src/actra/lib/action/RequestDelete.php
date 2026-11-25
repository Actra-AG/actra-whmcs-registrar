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
 * Called by the "Request Delete" button of WHMCS: lets the domain expire instead of deleting it at once. The Actra
 * registrar API deletes it on its deletion date (.ch and .li: one day before the renewal; other TLDs: on the expiry
 * date). Undone with CancelDeletion.
 */
final class RequestDelete extends AbstractAction
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
                renewalMode: RenewalModeEnum::EXPIRE,
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
