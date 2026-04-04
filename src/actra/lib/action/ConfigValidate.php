<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\api\ApiValidateApiKey;
use WHMCS\Module\Registrar\Actra\settings\ModuleSettings;

// See https://developers.whmcs.com/domain-registrars/config-options/
class ConfigValidate extends AbstractAction
{
    protected function execute(): void
    {
        new ApiValidateApiKey(
            apiClient: $this->getApiClient(),
            username: $this->params[ModuleSettings::CONFIG_API_USERNAME],
        );
    }
}