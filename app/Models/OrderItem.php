<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'order_id',
        'item_id',
        'item_variant_id',
        'qty',
        'price',
        'tax_rate',
        'discount',
        'note',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }

    public function modifiers(): HasMany
    {
        return $this->hasMany(OrderItemModifier::class);
    }

    public function kitchenLines(): HasMany
    {
        return $this->hasMany(KitchenLine::class);
    }

    public function getTotalAttribute(): float
    {
        $subtotal = $this->qty * $this->price;
        $modifierTotal = $this->modifiers->sum('price');
        $tax = ($subtotal + $modifierTotal) * ($this->tax_rate / 100);
        return round($subtotal + $modifierTotal + $tax - $this->discount, 2);
    }
}
