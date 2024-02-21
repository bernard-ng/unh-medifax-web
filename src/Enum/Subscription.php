<?php

declare(strict_types=1);


namespace App\Enum;

/**
 * Class Subscription.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum Subscription: string
{
    case FREE = 'free';
    case BASIC = 'basic';
    case PREMIUM = 'premium';
}
