<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\settings\ModuleSettings;

// See https://developers.whmcs.com/domain-registrars/metadata-params/
class MetaData extends AbstractAction
{
    public readonly array $metaData;

    protected function execute(): void
    {
        $this->metaData = [
            'DisplayName' => ModuleSettings::FRIENDLY_DISPLAY_NAME,
            'APIVersion' => ModuleSettings::API_VERSION,
            'NonLinearRegistrationPricing' => false,
        ];
    }
}
