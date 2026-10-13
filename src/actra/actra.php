<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

use WHMCS\Domain\Registrar\Domain;
use WHMCS\Module\Registrar\Actra\action\ConfigValidate;
use WHMCS\Module\Registrar\Actra\action\DeleteNameserver;
use WHMCS\Module\Registrar\Actra\action\GetConfigArray;
use WHMCS\Module\Registrar\Actra\action\GetContactDetails;
use WHMCS\Module\Registrar\Actra\action\GetDnsSecRecords;
use WHMCS\Module\Registrar\Actra\action\GetDomainInformation;
use WHMCS\Module\Registrar\Actra\action\GetEppCode;
use WHMCS\Module\Registrar\Actra\action\GetNameservers;
use WHMCS\Module\Registrar\Actra\action\MetaData;
use WHMCS\Module\Registrar\Actra\action\ModifyNameserver;
use WHMCS\Module\Registrar\Actra\action\RegisterDomain;
use WHMCS\Module\Registrar\Actra\action\RegisterNameserver;
use WHMCS\Module\Registrar\Actra\action\RequestDelete;
use WHMCS\Module\Registrar\Actra\action\RestoreDomain;
use WHMCS\Module\Registrar\Actra\action\SaveContactDetails;
use WHMCS\Module\Registrar\Actra\action\SaveNameservers;
use WHMCS\Module\Registrar\Actra\action\Sync;
use WHMCS\Module\Registrar\Actra\action\TransferDomain;

if (!defined(constant_name: 'WHMCS')) {
    die('This file cannot be accessed directly');
}

// See https://developers.whmcs.com/domain-registrars/function-index/

function actra_MetaData(): array
{
    return (new MetaData(params: []))->metaData;
}

function actra_getConfigArray(array $params = []): array
{
    return (new GetConfigArray(params: $params))->config;
}

function actra_config_validate(array $params): void
{
    new ConfigValidate(params: $params);
}

/**
 * Called when the registration of a new domain is initiated within WHMCS.
 */
function actra_RegisterDomain(array $params): array
{
    return (new RegisterDomain(params: $params))->response;
}

/**
 * Called when a domain transfer request is initiated within WHMCS.
 */
function actra_TransferDomain(array $params): array
{
    return (new TransferDomain(params: $params))->response;
}

/**
 * Called when a request to renew a domain is initiated within WHMCS.
 *
 * @return void
 */
//function actra_RenewDomain($params)
//{
// TODO: Not available for SWITCH domains to be created for other TLDs.
//}

/**
 * Called when a domain is viewed within WHMCS.
 * Recommended instead of GetNameservers and GetRegistrarLock in WHMCS 7.6 and later.
 */
function actra_GetDomainInformation(array $params): Domain
{
    return (new GetDomainInformation(params: $params))->domain;
}

/**
 * Called when Email Forwarding is displayed within WHMCS.
 *
 * @return true[]
 */
//function actra_GetEmailForwarding(array $params)
//{
// TODO: Not available for SWITCH domains to be created for other TLDs.
//}

/**
 * Called when changes to Email Forwarding are submitted.
 *
 * @return void
 */
//function actra_SaveEmailForwarding(array $params)
//{
// TODO: Not available for SWITCH domains to be created for other TLDs.
//}


/**
 * Called when a domain is viewed within WHMCS. It can return up to 5 nameservers that are set for the domain.
 */
function actra_GetNameservers(array $params): array
{
    return (new GetNameservers(params: $params))->nameservers;
}

/**
 * Called when a change is submitted for domain nameservers.
 */
function actra_SaveNameservers(array $params): array
{
    return (new SaveNameservers(params: $params))->response;
}

/**
 * Called when domain details are viewed within WHMCS.
 * It should return the current lock status of a domain.
 *
 * @return array|string
 */
//function actra_GetRegistrarLock(array $params): array|string
//{
//    return (new GetRegistrarLock(params: $params))->result;
//}

/**
 * Called when the lock status setting is toggled within WHMCS.
 */
//function actra_SaveRegistrarLock(array $params): void
//{
// TODO: Not available for SWITCH domains to be created for other TLDs.
//}

/**
 * Called when the WHOIS information is displayed within WHMCS.
 */
function actra_getContactDetails(array $params): array
{
    return (new GetContactDetails(params: $params))->contacts;
}

/**
 * Called when revised WHOIS information is submitted.
 */
