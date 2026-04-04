<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\data;

use DateTimeImmutable;

readonly class Host
{
    public function __construct(
        public string $name,
        public string $roid,
        public array $status,
        public array $ipv4List,
        public array $ipv6List,
        public DateTimeImmutable $createdDate
    ) {
    }
}