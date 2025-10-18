<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantTable extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'name',
        'status',
        'capacity',
        'shape',
        'type',
        'description',
        'total_amount',
        'orders',
        'is_active',
        'current_order_id',
    ];

    protected $casts = [
        'orders' => 'array',
        'total_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'occupied' => 'red',
            'reserved' => 'yellow',
            'free' => 'green',
            default => 'gray'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'occupied' => 'Occupied',
            'reserved' => 'Reserved',
            'free' => 'Available',
            default => 'Unknown'
        };
    }

    /**
     * Get the current order for this table
     */
    public function currentOrder()
    {
        return $this->belongsTo(Order::class, 'current_order_id');
    }

    /**
     * Get the open order for this table
     */
    public function openOrder()
    {
        return $this->hasOne(Order::class, 'table_id')->where('status', 'OPEN');
    }

    /**
     * Get all orders for this table
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    /**
     * Scope to include open order counts
     */
    public function scopeWithOpenOrders($query)
    {
        return $query->withCount(['orders as open_orders_count' => function ($q) {
            $q->where('status', 'OPEN');
        }]);
    }

    /**
     * Scope to include active order details
     */
    public function scopeWithActiveOrder($query)
    {
        return $query->with(['orders' => function($q) {
            $q->where('status', 'OPEN')->latest()->limit(1);
        }]);
    }

    /**
     * Computed accessor to determine if table is occupied
     */
    public function getIsOccupiedAttribute()
    {
        return $this->open_orders_count > 0;
    }

    /**
     * Get status text based on open orders
     */
    public function getComputedStatusTextAttribute()
    {
        return $this->is_occupied ? 'Occupied' : 'Available';
    }

    /**
     * Get status color based on open orders
     */
    public function getComputedStatusColorAttribute()
    {
        return $this->is_occupied ? 'red' : 'green';
    }

    /**
     * Sync table status based on active orders
     */
    public function syncStatus(): void
    {
        // Only consider OPEN orders as keeping the table occupied
        $openOrders = $this->orders()
            ->where('status', 'OPEN')
            ->get();

        \Log::info('Syncing table status', [
            'table_id' => $this->id,
            'table_name' => $this->name,
            'current_status' => $this->status,
            'open_orders_count' => $openOrders->count(),
            'open_orders' => $openOrders->map(function($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'created_at' => $order->created_at
                ];
            })
        ]);

        if ($openOrders->count() > 0) {
            $this->update(['status' => 'occupied']);
            \Log::info('Table marked as occupied', ['table_id' => $this->id]);
        } else {
            $this->update([
                'status' => 'free',
                'current_order_id' => null,
            ]);
            \Log::info('Table marked as free', ['table_id' => $this->id]);
        }
    }

    /**
     * Get the current active order for this table
     */
    public function getActiveOrder()
    {
        return $this->orders()
            ->where('status', 'OPEN')
            ->latest()
            ->first();
    }
}