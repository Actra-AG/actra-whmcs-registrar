<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\action;

use DateTimeImmutable;

class Log
{
    public static function execute(
        string $className,
        array $params
    ): void {
        array_walk_recursive(
            array: $params,
            callback: function (&$value, $key) {
                if ($key === 'APIKey') {
                    $value = '***';
                }
            }
        );
        $handle = fopen(
            filename: dirname(path: __FILE__) . '/../log/actra.log',
            mode: 'a'
        );
        fwrite(
            stream: $handle,
            data: implode(
                separator: PHP_EOL,
                array: [
                    '',
                    "================= $className =================",
                    (new DateTimeImmutable())->format(format: 'Y-m-d H:i:s'),
                    '',
                    print_r(
                        value: $params,
                        return: true
                    )
                ]
            )
        );
        fclose(stream: $handle);
    }
}