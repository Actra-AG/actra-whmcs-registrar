<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\data;

readonly class DsRecord
{
    public function __construct(
        public int $keyTag,
        public int $algorithm,
        public int $digestType,
        public string $digest
    ) {
    }

    public function getDataArray(): array
    {
        return [
            'keyTag' => $this->keyTag,
            'algorithm' => $this->algorithm,
            'digestType' => $this->digestType,
            'digest' => $this->digest,
        ];
    }
}