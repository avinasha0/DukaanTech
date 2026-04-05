<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrRazorpayCheckout extends Model
{
    protected $fillable = [
        'tenant_id',
        'payload',
        'amount_paise',
        'currency',
        'razorpay_order_id',
        'expires_at',
        'completed_at',
        'order_id',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'expires_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
