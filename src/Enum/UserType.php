<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Class UserType.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum UserType: string
{
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';

}
