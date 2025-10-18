<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'description',
        'website',
        'industry',
        'company_size',
        'founded_year',
        'owner_id',
        'status',
        'plan',
        'settings',
        'logo_path',
        'logo_url',
        'contact_info',
        'business_hours',
        'social_media',
        'tax_settings',
        'notification_settings',
        'kot_enabled',
        'kot_settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'contact_info' => 'array',
        'business_hours' => 'array',
        'social_media' => 'array',
        'tax_settings' => 'array',
        'notification_settings' => 'array',
        'kot_enabled' => 'boolean',
        'kot_settings' => 'array',
    ];

    public function outlets(): HasMany
    {
        return $this->hasMany(Outlet::class, 'tenant_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'tenant_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'tenant_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'tenant_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'tenant_id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'tenant_id');
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class, 'tenant_id');
    }

    public function taxRates(): HasMany
    {
        return $this->hasMany(TaxRate::class, 'tenant_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'tenant_id');
    }
}
