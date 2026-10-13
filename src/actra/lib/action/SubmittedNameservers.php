<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

/**
 * Nameservers submitted by WHMCS in the parameters ns1_punycode … ns5_punycode.
 */
final class SubmittedNameservers
{
    /**
     * @param array<array-key, mixed> $params WHMCS module parameters
     *
     * @return list<string> lowercase ASCII names in the submitted order, without empty entries and duplicates
     */
    public static function fromParams(array $params): array
    {
        $nameservers = [];
        for ($i = 1; $i <= 5; $i++) {
            $value = $params['ns' . $i . '_punycode'] ?? null;
            if (!is_string(value: $value)) {
                continue;
            }
            $nameserver = strtolower(string: trim(string: $value));
            if ($nameserver === '' || in_array(needle: $nameserver, haystack: $nameservers, strict: true)) {
                continue;
            }
            $nameservers[] = $nameserver;
        }

        return $nameservers;
    }

    /**
     * Whether both lists contain the same nameservers, in any order.
     *
     * @param list<string> $first
     * @param list<string> $second
     */
    public static function equal(array $first, array $second): bool
    {
        $first = array_unique(array: $first);
        $second = array_unique(array: $second);
        sort(array: $first);
        sort(array: $second);

        return $first === $second;
    }
}
