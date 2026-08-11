<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use WHMCS\Module\Registrar\Actra\data\DsRecord;

readonly class ApiDeleteDsRecord
{
    public function __construct(
        ApiClient $apiClient,
        string $asciiDomainName,
        DsRecord $dsRecord
    ) {
        $apiClient->delete(
            path: 'deleteDsRecord',
            query: [
                'domainName' => $asciiDomainName,
                'keyTag' => $dsRecord->keyTag,
                'alg' => $dsRecord->algorithm,
                'digestType' => $dsRecord->digestType,
                'digest' => $dsRecord->digest,
            ]
        );
    }
}