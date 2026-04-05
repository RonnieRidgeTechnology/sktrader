<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_COD = 'cod';
    public const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    public const PAYMENT_JAZZCASH = 'jazzcash';

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'number',
        'guest_email',
        'guest_name',
        'guest_phone',
        'status',
        'payment_method',
        'payment_proof',
        'payment_reference',
        'courier_name',
        'tracking_number',
        'tracking_url',
        'shipping_address',
        'subtotal',
        'total',
        'currency',
        'order_notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->orderBy('id');
    }

    public static function generateNumber(): string
    {
        $prefix = 'ORD-';
        $last = static::query()->orderByDesc('id')->first();
        $next = $last ? ((int) preg_replace('/^ORD-/', '', $last->number)) + 1 : 1;
        return $prefix . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
}
