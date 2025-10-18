<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function index()
    {
        $tenant = app('tenant');
        $devices = Device::where('tenant_id', $tenant->id)
            ->with('outlet')
            ->orderBy('name')
            ->get();
        
        // Check if this is an API request
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json($devices);
        }
        
        // Return view for web requests
        $outlets = \App\Models\Outlet::where('tenant_id', $tenant->id)->get();
        return view('tenant.devices.index', compact('tenant', 'outlets', 'devices'))->with('tenant', $tenant);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:POS,KITCHEN,TOKEN',
                'outlet_id' => 'required|exists:outlets,id',
            ]);

            $tenant = app('tenant');
            
            $device = Device::create([
                'tenant_id' => $tenant->id,
                'outlet_id' => $data['outlet_id'],
                'name' => $data['name'],
                'type' => $data['type'],
                'api_key' => strtolower($data['type']) . '_' . Str::random(16),
            ]);

            return response()->json($device, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating device: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return response()->json(['message' => 'Device deleted successfully']);
    }
}
