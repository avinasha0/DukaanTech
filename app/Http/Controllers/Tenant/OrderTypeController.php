<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\OrderType;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{
    public function index()
    {
        $orderTypes = OrderType::where('tenant_id', app('tenant.id'))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        return response()->json($orderTypes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:order_types,slug',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'integer|min:0',
        ]);

        $data['tenant_id'] = app('tenant.id');
        $data['color'] = $data['color'] ?? '#3B82F6';
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $orderType = OrderType::create($data);

        return response()->json($orderType, 201);
    }

    public function show($tenant, OrderType $orderType)
    {
        return response()->json($orderType);
    }

    public function update(Request $request, $tenant, OrderType $orderType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:order_types,slug,' . $orderType->id,
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $orderType->update($data);

        return response()->json($orderType);
    }

    public function destroy($tenant, OrderType $orderType)
    {
        // Check if order type has orders
        if ($orderType->orders()->count() > 0) {
            return response()->json(['error' => 'Cannot delete order type with existing orders'], 400);
        }

        $orderType->delete();

        return response()->json(['message' => 'Order type deleted successfully']);
    }
}