<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrderType extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Slugs are globally unique — generate one that is not taken.
     */
    public static function uniqueSlug(string $baseSlug, int $tenantId): string
    {
        $slug = $baseSlug;
        $suffix = 1;
        while (static::withoutGlobalScopes()->where('slug', $slug)->exists()) {
            $suffix++;
            $slug = Str::slug("{$baseSlug}-{$tenantId}-{$suffix}");
        }

        return $slug;
    }

    /**
     * Ensure the tenant has at least one active order type usable from the public QR menu
     * (dine-in or takeaway; delivery-only setups are supplemented with dine-in).
     */
    public static function ensureQrOrderingTypesExist(int $tenantId): void
    {
        $anyRows = static::withoutGlobalScopes()->where('tenant_id', $tenantId)->exists();
        $active = static::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $eligible = static::filterQrEligible(static::attachQrModes($active));
        if ($eligible->isNotEmpty()) {
            return;
        }

        if (! $anyRows) {
            static::seedDefaultOrderTypesForTenant($tenantId);

            return;
        }

        static::createDineInFallback($tenantId);
    }

    /**
     * @param  Collection<int, self>  $orderTypes
     * @return Collection<int, self>
     */
    public static function attachQrModes(Collection $orderTypes): Collection
    {
        return $orderTypes->map(function (self $ot) {
            $slug = strtolower((string) ($ot->slug ?? ''));
            $name = strtolower((string) ($ot->name ?? ''));
            $h = $slug.' '.$name;
            if (str_contains($h, 'deliver')) {
                $ot->qr_mode = 'DELIVERY';
            } elseif (str_contains($h, 'take') || str_contains($h, 'pickup') || str_contains($h, 'away') || str_contains($h, 'pick')) {
                $ot->qr_mode = 'TAKEAWAY';
            } else {
                $ot->qr_mode = 'DINE_IN';
            }

            return $ot;
        });
    }

    /**
     * @param  Collection<int, self>  $orderTypes
     * @return Collection<int, self>
     */
    public static function filterQrEligible(Collection $orderTypes): Collection
    {
        return $orderTypes->filter(function (self $ot) {
            return in_array($ot->qr_mode ?? '', ['DINE_IN', 'TAKEAWAY'], true);
        })->values();
    }

    protected static function seedDefaultOrderTypesForTenant(int $tenantId): void
    {
        $defaults = [
            ['name' => 'Dine In', 'base_slug' => 'dine-in', 'color' => '#10B981', 'sort_order' => 1],
            ['name' => 'Delivery', 'base_slug' => 'delivery', 'color' => '#F59E0B', 'sort_order' => 2],
            ['name' => 'Takeaway', 'base_slug' => 'takeaway', 'color' => '#3B82F6', 'sort_order' => 3],
        ];

        foreach ($defaults as $row) {
            static::withoutGlobalScopes()->create([
                'tenant_id' => $tenantId,
                'name' => $row['name'],
                'slug' => static::uniqueSlug($row['base_slug'], $tenantId),
                'color' => $row['color'],
                'is_active' => true,
                'sort_order' => $row['sort_order'],
            ]);
        }
    }

    protected static function createDineInFallback(int $tenantId): void
    {
        static::withoutGlobalScopes()->create([
            'tenant_id' => $tenantId,
            'name' => 'Dine In',
            'slug' => static::uniqueSlug('dine-in', $tenantId),
            'color' => '#10B981',
            'is_active' => true,
            'sort_order' => 0,
        ]);
    }
}