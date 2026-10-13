<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\data;

class DsRecordCollection
{
    /** @var DsRecord[] $items */
    private array $items = [];

    public function add(DsRecord $dsRecord): void
    {
        $this->items[$dsRecord->digest] = $dsRecord;
    }

    public function getDataArray(): array
    {
        $dsRecords = [];
        foreach ($this->items as $dsRecord) {
            $dsRecords[] = $dsRecord->getDataArray();
        }
        return $dsRecords;
    }

    public function getAsJson(): string
    {
        return json_encode(value: $this->getDataArray());
    }

    public static function createFromRawData(string $rawData): DsRecordCollection
    {
        $dsRecordCollection = new DsRecordCollection();
        $array = json_decode(json: $rawData);
        if ($array === null) {
            return $dsRecordCollection;
        }
        foreach ($array as $item) {
            $dsRecordCollection->add(
                dsRecord: new DsRecord(
                    keyTag: (int) $item->keyTag,
                    algorithm: (int) $item->algorithm,
                    digestType: (int) $item->digestType,
                    digest: (string) $item->digest
                )
            );
        }
        return $dsRecordCollection;
    }

    public function getByDigest(string $digest): ?DsRecord
    {
        return array_key_exists(key: $digest, array: $this->items) ? $this->items[$digest] : null;
    }

    public function getByKeyTag(int $keyTag): ?DsRecord
    {
        foreach ($this->items as $dsRecord) {
            if ($dsRecord->keyTag === $keyTag) {
                return $dsRecord;
            }
        }
        return null;
    }
}
