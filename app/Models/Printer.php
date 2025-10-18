<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Printer extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'name',
        'iface',
        'address',
        'profile',
    ];

    protected $casts = [
        'iface' => 'string',
        'profile' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function kotRoutes(): HasMany
    {
        return $this->hasMany(KotRoute::class);
    }
}
