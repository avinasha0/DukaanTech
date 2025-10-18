<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'device_id',
        'order_id',
        'invoice_no',
        'sub_total',
        'tax_total',
        'discount_total',
        'round_off',
        'net_total',
        'state',
    ];

    protected $casts = [
        'sub_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'round_off' => 'decimal:2',
        'net_total' => 'decimal:2',
        'state' => 'string',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAmountAttribute(): float
    {
        return $this->payments->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return $this->net_total - $this->paid_amount;
    }

    public function isPaid(): bool
    {
        return $this->state === 'PAID';
    }

    public function isOpen(): bool
    {
        return $this->state === 'OPEN';
    }

    public function isVoid(): bool
    {
        return $this->state === 'VOID';
    }
}
