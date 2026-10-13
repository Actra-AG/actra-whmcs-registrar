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
    public array $nameservers;
    public string $registrantIdentifier;
    public string $techIdentifier;
    public string $billingIdentifier;
    public string $adminIdentifier;
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
        string $asciiDomainName
    ) {
        $apiClient->get(
            path: 'infoDomain',
            query: [
                'domainName' => $asciiDomainName,
            ]
        );
        $this->asciiName = $apiClient->getFromResponse(key: 'name');
        $nameservers = [];
        $i = 0;
        foreach ($apiClient->getFromResponse(key: 'nameserver') as $nameserver) {
            $i++;
            $nameservers['ns' . $i] = $nameserver;
        }
        $this->nameservers = $nameservers;
        $this->registrantIdentifier = (string) $apiClient->getFromResponse(key: 'registrant');
        $this->techIdentifier = (string) $apiClient->getFromResponse(key: 'techContact');
        $this->billingIdentifier = (string) $apiClient->getFromResponse(key: 'billingContact');
        $this->adminIdentifier = (string) $apiClient->getFromResponse(key: 'adminContact');
        $registrant = null;
        $tech = null;
        $billing = null;
        $admin = null;
        foreach ((array) $apiClient->getFromResponse(key: 'contacts') as $originalType => $contact) {
            $domainContact = new DomainContact(
                name: $contact->name,
                org: $contact->org,
                street: $contact->street,
                addressRow2: $contact->addressRow2,
                city: $contact->city,
                postCode: $contact->zipCode,
                countryCode: $contact->countryCode,
                email: $contact->email,
                phone: $contact->phone
            );
            switch ($originalType) {
                case 'registrant':
                    $registrant = $domainContact;
                    break;
                case 'tech':
                    $tech = $domainContact;
                    break;
                case 'billing':
                    $billing = $domainContact;
                    break;
                case 'admin':
                    $admin = $domainContact;
                    break;
            }
        }
        $this->registrant = $registrant;
        $this->tech = $tech;
        $this->billing = $billing;
        $this->admin = $admin;
        $normalizedStatus = trim(string: (string) $apiClient->getFromResponse(key: 'normalizedStatus'));
        $this->normalizedStatus = $normalizedStatus === '' ? 'unknown' : $normalizedStatus;
        $expiryDate = $apiClient->getFromResponse(key: 'expiryDate');
        $this->expiryDate = $expiryDate === null || $expiryDate === ''
            ? null
            : Carbon::parse(time: $expiryDate);
        // The API derives these from the raw registry status codes so that the registry-specific vocabulary of every
        // supported TLD does not have to be interpreted here.
        $this->hasTransferLock = $apiClient->getFromResponse(key: 'transferLock') === true;
        $this->hasDeleteLock = $apiClient->getFromResponse(key: 'deleteLock') === true;
        $this->hasUpdateLock = $apiClient->getFromResponse(key: 'updateLock') === true;
        $this->hasRenewLock = $apiClient->getFromResponse(key: 'renewLock') === true;
        $this->isDelegated = $apiClient->getFromResponse(key: 'delegated') === true;
        $this->hasPendingTransfer = $apiClient->getFromResponse(key: 'pendingTransfer') === true;
        $restorable = $apiClient->getFromResponse(key: 'restorable');
        // Null means the registry reported 'pendingDelete' without RGP data, so redemption and final deletion cannot
        // be told apart.
        $this->isRestorable = is_bool(value: $restorable) ? $restorable : null;
        $this->dsRecordCollection = new DsRecordCollection();
        foreach ((array) $apiClient->getFromResponse(key: 'dsRecords') as $dsRecord) {
            $this->dsRecordCollection->add(
                dsRecord: new DsRecord(
                    keyTag: $dsRecord->keyTag,
                    algorithm: $dsRecord->algorithm,
                    digestType: $dsRecord->digestType,
                    digest: $dsRecord->digest
                )
            );
        }
    }
}
