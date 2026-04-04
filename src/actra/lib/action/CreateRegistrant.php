<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use WHMCS\Module\Registrar\Actra\api\ApiCreateContact;
use WHMCS\Module\Registrar\Actra\data\DomainContact;

class CreateRegistrant extends AbstractAction
{
    public readonly string $identifier;

    protected function execute(): void
    {
        $params = $this->params;
        $this->identifier = (new ApiCreateContact(
            apiClient: $this->getApiClient(),
            registry: 'SWITCH',
            domainContact: new DomainContact(
                name: $params['fullname'],
                org: $params['companyname'],
                street: $params['address1'],
                addressRow2: $params['address2'],
                city: $params['city'],
                postCode: $params['postcode'],
                countryCode: $params['countrycode'],
                email: $params['email'],
                phone: $params['fullphonenumber'],
            )
        ))->identifier;
    }
}