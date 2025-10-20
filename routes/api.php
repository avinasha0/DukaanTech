<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API endpoints for POS register (completely bypass web middleware)
Route::group(['prefix' => '{tenant}/public', 'middleware' => []], function () {
    Route::get('/categories', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        return \App\Models\Category::where('tenant_id', $account->id)->get();
    });
    
    Route::get('/items', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        return \App\Models\Item::where('tenant_id', $account->id)->get();
    });
    
    // Item management API routes (bypass CSRF)
    Route::post('/items', function ($tenant, Request $request) {
        // Set tenant context
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);
        
        return app(\App\Http\Controllers\Tenant\Catalog\ItemController::class)->store($request);
    });
    
    Route::put('/items/{item}', function ($tenant, $itemId, Request $request) {
        // Set tenant context
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);
        
        $item = \App\Models\Item::where('tenant_id', $account->id)->findOrFail($itemId);
        return app(\App\Http\Controllers\Tenant\Catalog\ItemController::class)->update($request, $tenant, $item);
    });
    
    Route::delete('/items/{item}', function ($tenant, $itemId, Request $request) {
        // Set tenant context
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);
        
        $item = \App\Models\Item::where('tenant_id', $account->id)->findOrFail($itemId);
        return app(\App\Http\Controllers\Tenant\Catalog\ItemController::class)->destroy($tenant, $item);
    });
    
    
    Route::get('/order-types', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        return \App\Models\OrderType::where('tenant_id', $account->id)->get();
    });
    
    Route::get('/devices', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        $outletId = request('outlet_id');
        $query = \App\Models\Device::where('tenant_id', $account->id);
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }
        return $query->with('outlet')->get();
    });
    
    Route::get('/dashboard/shift/current', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        $outletId = request('outlet_id', 1);
        $shift = \App\Models\Shift::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->whereNull('closed_at')
            ->first();
            
        if (!$shift) {
            return response()->json([
                'shift' => null,
                'has_shift' => false,
                'summary' => [
                    'total_sales' => 0,
                    'total_orders' => 0,
                    'cash_sales' => 0,
                    'card_sales' => 0,
                    'upi_sales' => 0,
                    'opening_float' => 0
                ]
            ]);
        }
        
        // Calculate summary using ShiftService
        $shiftService = new \App\Services\ShiftService();
        $summary = $shiftService->getShiftSummary($shift);
        
        return response()->json([
            'shift' => $shift,
            'has_shift' => true,
            'summary' => $summary
        ]);
    });
    
    Route::post('/shifts/open', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        $data = request()->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'opening_float' => 'nullable|numeric|min:0',
        ]);
        
        // Check if there's already an open shift
        $existingShift = \App\Models\Shift::where('tenant_id', $account->id)
            ->where('outlet_id', $data['outlet_id'])
            ->whereNull('closed_at')
            ->first();
            
        if ($existingShift) {
            return response()->json(['error' => 'There is already an open shift'], 400);
        }
        
        // Find a user to assign as opened_by
        $user = \App\Models\User::where('tenant_id', $account->id)->first();
        if (!$user) {
            return response()->json(['error' => 'No users found for this tenant'], 400);
        }
        
        $shift = \App\Models\Shift::create([
            'tenant_id' => $account->id,
            'outlet_id' => $data['outlet_id'],
            'opening_float' => $data['opening_float'] ?? 0,
            'opened_by' => $user->id,
        ]);
        
        return response()->json($shift, 201);
    });
    
    Route::get('/kot/status', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        $outletId = request('outlet_id', 1);
        $status = request('status', 'SENT');
        $kots = \App\Models\KitchenTicket::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->where('status', $status)
            ->with(['order', 'device'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($kots);
    });
    
    // QR Order Creation (bypasses CSRF)
    Route::post('/qr-order/create', [App\Http\Controllers\QROrderApiController::class, 'createOrder']);
    
    // Super simple QR order creation - minimal validation
    Route::post('/simple-order', function ($tenant, Request $request) {
        try {
            $account = \App\Models\Account::where('slug', $tenant)->first();
            if (!$account) {
                return response()->json(['error' => 'Restaurant not found'], 404);
            }

            $data = $request->all();
            
            // Create order with minimal validation
            // Get the first available order type for this tenant, or create default ones
            $orderType = \App\Models\OrderType::where('tenant_id', $account->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->first();
            
            if (!$orderType) {
                // Create default order types if none exist
                $orderType = \App\Models\OrderType::create([
                    'tenant_id' => $account->id,
                    'name' => 'Dine In',
                    'slug' => 'dine-in',
                    'color' => '#10B981',
                    'is_active' => true,
                    'sort_order' => 1,
                ]);
            }

            $order = \App\Models\Order::create([
                'tenant_id' => $account->id,
                'outlet_id' => $data['outlet_id'] ?? 1,
                'order_type_id' => $data['order_type_id'] ?? $orderType->id,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'customer_name' => $data['customer_name'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,
                'mode' => $data['mode'] ?? 'DINE_IN',
                'table_no' => $data['table_no'] ?? null,
                'state' => 'NEW',
                'source' => 'mobile_qr', // Add source indicator
            ]);

            // Add items
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $item = \App\Models\Item::find($itemData['item_id']);
                    if ($item) {
                        \App\Models\OrderItem::create([
                            'tenant_id' => $account->id,
                            'order_id' => $order->id,
                            'item_id' => $item->id,
                            'qty' => $itemData['qty'] ?? 1,
                            'price' => $item->price,
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully!',
                'order_id' => $order->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    });
    
    Route::post('/orders', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        // Set tenant context
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);
        
        // Resolve device from API key
        $apiKey = request()->header('X-Device-Key');
        $deviceId = null;
        if ($apiKey) {
            $device = \App\Models\Device::where('tenant_id', $account->id)
                ->where('api_key', $apiKey)
                ->first();
            if ($device) {
                $deviceId = $device->id;
                app()->instance('device.id', $device->id);
                app()->instance('device.outlet_id', $device->outlet_id);
            }
        }
        
        $data = request()->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'order_type_id' => 'required|exists:order_types,id',
            'payment_method' => 'required|string',
            'table_no' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'delivery_address' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric|min:0',
            'special_instructions' => 'nullable|string',
            'mode' => 'required|in:DINE_IN,DELIVERY,PICKUP,TAKEAWAY',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);
        
        $order = \App\Models\Order::create([
            'tenant_id' => $account->id,
            'outlet_id' => $data['outlet_id'],
            'device_id' => $deviceId,
            'order_type_id' => $data['order_type_id'],
            'payment_method' => $data['payment_method'],
            'table_no' => $data['table_no'],
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_address' => $data['customer_address'] ?? null,
            'delivery_address' => $data['delivery_address'],
            'delivery_fee' => $data['delivery_fee'] ?? 0,
            'special_instructions' => $data['special_instructions'],
            'mode' => $data['mode'],
            'state' => 'NEW',
        ]);
        
        // Add order items
        foreach ($data['items'] as $itemData) {
            $item = \App\Models\Item::find($itemData['item_id']);
            \App\Models\OrderItem::create([
                'tenant_id' => $account->id,
                'order_id' => $order->id,
                'item_id' => $itemData['item_id'],
                'qty' => $itemData['qty'],
                'price' => $item->price,
            ]);
        }
        
        return response()->json($order, 201);
    });
    
    Route::post('/orders/{order}/kot', function ($tenant, $orderId) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        // Set tenant context
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);
        
        // Resolve device from API key
        $apiKey = request()->header('X-Device-Key');
        $deviceId = null;
        if ($apiKey) {
            $device = \App\Models\Device::where('tenant_id', $account->id)
                ->where('api_key', $apiKey)
                ->first();
            if ($device) {
                $deviceId = $device->id;
                app()->instance('device.id', $device->id);
                app()->instance('device.outlet_id', $device->outlet_id);
            }
        }
        
        $order = \App\Models\Order::where('tenant_id', $account->id)->findOrFail($orderId);
        
        $data = request()->validate([
            'station' => 'required|string|max:255',
        ]);
        
        $kot = $order->kitchenTickets()->create([
            'tenant_id' => $account->id,
            'device_id' => $deviceId,
            'station' => $data['station'],
            'status' => 'SENT'
        ]);
        
        return response()->json($kot, 201);
    });

    // Customer management routes
    Route::get('/customers/search', function ($tenant, Request $request) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);
        
        return app(\App\Http\Controllers\CustomerController::class)->search($request);
    });
    
    Route::post('/customers', function ($tenant, Request $request) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);
        
        return app(\App\Http\Controllers\CustomerController::class)->store($request);
    });
    
    Route::post('/customers/find-or-create', function ($tenant, Request $request) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);
        
        return app(\App\Http\Controllers\CustomerController::class)->findOrCreate($request);
    });
});
