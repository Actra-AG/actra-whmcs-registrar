<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use Override;
use WHMCS\Module\Registrar\Actra\api\ApiValidateApiKey;

// See https://developers.whmcs.com/domain-registrars/config-options/
class ConfigValidate extends AbstractAction
{
    #[Override]
    protected function execute(): void
    {
        // Validates the setting before the API key
        $this->getCustomerNumber();
        new ApiValidateApiKey(
            apiClient: $this->getApiClient()
        );
    }
}
