<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, TenantScoped, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'sku',
        'price',
        'description',
        'is_veg',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'is_veg' => 'boolean',
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ItemVariant::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ItemPrice::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function modifierGroups(): BelongsToMany
    {
        return $this->belongsToMany(ModifierGroup::class, 'item_modifier_group', 'item_id', 'modifier_group_id')
            ->withPivot('tenant_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function priceFor(?ItemVariant $variant = null, ?Outlet $outlet = null): float
    {
        $outletId = $outlet ? $outlet->id : app('tenant.outlet_id');
        
        $price = $this->prices()
            ->where('outlet_id', $outletId)
            ->where('item_variant_id', $variant?->id)
            ->where('is_available', true)
            ->first();

        // If no outlet-specific price found, fall back to base price
        if (!$price) {
            return (float) $this->price;
        }

        return $price->price;
    }
}
