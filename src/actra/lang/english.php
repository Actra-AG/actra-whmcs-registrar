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
    'setting.apiUrl.description' => 'Base URL of the Actra Domains REST API, e.g. https://www.example.com/api/v1',
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
    'error.customerNumberInvalid' => 'The module setting "Customer Number" must be a positive integer.',
    'error.domainNotAvailable' => '{domain}: Domain is not available',
    'error.nameserverNotAvailable' => '{nameserver}: Nameserver is not available',
];
