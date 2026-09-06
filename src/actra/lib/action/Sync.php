<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Throwable;
use WHMCS\Module\Registrar\Actra\api\ApiInfoDomain;

// See https://developers.whmcs.com/domain-registrars/domain-syncing/
class Sync extends AbstractAction
{
    public readonly array $response;

    protected function execute(): void
    {
        try {
            $apiInfoDomain = new ApiInfoDomain(
                apiClient: $this->getApiClient(),
                asciiDomainName: $this->params['domain_punycode']
            );
        } catch (Throwable $throwable) {
            $this->response = [
                'error' => $throwable->getMessage()
            ];
            return;
        }
        $response = $this->getStatusResponse(normalizedStatus: $apiInfoDomain->normalizedStatus);
        // The registry does not report an expiry date for every domain, in which case the date known to WHMCS is kept.
        if ($apiInfoDomain->expiryDate !== null) {
            $response['expirydate'] = $apiInfoDomain->expiryDate->format(format: 'Y-m-d');
        }
        $this->response = $response;
    }

    /**
     * Translates the normalized status of the API into the flags WHMCS syncs the local domain status from. An empty
     * array leaves the status in WHMCS untouched, which is what an unexpected status should do: the domain exists,
     * but nothing is known about its health.
     */
    private function getStatusResponse(string $normalizedStatus): array
    {
        return match ($normalizedStatus) {
            'ok', 'inactive', 'suspended', 'pendingCreate', 'pendingTransfer' => ['active' => true],
            'expired', 'redemption', 'pendingDelete' => ['expired' => true],
            default => [],
        };
    }
}