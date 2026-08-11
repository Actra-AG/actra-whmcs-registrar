<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\api\ApiValidateApiKey;

// See https://developers.whmcs.com/domain-registrars/config-options/
class ConfigValidate extends AbstractAction
{
    protected function execute(): void
    {
        new ApiValidateApiKey(
            apiClient: $this->getApiClient()
        );
    }
}