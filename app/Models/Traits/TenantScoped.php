<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TenantScoped
{
    protected static function bootTenantScoped()
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            // tenant.id is always "bound" via AppServiceProvider singleton (default null).
            // Only filter when a real tenant id is set — otherwise WHERE tenant_id = NULL matches nothing.
            $tenantId = app()->bound('tenant.id') ? app('tenant.id') : null;

            if ($tenantId !== null && $tenantId !== '') {
                $query->where('tenant_id', $tenantId);
            }
        });

        static::creating(function ($model) {
            $tenantId = app()->bound('tenant.id') ? app('tenant.id') : null;
            if ($tenantId !== null && $tenantId !== '' && $model->tenant_id === null) {
                $model->tenant_id = $tenantId;
            } elseif ($model->tenant_id === null) {
                \Log::error('TenantScoped: no tenant id during model creation', [
                    'model_class' => get_class($model),
                    'model_data' => $model->getAttributes(),
                ]);
            }
        });
    }
}
