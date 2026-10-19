<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use WHMCS\Carbon;
use WHMCS\Module\Registrar\Actra\data\DomainContact;
use WHMCS\Module\Registrar\Actra\data\DsRecord;
use WHMCS\Module\Registrar\Actra\data\DsRecordCollection;

readonly class ApiInfoDomain
{
    public string $asciiName;
    /** @var array<string, string> nameservers by WHMCS key (ns1, ns2, …) */
    public array $nameservers;
    public ?DomainContact $registrant;
    public ?DomainContact $tech;
    public ?DomainContact $billing;
    public ?DomainContact $admin;
    public string $normalizedStatus;
    public ?Carbon $expiryDate;
    public bool $hasTransferLock;
    public bool $hasDeleteLock;
    public bool $hasUpdateLock;
    public bool $hasRenewLock;
    public bool $isDelegated;
    public bool $hasPendingTransfer;
    public ?bool $isRestorable;
    public DsRecordCollection $dsRecordCollection;

    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName,
    ) {
        $response = $apiClient->get(
            path: 'infoDomain',
            query: [
                'domainName' => $asciiDomainName,
            ],
        );
        // `name` is the domain name as registered, which is the unicode form for IDNs at some registries
        $asciiName = $response->getString(key: 'asciiName');
        $this->asciiName = $asciiName === '' ? $response->getString(key: 'name') : $asciiName;
        $nameservers = [];
        foreach ($response->getStringList(key: 'nameserver') as $index => $nameserver) {
            $nameservers['ns' . ($index + 1)] = $nameserver;
        }
        $this->nameservers = $nameservers;
        // The API uses the keys registrant, admin, tech and billing
        $contacts = $response->getRecords(key: 'contacts');
        $this->registrant = array_key_exists(key: 'registrant', array: $contacts)
            ? DomainContact::fromApiRecord(record: $contacts['registrant'])
            : null;
        $this->tech = array_key_exists(key: 'tech', array: $contacts)
            ? DomainContact::fromApiRecord(record: $contacts['tech'])
            : null;
        $this->billing = array_key_exists(key: 'billing', array: $contacts)
            ? DomainContact::fromApiRecord(record: $contacts['billing'])
            : null;
        $this->admin = array_key_exists(key: 'admin', array: $contacts)
            ? DomainContact::fromApiRecord(record: $contacts['admin'])
            : null;
        $normalizedStatus = trim(string: $response->getString(key: 'normalizedStatus'));
        $this->normalizedStatus = $normalizedStatus === '' ? 'unknown' : $normalizedStatus;
        $expiryDate = $response->getString(key: 'expiryDate');
        $this->expiryDate = $expiryDate === '' ? null : Carbon::parse(time: $expiryDate);
        // The API derives these from the raw registry status codes so that the registry-specific vocabulary of every
        // supported TLD does not have to be interpreted here.
        $this->hasTransferLock = $response->getBool(key: 'transferLock');
        $this->hasDeleteLock = $response->getBool(key: 'deleteLock');
        $this->hasUpdateLock = $response->getBool(key: 'updateLock');
        $this->hasRenewLock = $response->getBool(key: 'renewLock');
        $this->isDelegated = $response->getBool(key: 'delegated');
        $this->hasPendingTransfer = $response->getBool(key: 'pendingTransfer');
        // Null means the registry reported 'pendingDelete' without RGP data, so redemption and final deletion cannot
        // be told apart.
        $this->isRestorable = $response->getNullableBool(key: 'restorable');
        $this->dsRecordCollection = new DsRecordCollection();
        foreach ($response->getRecords(key: 'dsRecords') as $dsRecord) {
            $this->dsRecordCollection->add(
                dsRecord: new DsRecord(
                    keyTag: $dsRecord->getInt(key: 'keyTag'),
                    algorithm: $dsRecord->getInt(key: 'algorithm'),
                    digestType: $dsRecord->getInt(key: 'digestType'),
                    digest: $dsRecord->getString(key: 'digest'),
                ),
            );
        }
    }
}
