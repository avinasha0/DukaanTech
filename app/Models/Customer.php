<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'name',
        'phone',
        'email',
        'address',
        'date_of_birth',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    protected $appends = ['total_orders', 'total_spent'];

    /**
     * Get the tenant that owns the customer
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    /**
     * Get all orders for this customer
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get total number of orders for this customer
     */
    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    /**
     * Get total amount spent by this customer
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->orders()->get()->sum('total');
    }

    /**
     * Scope to search customers by name or phone
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * Find or create customer by phone
     */
    public static function findOrCreateByPhone($tenantId, $phone, $additionalData = [])
    {
        // If phone is provided, try to find by phone first
        if ($phone) {
            $customer = static::where('tenant_id', $tenantId)
                ->where('phone', $phone)
                ->first();
            
            if ($customer) {
                // Update existing customer with new data if provided
                $customer->update(array_filter($additionalData, function($value) {
                    return $value !== null && $value !== '';
                }));
                return $customer;
            }
        }
        
        // If no phone or customer not found by phone, try to find by name
        if (isset($additionalData['name']) && $additionalData['name']) {
            $customer = static::where('tenant_id', $tenantId)
                ->where('name', $additionalData['name'])
                ->first();
            
            if ($customer) {
                // Update existing customer with new data if provided
                $customer->update(array_filter($additionalData, function($value) {
                    return $value !== null && $value !== '';
                }));
                return $customer;
            }
        }
        
        // Create new customer
        return static::create(array_merge([
            'tenant_id' => $tenantId,
            'name' => $additionalData['name'] ?? '',
            'phone' => $phone,
            'email' => $additionalData['email'] ?? null,
            'address' => $additionalData['address'] ?? null,
        ], array_filter($additionalData, function($value) {
            return $value !== null && $value !== '';
        })));
    }
}
