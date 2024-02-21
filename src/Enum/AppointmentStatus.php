<?php

declare(strict_types=1);

namespace App\Enum;

use phpDocumentor\Reflection\Types\Self_;

/**
 * Class AppointmentStatus.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum AppointmentStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case CANCELED = 'canceled';
}
