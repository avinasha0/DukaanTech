<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'buy_quantity',
        'get_quantity',
        'valid_from',
        'valid_until',
        'is_active',
        'applicable_items',
        'applicable_order_types',
        'usage_limit',
        'usage_count',
        'description',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'buy_quantity' => 'integer',
        'get_quantity' => 'integer',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'applicable_items' => 'array',
        'applicable_order_types' => 'array',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
        });
    }

    public function scopeAvailable($query)
    {
        return $query->active()->valid()->where(function ($q) {
            $q->whereNull('usage_limit')->orWhereRaw('usage_count < usage_limit');
        });
    }

    public function isApplicableToItem($itemId, $orderType = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check validity period
        $now = Carbon::now();
        if ($this->valid_from && $this->valid_from > $now) {
            return false;
        }
        if ($this->valid_until && $this->valid_until < $now) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        // Check applicable items (only if itemId is provided and applicable_items is set)
        if ($this->applicable_items && $itemId && !in_array($itemId, $this->applicable_items)) {
            return false;
        }

        // Check applicable order types
        if ($this->applicable_order_types && $orderType && !in_array($orderType, $this->applicable_order_types)) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($amount, $itemId = null, $orderType = null): float
    {
        if (!$this->isApplicableToItem($itemId, $orderType)) {
            return 0;
        }

        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            return 0;
        }

        $discount = 0;
        switch ($this->type) {
            case 'percentage':
                $discount = $amount * ($this->value / 100);
                break;
            case 'fixed_amount':
                $discount = $this->value;
                break;
            case 'buy_x_get_y':
                // This would need item quantity context
                $discount = 0; // Implement based on business logic
                break;
        }

        // Apply maximum discount limit
        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }

        return min($discount, $amount); // Can't discount more than the amount
    }

    public function incrementUsage()
    {
        $this->increment('usage_count');
    }
}