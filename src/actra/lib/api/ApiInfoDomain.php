<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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
    public string $domainStatus;
    public Carbon $expiryDate;
    public bool $hasTransferLock;
    public DsRecordCollection $dsRecordCollection;

    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName
    ) {
        $apiClient->call(
            action: 'infoDomain',
            postFields: [
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
        $this->registrantIdentifier = (string)$apiClient->getFromResponse(key: 'registrant');
        $this->techIdentifier = (string)$apiClient->getFromResponse(key: 'techContact');
        $this->billingIdentifier = (string)$apiClient->getFromResponse(key: 'billingContact');
        $this->adminIdentifier = (string)$apiClient->getFromResponse(key: 'adminContact');
        $registrant = null;
        $tech = null;
        $billing = null;
        $admin = null;
        foreach ((array)$apiClient->getFromResponse(key: 'contacts') as $originalType => $contact) {
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
        $this->domainStatus = current(array: $apiClient->getFromResponse(key: 'status'));
        $this->expiryDate = Carbon::parse(
            time: $apiClient->getFromResponse(key: 'expiryDate')
        );
        $this->hasTransferLock = in_array(
            needle: $this->domainStatus,
            haystack: [
                'clientTransferProhibited',
                'serverTransferProhibited'
            ]
        );
        $this->dsRecordCollection = new DsRecordCollection();
        foreach ((array)$apiClient->getFromResponse(key: 'dsRecords') as $dsRecord) {
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