<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAttempt extends Model
{
    protected $fillable = ['order_id', 'provider', 'external_id', 'status', 'amount', 'currency', 'idempotency_key', 'request_payload', 'response_payload', 'failure_message', 'processed_at'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'request_payload' => 'array', 'response_payload' => 'array', 'processed_at' => 'datetime'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
