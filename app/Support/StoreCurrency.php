<?php

namespace App\Support;

use App\Models\Setting;

final class StoreCurrency
{
    public const ALLOWED = ['USD', 'PKR'];

    /** ISO 4217 code used for storefront, cart, checkout, and new orders. */
    public static function code(): string
    {
        $v = strtoupper(trim((string) Setting::get('store_currency', 'USD')));

        return in_array($v, self::ALLOWED, true) ? $v : 'USD';
    }

    /** BCP 47 locale for Intl formatting. */
    public static function locale(?string $currencyCode = null): string
    {
        $c = strtoupper((string) ($currencyCode ?: self::code()));

        return $c === 'PKR' ? 'en-PK' : 'en-US';
    }
}
