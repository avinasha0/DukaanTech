<?php

use Illuminate\Support\Facades\Route;

// POS Public API routes - use optimized controllers with tenant context
Route::group(['prefix' => '{tenant}/pos/api', 'middleware' => ['resolve.tenant']], function () {
    Route::get('/categories', [\App\Http\Controllers\PosApiController::class, 'categories']);
    Route::get('/items', [\App\Http\Controllers\PosApiController::class, 'items']);
    Route::get('/order-types', [\App\Http\Controllers\PosApiController::class, 'orderTypes']);
    Route::get('/devices', [\App\Http\Controllers\PosApiController::class, 'devices']);
    Route::get('/outlets', [\App\Http\Controllers\PosApiController::class, 'outlets']);
Route::get('/tables', [\App\Http\Controllers\PosApiController::class, 'listTables']);
Route::patch('/tables/{table}/status', [\App\Http\Controllers\PosApiController::class, 'updateTableStatus']);
Route::post('/orders/place', [\App\Http\Controllers\PosApiController::class, 'placeOrder']);
Route::post('/orders/close', [\App\Http\Controllers\PosApiController::class, 'closeOrder']);
Route::put('/orders/{orderId}', [\App\Http\Controllers\PosApiController::class, 'updateOrder']);
Route::get('/tables/orders', [\App\Http\Controllers\PosApiController::class, 'getTableOrders']);
Route::get('/tables/status', [\App\Http\Controllers\PosApiController::class, 'getTableStatus']);
    Route::get('/dashboard/shift/current', [\App\Http\Controllers\PosApiController::class, 'currentShift']);
    
    Route::post('/dashboard/shift/checkout', function ($tenant) {
        $account = app('tenant'); // Get tenant from middleware context
        
        $data = request()->validate([
            'actual_cash' => 'required|numeric|min:0',
        ]);
        
        $outletId = request('outlet_id', 1);
        $shift = \App\Models\Shift::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->whereNull('closed_at')
            ->first();
            
        if (!$shift) {
            return response()->json(['error' => 'No open shift found'], 404);
        }
        
        if ($shift->closed_at) {
            return response()->json(['error' => 'Shift is already closed'], 400);
        }
        
        // Calculate expected cash
        $cashOrders = \App\Models\Order::where('tenant_id', $account->id)
            ->where('outlet_id', $shift->outlet_id)
            ->where('payment_method', 'cash')
            ->whereBetween('created_at', [$shift->created_at, now()])
            ->with(['items.modifiers'])
            ->get();
            
        $cashSales = $cashOrders->sum(function ($order) {
            return $order->items->sum(function ($item) {
                $subtotal = $item->qty * $item->price;
                $modifierTotal = $item->modifiers->sum('price');
                $tax = ($subtotal + $modifierTotal) * ($item->tax_rate / 100);
                return $subtotal + $modifierTotal + $tax - $item->discount;
            });
        });
        
        $expectedCash = $shift->opening_float + $cashSales;
        $variance = $data['actual_cash'] - $expectedCash;
        
        $shift->update([
            'closed_at' => now(),
            'expected_cash' => $expectedCash,
            'actual_cash' => $data['actual_cash'],
            'variance' => $variance
        ]);
        
        // Calculate final summary
        $orders = \App\Models\Order::where('tenant_id', $account->id)
            ->where('outlet_id', $shift->outlet_id)
            ->whereBetween('created_at', [$shift->created_at, $shift->closed_at])
            ->where('status', '!=', 'CANCELLED')
            ->with(['items.modifiers'])
            ->get();
            
        $totalSales = $orders->sum(function ($order) {
            return $order->items->sum(function ($item) {
                $subtotal = $item->qty * $item->price;
                $modifierTotal = $item->modifiers->sum('price');
                $tax = ($subtotal + $modifierTotal) * ($item->tax_rate / 100);
                return $subtotal + $modifierTotal + $tax - $item->discount;
            });
        });
        
        $cashSales = $orders->where('payment_method', 'cash')->sum(function ($order) {
            return $order->items->sum(function ($item) {
                $subtotal = $item->qty * $item->price;
                $modifierTotal = $item->modifiers->sum('price');
                $tax = ($subtotal + $modifierTotal) * ($item->tax_rate / 100);
                return $subtotal + $modifierTotal + $tax - $item->discount;
            });
        });
        
        $cardSales = $orders->where('payment_method', 'card')->sum(function ($order) {
            return $order->items->sum(function ($item) {
                $subtotal = $item->qty * $item->price;
                $modifierTotal = $item->modifiers->sum('price');
                $tax = ($subtotal + $modifierTotal) * ($item->tax_rate / 100);
                return $subtotal + $modifierTotal + $tax - $item->discount;
            });
        });
        
        $upiSales = $orders->where('payment_method', 'upi')->sum(function ($order) {
            return $order->items->sum(function ($item) {
                $subtotal = $item->qty * $item->price;
                $modifierTotal = $item->modifiers->sum('price');
                $tax = ($subtotal + $modifierTotal) * ($item->tax_rate / 100);
                return $subtotal + $modifierTotal + $tax - $item->discount;
            });
        });
        
        $summary = [
            'total_sales' => $totalSales,
            'total_orders' => $orders->count(),
            'cash_sales' => $cashSales,
            'card_sales' => $cardSales,
            'upi_sales' => $upiSales,
            'opening_float' => $shift->opening_float,
            'expected_cash' => $expectedCash,
            'actual_cash' => $data['actual_cash'],
            'variance' => $variance,
        ];
        
        return response()->json([
            'shift' => $shift,
            'summary' => $summary,
            'message' => 'Shift closed successfully'
        ]);
    });
    
    Route::post('/shifts/open', function ($tenant) {
        $account = app('tenant'); // Get tenant from middleware context
        
        $data = request()->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'opening_float' => 'nullable|numeric|min:0',
        ]);
        
        // Get terminal user from session
        $terminalUser = null;
        $sessionToken = request()->cookie('terminal_session_token');
        if ($sessionToken) {
            $session = \App\Models\TerminalSession::where('session_token', $sessionToken)
                ->where('expires_at', '>', now())
                ->with('terminalUser')
                ->first();
            
            if ($session && $session->terminalUser) {
                $terminalUser = $session->terminalUser;
            }
        }
        
        if (!$terminalUser) {
            return response()->json(['error' => 'Terminal session not found'], 401);
        }
        
        // Ensure TerminalUser has a linked User record
        if (!$terminalUser->user_id) {
            $user = \App\Models\User::create([
                'tenant_id' => $account->id,
                'name' => $terminalUser->name,
                'email' => $terminalUser->terminal_id . '@terminal.local',
                'password' => bcrypt('terminal_password'),
                'role' => 'terminal_user',
                'is_active' => true,
            ]);
            
            $terminalUser->update(['user_id' => $user->id]);
        }
        
        // Check if there's already an open shift for this user
        $existingShift = \App\Models\Shift::where('tenant_id', $account->id)
            ->where('opened_by', $terminalUser->user_id)
            ->where('outlet_id', $data['outlet_id'])
            ->whereNull('closed_at')
            ->first();
            
        if ($existingShift) {
            return response()->json(['error' => 'You already have an open shift for this outlet'], 400);
        }
        
        $shift = \App\Models\Shift::create([
            'tenant_id' => $account->id,
            'outlet_id' => $data['outlet_id'],
            'opening_float' => $data['opening_float'] ?? 0,
            'opened_by' => $terminalUser->user_id,
        ]);
        
        return response()->json($shift, 201);
    });
    
    Route::get('/shifts/current', function ($tenant) {
        $account = app('tenant'); // Get tenant from middleware context
        
        $outletId = request('outlet_id', 1);
        
        $shift = \App\Models\Shift::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->whereNull('closed_at')
            ->first();
            
        return response()->json([
            'shift' => $shift,
            'has_shift' => $shift ? true : false
        ]);
    });
    
    Route::get('/orders/current-shift', function ($tenant) {
        $account = app('tenant'); // Get tenant from middleware context
        
        $data = request()->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'shift_id' => 'required|exists:shifts,id'
        ]);

        // Get the shift to verify it belongs to current tenant and outlet
        $shift = \App\Models\Shift::where('id', $data['shift_id'])
            ->where('tenant_id', $account->id)
            ->where('outlet_id', $data['outlet_id'])
            ->first();

        if (!$shift) {
            return response()->json(['error' => 'Shift not found'], 404);
        }

        // Get last 10 orders created during this shift
        $orders = \App\Models\Order::where('tenant_id', $account->id)
            ->where('outlet_id', $data['outlet_id'])
            ->whereBetween('created_at', [$shift->created_at, $shift->closed_at ?? now()])
            ->with(['orderType', 'items.item', 'outlet'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($orders);
    });
    
    Route::get('/kot/status', function ($tenant) {
        $account = app('tenant'); // Get tenant from middleware context
        $outletId = request('outlet_id', 1);
        $status = request('status', 'SENT');
        $kots = \App\Models\KitchenTicket::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->where('status', $status)
            ->with(['order', 'device'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'kots' => $kots,
            'kot_enabled' => $account->kot_enabled ?? false
        ]);
    });
    
    Route::post('/orders', function ($tenant) {
        $account = app('tenant'); // Get tenant from middleware context
        
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
        
        $data = request()->all();
        
        // Set default values for optional fields
        $data['table_no'] = $data['table_no'] ?? null;
        $data['customer_name'] = $data['customer_name'] ?? null;
        $data['customer_phone'] = $data['customer_phone'] ?? null;
        $data['customer_address'] = $data['customer_address'] ?? null;
        $data['delivery_address'] = $data['delivery_address'] ?? null;
        $data['delivery_fee'] = $data['delivery_fee'] ?? 0;
        $data['special_instructions'] = $data['special_instructions'] ?? null;
        
        // Validate required fields
        $validator = \Validator::make($data, [
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
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
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
        $account = app('tenant'); // Get tenant from middleware context
        
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
        
        // Check if order is in NEW state
        if ($order->state !== 'NEW') {
            return response()->json(['error' => 'Order must be in NEW state to create KOT'], 400);
        }
        
        $data = request()->validate([
            'station' => 'required|string|max:255',
        ]);
        
        // Create kitchen ticket
        $kitchenTicket = $order->kitchenTickets()->create([
            'tenant_id' => $account->id,
            'outlet_id' => $order->outlet_id,
            'device_id' => $deviceId,
            'station' => $data['station'],
            'status' => 'SENT'
        ]);
        
        // Create kitchen lines for each order item
        foreach ($order->items as $orderItem) {
            $kitchenTicket->lines()->create([
                'tenant_id' => $account->id,
                'order_item_id' => $orderItem->id,
                'qty' => $orderItem->qty,
            ]);
        }
        
        // Update order state to IN_KITCHEN
        $order->update(['state' => 'IN_KITCHEN']);
        
        return response()->json([
            'id' => $kitchenTicket->id,
            'order_id' => $order->id,
            'station' => $kitchenTicket->station,
            'status' => $kitchenTicket->status,
            'created_at' => $kitchenTicket->created_at,
            'lines_count' => $kitchenTicket->lines()->count()
        ], 201);
    });
});

// Terminal Authentication API Routes
Route::group(['prefix' => '{tenant}/terminal/api', 'middleware' => ['resolve.tenant']], function () {
    Route::post('/login', [\App\Http\Controllers\TerminalAuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\TerminalAuthController::class, 'logout']);
    Route::get('/session', [\App\Http\Controllers\TerminalAuthController::class, 'session'])
        ->middleware(['terminal.auth']);
    Route::post('/validate', [\App\Http\Controllers\TerminalAuthController::class, 'validateSession'])
        ->middleware(['terminal.auth']);
    Route::post('/extend', [\App\Http\Controllers\TerminalAuthController::class, 'extend'])
        ->middleware(['terminal.auth']);
});
