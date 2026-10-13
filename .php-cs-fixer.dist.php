<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Config\RuleCustomisationPolicyInterface;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

/** @var array<string, bool|array<string, mixed>> $rules */
$rules = require __DIR__ . '/vendor/actra/coding-standard/config/php-cs-fixer.php';
/**
 * @var Closure(
 *     string $copyright,
 *     string $license,
 *     list<array{path: string, license: string, contains?: string}> $thirdParty,
 * ): array{
 *     rule: array{header: string, comment_type: string, location: string, separate: string},
 *     policy: RuleCustomisationPolicyInterface,
 * } $headerFactory
 */
$headerFactory = require __DIR__ . '/vendor/actra/coding-standard/config/php-cs-fixer-header.php';
$header = $headerFactory(
    copyright: 'Actra AG - https://www.actra.ch',
    license: 'MIT',
    thirdParty: [],
);

// The module supports PHP 8.3 (see AGENTS.md, "Deviations from the global standard")
return (new Config())
    ->setRiskyAllowed(true)
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        ...$rules,
        '@PHP8x5Migration' => false,
        '@PHP8x5Migration:risky' => false,
        '@PHP8x3Migration' => true,
        '@PHP8x3Migration:risky' => true,
        'header_comment' => $header['rule'],
    ])
    ->setRuleCustomisationPolicy($header['policy'])
    ->setFinder(
        Finder::create()
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
            ->append([__FILE__]),
    );
