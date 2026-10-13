<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\settings;

enum RegistrationStatusEnum: string
{
    case STATUS_ACTIVE = 'active';
    case STATUS_ARCHIVED = 'archived';
    case STATUS_DELETED = 'deleted';
    case STATUS_EXPIRED = 'expired';
    case STATUS_INACTIVE = 'inactive';
    case STATUS_SUSPENDED = 'suspended';
    case STATUS_PENDING_DELETE = 'pendingdelete';
}
