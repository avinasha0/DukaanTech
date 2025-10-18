<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'opened_by',
        'opening_float',
        'closed_at',
        'expected_cash',
        'actual_cash',
        'variance',
    ];

    protected $casts = [
        'opening_float' => 'decimal:2',
        'closed_at' => 'datetime',
        'expected_cash' => 'decimal:2',
        'actual_cash' => 'decimal:2',
        'variance' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function isOpen(): bool
    {
        return is_null($this->closed_at);
    }

    public function isClosed(): bool
    {
        return !is_null($this->closed_at);
    }
}
