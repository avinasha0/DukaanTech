<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RestaurantTableController extends Controller
{
    public function index(): JsonResponse
    {
        $tenant = app('tenant');
        $tables = RestaurantTable::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($tables);
    }

    public function store(Request $request): JsonResponse
    {
        $tenant = app('tenant');
        
        $request->validate([
            'name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:20',
            'shape' => 'required|in:round,rectangular,oval,square',
            'type' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        // Check if table name already exists for this tenant/outlet
        $existingTable = RestaurantTable::where('tenant_id', $tenant->id)
            ->where('outlet_id', $tenant->outlets->first()->id ?? 1)
            ->where('name', $request->name)
            ->first();

        if ($existingTable) {
            return response()->json(['error' => 'Table name already exists'], 422);
        }

        $table = RestaurantTable::create([
            'tenant_id' => $tenant->id,
            'outlet_id' => $tenant->outlets->first()->id ?? 1,
            'name' => $request->name,
            'capacity' => $request->capacity,
            'shape' => $request->shape,
            'type' => $request->type ?? 'standard',
            'description' => $request->description,
            'status' => 'free',
            'total_amount' => 0.00,
            'orders' => [],
            'is_active' => true,
        ]);

        return response()->json($table, 201);
    }

    public function show(RestaurantTable $table): JsonResponse
    {
        $this->authorize('view', $table);
        return response()->json($table);
    }

    public function update(Request $request, RestaurantTable $table): JsonResponse
    {
        $this->authorize('update', $table);
        
        $request->validate([
            'name' => 'sometimes|string|max:50',
            'capacity' => 'sometimes|integer|min:1|max:20',
            'shape' => 'sometimes|in:round,rectangular,oval,square',
            'type' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
            'status' => 'sometimes|in:free,occupied,reserved',
            'total_amount' => 'sometimes|numeric|min:0',
        ]);

        $table->update($request->only([
            'name', 'capacity', 'shape', 'type', 'description', 
            'status', 'total_amount'
        ]));

        return response()->json($table);
    }

    public function destroy(RestaurantTable $table): JsonResponse
    {
        $this->authorize('delete', $table);
        
        // Soft delete by setting is_active to false
        $table->update(['is_active' => false]);
        
        return response()->json(['message' => 'Table deleted successfully']);
    }

    public function updateStatus(Request $request, RestaurantTable $table): JsonResponse
    {
        $this->authorize('update', $table);
        
        $request->validate([
            'status' => 'required|in:free,occupied,reserved',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        $table->update([
            'status' => $request->status,
            'total_amount' => $request->total_amount ?? $table->total_amount,
        ]);

        return response()->json($table);
    }
}