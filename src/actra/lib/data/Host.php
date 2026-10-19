<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\data;

use DateTimeImmutable;

readonly class Host
{
    public function __construct(
        public string $name,
        /** @var list<string> */
        public array $status,
        /** @var list<string> */
        public array $ipv4List,
        /** @var list<string> */
        public array $ipv6List,
        public ?DateTimeImmutable $createdDate,
    ) {}
}
