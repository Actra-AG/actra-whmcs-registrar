<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

// Texts of the module in English, the fallback for every missing text and unsupported language. {name} placeholders
// are replaced by the Translator.
return [
    'module.description' => 'This module can be used to manage domains with Actra as registrar.',
    'setting.apiUrl.label' => 'API URL',
    'setting.apiUrl.description' => 'Base URL of the Actra registrar API, e.g. https://www.example.com/api/v1',
    'setting.apiKey.label' => 'API Key',
    'setting.customerNumber.label' => 'Customer Number',
    'setting.customerNumber.description' => 'Customer number at Actra to be billed for registered and transferred '
        . 'domains.',
    'setting.log.label' => 'Log',
    'setting.log.description' => 'Tick to enable extra logging to module log file.',
    'button.dnssec' => 'DNSSEC Records',
    'button.restoreDomain' => 'Restore Domain',
    'dnssec.title' => 'DNSSEC Management',
    'dnssec.saved' => 'The change has been saved.',
    'dnssec.keyTag' => 'Key Tag',
    'dnssec.algorithm' => 'Algorithm',
    'dnssec.digestType' => 'Digest Type',
    'dnssec.digest' => 'Digest',
    'dnssec.action' => 'Action',
    'dnssec.delete' => 'Delete',
    'dnssec.addTitle' => 'Add a new DS record',
    'dnssec.add' => 'Add',
    'error.contactChangeNeedsReview' => 'The registry declined the contact change. An administrator at Actra checks it; '
        . 'until then, the contacts of the domain stay unchanged.',
    'error.contactNotUsable' => 'A contact cannot be used for the domain, e.g. because of an invalid phone number or '
        . 'email address. Please correct the contact details. Details: {details}',
    'error.registrantChangeNotAccepted' => 'The registrant change of this domain has a fee or a formal process at the '
        . 'registry and cannot be made here. Please contact Actra. Details: {details}',
    'error.unknownContact' => 'The contact is unknown. Details: {details}',
    'error.registryNotReachable' => 'The registry is currently not reachable. Please try again later.',
    'error.apiKeyNotAllowed' => 'The API key of the module may not use this function of the Actra registrar API. '
        . 'Please check the API key in the settings of the registrar module or contact Actra.',
    'error.rateLimited' => 'Too many availability checks with the API key of the module. Please try again later.',
    'error.rateLimitedRetryAfter' => 'Too many availability checks with the API key of the module. Please try again in '
        . '{seconds} seconds.',
    'error.customerNumberInvalid' => 'The module setting "Customer Number" must be a positive integer.',
    'error.domainNotAvailable' => '{domain}: Domain is not available',
    'error.domainPremium' => '{domain} is a premium domain with a higher price at the registry and is not registered '
        . 'at the normal price. Please contact Actra.',
    'error.domainPremiumUnknown' => 'The registry does not tell whether {domain} is a premium domain with a higher '
        . 'price, so it is not registered automatically. Please contact Actra.',
    'error.nameserverNotAvailable' => '{nameserver}: Nameserver is not available',
];
