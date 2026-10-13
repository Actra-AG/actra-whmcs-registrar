<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit;

use AppendIterator;
use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class StrictTypesTest extends TestCase
{
    // Tracked directories with PHP code (whitelist .gitignore), relative to the project root
    private const array DIRECTORIES = ['src', 'tests'];

    public function testEveryPhpFileDeclaresStrictTypes(): void
    {
        $root = dirname(path: __DIR__, levels: 2);
        $files = new AppendIterator();
        // Root files only, the root also contains untracked local directories (vendor, .idea, …)
        $files->append(iterator: new FilesystemIterator(directory: $root));
        foreach (self::DIRECTORIES as $directory) {
            $files->append(iterator: new RecursiveIteratorIterator(
                iterator: new RecursiveDirectoryIterator(
                    directory: $root . '/' . $directory,
                    flags: FilesystemIterator::SKIP_DOTS,
                ),
            ));
        }
        $missing = [];
        foreach ($files as $file) {
            if (!$file instanceof SplFileInfo || !$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }
            $code = file_get_contents(filename: $file->getPathname());
            if ($code === false || preg_match(pattern: '/^declare\(strict_types=1\);$/m', subject: $code) !== 1) {
                $missing[] = $file->getPathname();
            }
        }

        $this->assertSame([], $missing);
    }
}
