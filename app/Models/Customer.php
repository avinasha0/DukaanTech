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
        return static::firstOrCreate(
            [
                'tenant_id' => $tenantId,
                'phone' => $phone,
            ],
            array_merge([
                'name' => $additionalData['name'] ?? '',
                'email' => $additionalData['email'] ?? null,
                'address' => $additionalData['address'] ?? null,
            ], $additionalData)
        );
    }
}
