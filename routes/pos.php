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
    
    Route::post('/dashboard/shift/checkout', [\App\Http\Controllers\Tenant\DashboardController::class, 'checkoutShift']);
    
    Route::post('/shifts/open', function ($tenant) {
        $account = app('tenant');
        
        $data = request()->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'opening_float' => 'nullable|numeric|min:0',
        ]);
        
        $terminalUser = null;
        $sessionToken = request()->header('X-Terminal-Session-Token') ?? request()->cookie('terminal_session_token');
        
        if ($sessionToken && strlen($sessionToken) > 100) {
            try {
                $sessionToken = decrypt($sessionToken);
            } catch (\Exception $e) {
                $sessionToken = request()->header('X-Terminal-Session-Token');
            }
        }
        
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
            return response()->json(['error' => 'Terminal session not found. Please login again.'], 401);
        }
        
        if (!$terminalUser->user_id) {
            $user = \App\Models\User::where('email', $terminalUser->terminal_id . '@terminal.local')->first();
            
            if (!$user) {
                $user = \App\Models\User::create([
                    'tenant_id' => $account->id,
                    'name' => $terminalUser->name,
                    'email' => $terminalUser->terminal_id . '@terminal.local',
                    'password' => bcrypt('terminal_password'),
                    'role' => 'terminal_user',
                    'is_active' => true,
                ]);
            }
            
            $terminalUser->update(['user_id' => $user->id]);
        }
        
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
    
    Route::post('/shifts/close', [\App\Http\Controllers\Tenant\DashboardController::class, 'checkoutShift']);
    
    Route::get('/shifts/current', [\App\Http\Controllers\Tenant\DashboardController::class, 'getCurrentShift']);
    
    Route::get('/shifts/test-session', function ($tenant) {
        $account = app('tenant'); // Get tenant from middleware context
        
        // Get terminal user from session
        $terminalUser = null;
        $sessionToken = request()->header('X-Terminal-Session-Token') ?? request()->cookie('terminal_session_token');
        
        // If the token looks like a Laravel encrypted cookie, try to decrypt it
        if ($sessionToken && strlen($sessionToken) > 100) {
            try {
                $sessionToken = decrypt($sessionToken);
            } catch (\Exception $e) {
                // Fall back to header token if cookie decryption fails
                $sessionToken = request()->header('X-Terminal-Session-Token');
            }
        }
        
        \Log::info('Test session request', [
            'session_token' => $sessionToken,
            'cookies' => request()->cookies->all(),
            'headers' => request()->headers->all()
        ]);
        
        if ($sessionToken) {
            $session = \App\Models\TerminalSession::where('session_token', $sessionToken)
                ->where('expires_at', '>', now())
                ->with('terminalUser')
                ->first();
            
            if ($session && $session->terminalUser) {
                $terminalUser = $session->terminalUser;
            }
        }
        
        return response()->json([
            'session_token' => $sessionToken,
            'terminal_user' => $terminalUser ? [
                'id' => $terminalUser->id,
                'terminal_id' => $terminalUser->terminal_id,
                'name' => $terminalUser->name,
                'user_id' => $terminalUser->user_id
            ] : null,
            'session_found' => $terminalUser ? true : false,
            'cookies' => request()->cookies->all(),
            'headers' => request()->headers->all()
        ]);
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
