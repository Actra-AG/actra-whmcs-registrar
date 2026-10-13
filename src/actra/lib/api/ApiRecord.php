<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

/**
 * Typed read access to a decoded JSON object of the API. Missing fields and fields of another type return the empty
 * value of the requested type (or null for the nullable getters), so an optional field never causes a TypeError.
 */
final readonly class ApiRecord
{
    /**
     * @param array<array-key, mixed> $fields decoded JSON object (json_decode() with associative arrays)
     */
    public function __construct(private array $fields) {}

    public function getString(string $key): string
    {
        return $this->getNullableString(key: $key) ?? '';
    }

    public function getNullableString(string $key): ?string
    {
        $value = $this->fields[$key] ?? null;
        if (is_string(value: $value)) {
            return $value;
        }
        if (is_int(value: $value)) {
            return (string) $value;
        }

        return null;
    }

    public function getBool(string $key): bool
    {
        return $this->getNullableBool(key: $key) === true;
    }

    public function getNullableBool(string $key): ?bool
    {
        $value = $this->fields[$key] ?? null;

        return is_bool(value: $value) ? $value : null;
    }

    public function getInt(string $key): int
    {
        $value = $this->fields[$key] ?? null;

        return is_int(value: $value) ? $value : 0;
    }

    /**
     * @return list<string>
     */
    public function getStringList(string $key): array
    {
        $value = $this->fields[$key] ?? null;
        if (!is_array(value: $value)) {
            return [];
        }

        return array_values(array: array_filter(
            array: $value,
            callback: static fn(mixed $item): bool => is_string(value: $item),
        ));
    }

    public function getRecord(string $key): ?self
    {
        $value = $this->fields[$key] ?? null;

        return is_array(value: $value) ? new self(fields: $value) : null;
    }

    /**
     * Records of a JSON array or of a JSON object (map), with their original keys.
     *
     * @return array<array-key, self>
     */
    public function getRecords(string $key): array
    {
        $value = $this->fields[$key] ?? null;
        if (!is_array(value: $value)) {
            return [];
        }
        $records = [];
        foreach ($value as $itemKey => $item) {
            if (is_array(value: $item)) {
                $records[$itemKey] = new self(fields: $item);
            }
        }

        return $records;
    }
}
