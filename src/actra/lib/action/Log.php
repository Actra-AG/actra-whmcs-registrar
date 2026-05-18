<?php
/**
 * @copyright Actra AG - https://www.actra.ch
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
        $filePath = dirname(path: __FILE__) . '/../log/actra.log';
        $dirPath = dirname(path: $filePath);
        if (!is_dir(filename: $dirPath)) {
            mkdir(
                directory: $dirPath,
                recursive: true
            );
        }
        $handle = fopen(
            filename: $filePath,
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