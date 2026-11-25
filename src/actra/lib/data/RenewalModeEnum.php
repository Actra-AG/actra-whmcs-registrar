<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\data;

/**
 * Renewal mode of a domain at the Actra registrar API: "expire" lets the domain end on its deletion date instead of
 * renewing it, "renew" renews it automatically.
 */
enum RenewalModeEnum: string
{
    case RENEW = 'renew';
    case EXPIRE = 'expire';
}
