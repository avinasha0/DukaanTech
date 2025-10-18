<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class KotSettingsController extends Controller
{
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'enabled' => 'required|boolean',
        ]);

        $tenantId = app('tenant.id');
        if (!$tenantId) {
            // For main dashboard, get user's tenant
            $tenantId = auth()->user()->tenant_id;
        }
        
        $tenant = Account::find($tenantId);
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        $tenant->update(['kot_enabled' => $data['enabled']]);

        return response()->json([
            'message' => $data['enabled'] ? 'KOT functionality enabled' : 'KOT functionality disabled',
            'kot_enabled' => $tenant->kot_enabled
        ]);
    }

    public function getStatus()
    {
        $tenantId = app('tenant.id');
        if (!$tenantId) {
            // For main dashboard, get user's tenant
            $tenantId = auth()->user()->tenant_id;
        }
        
        $tenant = Account::find($tenantId);
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        return response()->json([
            'kot_enabled' => $tenant->kot_enabled,
            'kot_settings' => $tenant->kot_settings
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'kot_settings' => 'nullable|array',
        ]);

        $tenantId = app('tenant.id');
        if (!$tenantId) {
            // For main dashboard, get user's tenant
            $tenantId = auth()->user()->tenant_id;
        }
        
        $tenant = Account::find($tenantId);
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        $tenant->update(['kot_settings' => $data['kot_settings'] ?? []]);

        return response()->json([
            'message' => 'KOT settings updated successfully',
            'kot_settings' => $tenant->kot_settings
        ]);
    }
}