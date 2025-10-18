<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TenantScoped
{
    protected static function bootTenantScoped()
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            \Log::info('TenantScoped global scope applied', [
                'tenant_id_bound' => app()->bound('tenant.id'),
                'tenant_id_value' => app()->bound('tenant.id') ? app('tenant.id') : 'NOT_BOUND'
            ]);
            
            if (app()->bound('tenant.id')) {
                $query->where('tenant_id', app('tenant.id'));
            } else {
                \Log::warning('TenantScoped: tenant.id not bound, skipping global scope');
            }
        });

        static::creating(function ($model) {
            \Log::info('TenantScoped creating event', [
                'model_class' => get_class($model),
                'tenant_id_bound' => app()->bound('tenant.id'),
                'tenant_id_value' => app()->bound('tenant.id') ? app('tenant.id') : 'NOT_BOUND',
                'current_tenant_id' => $model->tenant_id ?? 'NOT_SET'
            ]);
            
            if (app()->bound('tenant.id')) {
                $model->tenant_id ??= app('tenant.id');
                \Log::info('TenantScoped: tenant_id set', ['tenant_id' => $model->tenant_id]);
            } else {
                \Log::error('TenantScoped: tenant.id not bound during model creation', [
                    'model_class' => get_class($model),
                    'model_data' => $model->getAttributes()
                ]);
            }
        });
    }
}
