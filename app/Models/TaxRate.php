<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class TaxRate extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'tax_group_id',
        'name',
        'code',
        'rate',
        'inclusive',
        'description',
        'calculation_type',
        'fixed_amount',
        'is_compound',
        'applicable_items',
        'applicable_order_types',
        'regional_settings',
        'is_active',
        'effective_from',
        'effective_until',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'inclusive' => 'boolean',
        'calculation_type' => 'string',
        'fixed_amount' => 'decimal:2',
        'is_compound' => 'boolean',
        'applicable_items' => 'array',
        'applicable_order_types' => 'array',
        'regional_settings' => 'array',
        'is_active' => 'boolean',
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function taxGroup(): BelongsTo
    {
        return $this->belongsTo(TaxGroup::class);
    }

    public function itemPrices(): HasMany
    {
        return $this->hasMany(ItemPrice::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('effective_from')->orWhere('effective_from', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('effective_until')->orWhere('effective_until', '>=', $now);
        });
    }

    public function scopeApplicable($query, $itemId = null, $orderType = null, $region = null)
    {
        return $query->active()->valid()->where(function ($q) use ($itemId, $orderType, $region) {
            // Check applicable items
            if ($itemId) {
                $q->where(function ($subQ) use ($itemId) {
                    $subQ->whereNull('applicable_items')
                         ->orWhereJsonContains('applicable_items', $itemId);
                });
            }

            // Check applicable order types
            if ($orderType) {
                $q->where(function ($subQ) use ($orderType) {
                    $subQ->whereNull('applicable_order_types')
                         ->orWhereJsonContains('applicable_order_types', $orderType);
                });
            }

            // Check regional settings
            if ($region) {
                $q->where(function ($subQ) use ($region) {
                    $subQ->whereNull('regional_settings')
                         ->orWhereJsonContains('regional_settings', $region);
                });
            }
        });
    }

    public function calculateTax($amount, $itemId = null, $orderType = null, $region = null): float
    {
        if (!$this->isApplicableTo($itemId, $orderType, $region)) {
            return 0;
        }

        switch ($this->calculation_type) {
            case 'percentage':
                return $amount * ($this->rate / 100);
            case 'fixed_amount':
                return $this->fixed_amount ?? 0;
            default:
                return $amount * ($this->rate / 100);
        }
    }

    public function isApplicableTo($itemId = null, $orderType = null, $region = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check validity period
        $now = Carbon::now();
        if ($this->effective_from && $this->effective_from > $now) {
            return false;
        }
        if ($this->effective_until && $this->effective_until < $now) {
            return false;
        }

        // Check applicable items
        if ($this->applicable_items && $itemId && !in_array($itemId, $this->applicable_items)) {
            return false;
        }

        // Check applicable order types
        if ($this->applicable_order_types && $orderType && !in_array($orderType, $this->applicable_order_types)) {
            return false;
        }

        // Check regional settings
        if ($this->regional_settings && $region && !in_array($region, $this->regional_settings)) {
            return false;
        }

        return true;
    }
}
