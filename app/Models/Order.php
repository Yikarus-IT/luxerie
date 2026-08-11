<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['number', 'cart_id', 'checkout_token', 'status', 'payment_status', 'fulfillment_status', 'customer_name', 'customer_email', 'customer_phone', 'shipping_address_line_1', 'shipping_address_line_2', 'shipping_neighborhood', 'shipping_city', 'shipping_state', 'shipping_postal_code', 'shipping_country', 'customer_notes', 'subtotal', 'shipping_total', 'discount_total', 'total', 'currency', 'reserved_until', 'paid_at'];

    protected function casts(): array
    {
        return ['subtotal' => 'decimal:2', 'shipping_total' => 'decimal:2', 'discount_total' => 'decimal:2', 'total' => 'decimal:2', 'reserved_until' => 'datetime', 'paid_at' => 'datetime'];
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function paymentAttempts(): HasMany
    {
        return $this->hasMany(PaymentAttempt::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(InventoryReservation::class);
    }
}