function actra_SaveContactDetails(array $params): array
{
    return (new SaveContactDetails(params: $params))->response;
}

/**
 * Called when a request is made to resend the IRTP contact verification emails.
 */
//function actra_ResendIRTPVerificationEmail(array $params): array
//{
// TODO: Not available for SWITCH domains to be created for other TLDs.
//}

/**
 * Called when the DNS Host Records are requested to be viewed within WHMCS.
 */
//function actra_GetDNS(array $params): array
//{
// TODO: To be added when implementing DNS management.
//}

/**
 * Called when any changes to DNS Host Records information are submitted.
 *
 * @return mixed
 */
//function actra_SaveDNS(array $params)
//{
// TODO: To be added when implementing DNS management.
//}

/**
 * Called when the ID Protection setting is toggled on or off.
 */
//function actra_IDProtectToggle(array $params): array
//{
// TODO: To be added when implementing DNS management.
//}

/**
 * Called when the EPP Code is requested for a transfer out.
 *
 * @return true[]
 * TODO: Implement sending the auth code to registrar instead of displaying it.
 */
function actra_GetEPPCode(array $params): array
{
    return (new GetEppCode(params: $params))->response;
}

/**
 * Called when a domain release is requested (eg. UK IPSTag Changes).
 */
//function actra_ReleaseDomain(array $params): array
//{
// TODO: Not available for SWITCH domains to be created for other TLDs.
//}

/**
 * Called when a child nameserver registration request comes from WHMCS.
 */
function actra_RegisterNameserver(array $params): array
{
    return (new RegisterNameserver(params: $params))->response;
}

/**
 * Called when a child nameserver modification request comes from WHMCS.
 */
function actra_ModifyNameserver(array $params): array
{
    return (new ModifyNameserver(params: $params))->response;
}

/**
 * Called when a child nameserver deletion request comes from WHMCS.
 */
function actra_DeleteNameserver(array $params): array
{
    return (new DeleteNameserver(params: $params))->response;
}

/**
 * Called when a domain deletion request comes from WHMCS.
 */
function actra_RequestDelete(array $params): array
{
    return (new RequestDelete(params: $params))->response;
}

/**
 * Used to define module-specific client area output.
 * It accepts a return of HTML for display on the domain details page of the client area.
 * Output via a template file within the module folder named “clientarea.tpl” is also possible.
 * This function is discussed in more detail later on in the docs.
 *
 * @return string
 */
//function actra_ClientArea(array $params): string
//{
// TODO
//}

/**
 * Used to define custom functions that the module supports.
 * Customers can invoke and run these from the client area.
 * The functions can perform actions or product page output in the client area.
 * Example usages for this are to provide domain management pages, bandwidth reporting pages, etc…
 */
function actra_ClientAreaCustomButtonArray(): array
{
    return [
        'DNSSEC-Records' => 'GetDNSSEC',
    ];
}

function actra_GetDNSSEC(array $params): array
{
    return (new GetDnsSecRecords(
        params: $params,
        successful: false
    ))->response;
}

/**
 * Like the above, used to define custom functions.
 * These are functions that customers can invoke but are not shown as buttons by default.
 * (i.e., custom client area output will invoke them).
 *
 * @return string[]
 */
//function actra_ClientAreaAllowedFunctions(): array
//{
// not used yet
//}

function actra_AdminCustomButtonArray($params): array
{
    return [
        'Restore Domain' => 'RestoreDomain',
    ];
}

function actra_RestoreDomain(array $params): array
{
    return (new RestoreDomain(params: $params))->response;
}

/**
 * Called when a domain name’s expiry date and status change at registry level is requested for propagation into WHMCS.
 * (i.e., by the Domain Synchronization feature).
 */
function actra_Sync(array $params): array
{
    return (new Sync(params: $params))->response;
}

/**
 * Called when the status of a domain transfer at registry level is requested for propagation into WHMCS.
 * (i.e., by the Domain Synchronization feature).
 *
 * @param array $params
 * @return array|string
 */
//function actra_TransferSync(array $params): array
//{
// TODO: Not available for SWITCH domains to be created for other TLDs.
//}

/**
 * Called when the importation and synchronizing of TLD pricing, into WHMCS, from a registrar is requested.
 * (i.e., by the Registrar TLD & Pricing Sync Utility).
 *
 * @param array $params
 * @return array
 */
//function actra_GetTldPricing(array $params): array
//{
// TODO: Not available yet
//}
