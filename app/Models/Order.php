<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, TenantScoped;

    // Order status constants
    const STATUS_OPEN = 'OPEN';
    const STATUS_CLOSED = 'CLOSED';
    const STATUS_PAID = 'PAID';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'device_id',
        'order_type_id',
        'payment_method',
        'customer_name',
        'customer_phone',
        'customer_address',
        'delivery_address',
        'delivery_fee',
        'special_instructions',
        'mode',
        'table_no',
        'state',
        'source',
        'meta',
        'status',
        'table_id',
    ];

    protected $casts = [
        'mode' => 'string',
        'state' => 'string',
        'meta' => 'array',
        'delivery_fee' => 'decimal:2',
    ];

    protected $appends = ['total'];

    /**
     * Boot method to add validation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Prevent multiple OPEN orders for the same table
            if ($order->mode === 'DINE_IN' && $order->table_id && $order->status === self::STATUS_OPEN) {
                $existingOpenOrder = self::where('table_id', $order->table_id)
                    ->where('status', self::STATUS_OPEN)
                    ->where('tenant_id', $order->tenant_id)
                    ->exists();
                
                if ($existingOpenOrder) {
                    throw new \Exception('Table already has an open order. Please close the current order before placing a new one.');
                }
            }
        });
    }

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

    public function orderType(): BelongsTo
    {
        return $this->belongsTo(OrderType::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function kitchenTickets(): HasMany
    {
        return $this->hasMany(KitchenTicket::class);
    }

    public function bill(): HasOne
    {
        return $this->hasOne(Bill::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function getTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            $subtotal = $item->qty * $item->price;
            $modifierTotal = $item->modifiers->sum('price');
            $tax = ($subtotal + $modifierTotal) * ($item->tax_rate / 100);
            return $subtotal + $modifierTotal + $tax - $item->discount;
        });
    }

    /**
     * Check if order is open
     */
    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    /**
     * Check if order is closed
     */
    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Check if order is paid
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if order is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if order is active (open or closed but not paid/cancelled)
     */
    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_OPEN, self::STATUS_CLOSED]);
    }

    /**
     * Scope for open orders
     */
    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    /**
     * Scope for closed orders
     */
    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    /**
     * Scope for paid orders
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope for active orders (open or closed)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_CLOSED]);
    }
}
