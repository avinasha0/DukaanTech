<?php

namespace App\Services;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public function log(string $action, Model $model, array $oldValues = [], array $newValues = []): Audit
    {
        return Audit::create([
            'tenant_id' => app('tenant.id'),
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function logCreate(Model $model): Audit
    {
        return $this->log('created', $model, [], $model->getAttributes());
    }

    public function logUpdate(Model $model, array $oldValues, array $newValues): Audit
    {
        return $this->log('updated', $model, $oldValues, $newValues);
    }

    public function logDelete(Model $model): Audit
    {
        return $this->log('deleted', $model, $model->getAttributes(), []);
    }

    public function getAuditTrail(Model $model): \Illuminate\Database\Eloquent\Collection
    {
        return Audit::where('tenant_id', app('tenant.id'))
            ->where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserActivity(int $userId, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return Audit::where('tenant_id', app('tenant.id'))
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRecentActivity(int $limit = 100): \Illuminate\Database\Eloquent\Collection
    {
        return Audit::where('tenant_id', app('tenant.id'))
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
