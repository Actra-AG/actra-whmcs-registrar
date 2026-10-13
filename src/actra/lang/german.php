<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

// Texts of the module in German (Swiss spelling, without ß), same keys as english.php
return [
    'module.description' => 'Mit diesem Modul werden Domains mit Actra als Registrar verwaltet.',
    'setting.apiUrl.label' => 'API-URL',
    'setting.apiUrl.description' => 'Basis-URL der REST-API von Actra Domains, z. B. https://www.example.com/api/v1',
    'setting.apiKey.label' => 'API-Schlüssel',
    'setting.customerNumber.label' => 'Kundennummer',
    'setting.customerNumber.description' => 'Kundennummer bei Actra, der registrierte und transferierte Domains '
        . 'verrechnet werden.',
    'setting.log.label' => 'Protokoll',
    'setting.log.description' => 'Aktivieren, um zusätzlich in die Protokolldatei des Moduls zu schreiben.',
    'button.dnssec' => 'DNSSEC-Einträge',
    'button.restoreDomain' => 'Domain wiederherstellen',
    'dnssec.title' => 'DNSSEC-Verwaltung',
    'dnssec.saved' => 'Die Änderung wurde gespeichert.',
    'dnssec.keyTag' => 'Key Tag',
    'dnssec.algorithm' => 'Algorithmus',
    'dnssec.digestType' => 'Digest Type',
    'dnssec.digest' => 'Digest',
    'dnssec.action' => 'Aktion',
    'dnssec.delete' => 'Löschen',
    'dnssec.addTitle' => 'Neuen DS-Eintrag hinzufügen',
    'dnssec.add' => 'Hinzufügen',
    'error.customerNumberInvalid' => 'Die Moduleinstellung «Kundennummer» muss eine positive ganze Zahl sein.',
    'error.domainNotAvailable' => '{domain}: Die Domain ist nicht verfügbar',
    'error.nameserverNotAvailable' => '{nameserver}: Der Nameserver ist nicht verfügbar',
];
