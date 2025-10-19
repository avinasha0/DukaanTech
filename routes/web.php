<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Tenant\KotSettingsController;
use App\Http\Controllers\TerminalAuthController;
use App\Http\Controllers\PosRegisterController;
use App\Http\Controllers\PosApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// Website Pages Routes
Route::get('/product', function () {
    return view('pages.product');
})->name('product');

Route::get('/features', function () {
    return view('pages.features');
})->name('features');

Route::get('/pricing', function () {
    return view('pages.pricing');
})->name('pricing');

Route::get('/integrations', function () {
    return view('pages.integrations');
})->name('integrations');

Route::get('/api-documentation', function () {
    return view('pages.api-documentation');
})->name('api-documentation');

Route::get('/mobile-app', function () {
    return view('pages.mobile-app');
})->name('mobile-app');

Route::get('/about-us', function () {
    return view('pages.about-us');
})->name('about-us');

Route::get('/careers', function () {
    return view('pages.careers');
})->name('careers');

Route::get('/blog', function () {
    return view('pages.blog');
})->name('blog');

Route::get('/press', function () {
    return view('pages.press');
})->name('press');

Route::get('/partners', function () {
    return view('pages.partners');
})->name('partners');

Route::get('/help-center', function () {
    return view('pages.help-center');
})->name('help-center');

Route::get('/contact-us', [App\Http\Controllers\ContactController::class, 'show'])->name('contact-us');
Route::post('/contact-us', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact-us.submit');

Route::get('/training', function () {
    return view('pages.training');
})->name('training');

Route::get('/system-status', function () {
    return view('pages.system-status');
})->name('system-status');

Route::get('/community', function () {
    return view('pages.community');
})->name('community');

// QR Ordering Routes
Route::prefix('{tenant}/qr-order')->group(function () {
    Route::get('/menu', [App\Http\Controllers\QROrderController::class, 'showMenu']);
    Route::get('/category/{category}', [App\Http\Controllers\QROrderController::class, 'showCategory']);
    Route::get('/item/{item}', [App\Http\Controllers\QROrderController::class, 'showItem']);
    Route::get('/table/{tableNo}', [App\Http\Controllers\QROrderController::class, 'showTable']);
    Route::post('/create', [App\Http\Controllers\QROrderController::class, 'createOrder']);
    Route::post('/generate-qr', [App\Http\Controllers\QROrderController::class, 'generateQR']);
});



Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('pages.terms-of-service');
})->name('terms-of-service');

Route::get('/cookie-policy', function () {
    return view('pages.cookie-policy');
})->name('cookie-policy');

// KOT Settings routes without auth (for public access)
Route::post('/{tenant}/kot/toggle-public', function ($tenant) {
    \Log::info('KOT toggle called for tenant: ' . $tenant, request()->all());
    
    $account = \App\Models\Account::where('slug', $tenant)->first();
    if (!$account) {
        return response()->json(['error' => 'Tenant not found'], 404);
    }
    
    $data = request()->validate([
        'enabled' => 'required|boolean',
    ]);
    
    \Log::info('Updating KOT enabled to: ' . ($data['enabled'] ? 'true' : 'false'));
    $account->update(['kot_enabled' => $data['enabled']]);
    
    return response()->json([
        'message' => $data['enabled'] ? 'KOT functionality enabled' : 'KOT functionality disabled',
        'kot_enabled' => $account->kot_enabled
    ]);
})->withoutMiddleware(['web']);

Route::get('/{tenant}/kot/status-public', function ($tenant) {
    \Log::info('KOT status called for tenant: ' . $tenant);
    
    $account = \App\Models\Account::where('slug', $tenant)->first();
    if (!$account) {
        return response()->json(['error' => 'Tenant not found'], 404);
    }
    
    \Log::info('KOT status for tenant ' . $tenant . ': ' . ($account->kot_enabled ? 'enabled' : 'disabled'));
    
    return response()->json([
        'kot_enabled' => $account->kot_enabled,
        'kot_settings' => $account->kot_settings
    ]);
})->withoutMiddleware(['web']);

Route::get('/{tenant}/kot', function ($tenant) {
    $account = \App\Models\Account::where('slug', $tenant)->first();
    if (!$account) {
        abort(404, 'Tenant not found');
    }
    
    return view('tenant.kot-dashboard', ['tenant' => $account]);
})->name('tenant.kot.public')->withoutMiddleware(['resolve.tenant']);

// Terminal Authentication Routes
Route::get('/{tenant}/terminal/login', [TerminalAuthController::class, 'showLogin'])
    ->name('terminal.login')
    ->middleware(['resolve.tenant']);

Route::post('/{tenant}/terminal/login', [TerminalAuthController::class, 'login'])
    ->name('terminal.login.post')
    ->middleware(['resolve.tenant']);

Route::get('/{tenant}/pos/register', [PosRegisterController::class, 'index'])
    ->name('tenant.pos.register')
    ->middleware(['resolve.tenant']);

Route::get('/{tenant}/pos/terminal', [PosRegisterController::class, 'terminalOnly'])
    ->name('tenant.pos.terminal')
    ->middleware(['resolve.tenant']);

Route::get('/{tenant}/pos/shift-open', [PosRegisterController::class, 'shiftOpen'])
    ->name('tenant.pos.shift-open')
    ->middleware(['resolve.tenant']);

// POS API endpoints for register functionality
Route::group(['prefix' => '{tenant}/pos/api', 'middleware' => ['resolve.tenant']], function () {
    Route::get('/categories', [PosApiController::class, 'categories']);
    Route::get('/items', [PosApiController::class, 'items']);
    Route::get('/order-types', [PosApiController::class, 'orderTypes']);
    Route::get('/devices', [PosApiController::class, 'devices']);
    Route::get('/dashboard/shift/current', [PosApiController::class, 'currentShift']);
    
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
            'customer_address' => $data['customer_address'],
            'delivery_address' => $data['delivery_address'],
            'delivery_fee' => $data['delivery_fee'] ?? 0,
            'special_instructions' => $data['special_instructions'],
            'mode' => $data['mode'],
            'state' => 'PENDING',
        ]);
        
        // Create order items
        foreach ($data['items'] as $itemData) {
            $item = \App\Models\Item::find($itemData['item_id']);
            $orderItem = \App\Models\OrderItem::create([
                'tenant_id' => $account->id,
                'order_id' => $order->id,
                'item_id' => $itemData['item_id'],
                'qty' => $itemData['qty'],
                'price' => $item->priceFor(null, $order->outlet),
                'tax_rate' => $item->tax_rate ?? 0,
                'discount' => 0,
            ]);
            
            // Handle modifiers if any
            if (isset($itemData['modifiers']) && is_array($itemData['modifiers'])) {
                foreach ($itemData['modifiers'] as $modifierId) {
                    $modifier = \App\Models\Modifier::find($modifierId);
                    if ($modifier) {
                        \App\Models\OrderItemModifier::create([
                            'order_item_id' => $orderItem->id,
                            'modifier_id' => $modifierId,
                            'price' => $modifier->price,
                        ]);
                    }
                }
            }
        }
        
        return response()->json($order->load(['items.modifiers', 'orderType']), 201);
    });
    
    Route::get('/orders', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        $outletId = request('outlet_id', 1);
        $shiftId = request('shift_id');
        
        $query = \App\Models\Order::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->with(['items.modifiers', 'orderType']);
            
        if ($shiftId) {
            $shift = \App\Models\Shift::find($shiftId);
            if ($shift) {
                $query->whereBetween('created_at', [$shift->created_at, $shift->closed_at ?? now()]);
            }
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($orders);
    });
});

// Public API endpoints for POS register (completely bypass web middleware)
Route::group(['prefix' => '{tenant}/api/public', 'middleware' => []], function () {
    Route::get('/categories', function ($tenant) {
        \Log::info('OLD API /categories called', [
            'tenant' => $tenant,
            'request_url' => request()->fullUrl(),
            'request_path' => request()->getPathInfo()
        ]);
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        return \App\Models\Category::where('tenant_id', $account->id)->get();
    });
    
    Route::get('/items', function ($tenant) {
        \Log::info('OLD API /items called', [
            'tenant' => $tenant,
            'request_url' => request()->fullUrl(),
            'request_path' => request()->getPathInfo()
        ]);
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        return \App\Models\Item::where('tenant_id', $account->id)->get();
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
        return response()->json($kots);
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
                'price' => $item->priceFor(null, $order->outlet),
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
    
    Route::get('/customers', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        // Get unique customers from orders
        $customers = \App\Models\Order::where('tenant_id', $account->id)
            ->whereNotNull('customer_phone')
            ->select('customer_name as name', 'customer_phone as phone', 'delivery_address')
            ->distinct()
            ->orderBy('customer_name')
            ->get()
            ->map(function ($order, $index) {
                return [
                    'id' => $index + 1, // Simple ID for frontend
                    'name' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->delivery_address, // Use delivery_address as address
                    'delivery_address' => $order->delivery_address
                ];
            });
        
        return response()->json($customers);
    });
});

// Legacy public API endpoints (keeping for backward compatibility)
Route::prefix('{tenant}/api/public')->group(function () {
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
    
    Route::get('/order-types', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        return \App\Models\OrderType::where('tenant_id', $account->id)->get();
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
                'price' => $item->priceFor(null, $order->outlet),
            ]);
        }
        
        return response()->json($order, 201);
    });
    
    // Public KOT creation endpoint
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
    
    // Public shift status endpoint (duplicate - removed to avoid conflicts)
    
    // Public customers endpoint
    Route::get('/customers', function ($tenant) {
        $account = \App\Models\Account::where('slug', $tenant)->first();
        if (!$account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
        
        // Get unique customers from orders
        $customers = \App\Models\Order::where('tenant_id', $account->id)
            ->whereNotNull('customer_phone')
            ->select('customer_name as name', 'customer_phone as phone', 'delivery_address')
            ->distinct()
            ->orderBy('customer_name')
            ->get()
            ->map(function ($order, $index) {
                return [
                    'id' => $index + 1, // Simple ID for frontend
                    'name' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->delivery_address, // Use delivery_address as address
                    'delivery_address' => $order->delivery_address
                ];
            });
        
        return response()->json($customers);
    });
    
    // Public KOT status endpoint
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
})->withoutMiddleware(['web', 'csrf']);

// Login routes are handled in auth.php

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register', function () {
    return view('auth.register-styled');
})->name('register');

// Custom Registration Routes (if needed for backend functionality)
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/register/success', [RegisterController::class, 'registerSuccess'])->name('register.success');
Route::get('/activate/{token}', [RegisterController::class, 'activate'])->name('activate');

// Organization Setup
Route::middleware(['auth'])->group(function () {
    Route::get('/organization/setup', [OrganizationController::class, 'showSetupForm'])->name('organization.setup');
    Route::post('/organization/setup', [OrganizationController::class, 'setup']);
    
    // Dashboard - redirect to tenant dashboard if user has tenant
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        if ($user && $user->tenant_id) {
            $tenant = $user->tenant;
            if ($tenant) {
                // Redirect to tenant-specific dashboard
                if ($tenant->plan === 'free') {
                    return redirect()->to("/{$tenant->slug}/dashboard");
                } else {
                    return redirect()->to("http://{$tenant->slug}.localhost:8000/dashboard");
                }
            }
        }
        
        // If no tenant, show organization setup
        return redirect()->route('organization.setup')
            ->with('info', 'Please complete your organization setup to access the dashboard.');
    })->name('dashboard');
    
    // Debug route to check user and tenant status
    Route::get('/debug-user', function () {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Not authenticated']);
        }
        
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'tenant_id' => $user->tenant_id,
            'tenant' => $user->tenant ? [
                'id' => $user->tenant->id,
                'name' => $user->tenant->name,
                'slug' => $user->tenant->slug,
                'plan' => $user->tenant->plan,
                'status' => $user->tenant->status
            ] : null
        ]);
    })->name('debug.user');
    
    
    // KOT Settings for main dashboard
    Route::post('/kot/toggle', [KotSettingsController::class, 'toggle']);
    Route::get('/kot/status', [KotSettingsController::class, 'getStatus']);
    Route::post('/kot/settings', [KotSettingsController::class, 'updateSettings']);
});

// Path-based tenant routes (for free users)
Route::middleware(['web', 'auth', 'resolve.tenant'])->group(function () {
    // Dashboard route is now handled by tenant.php with prefix {tenant}
    
        Route::get('/{tenant}/menu', function () {
            $tenant = app('tenant');
            if (!$tenant) {
                abort(404, 'Tenant not found');
            }

            return view('tenant.menu', compact('tenant'));
        })->name('tenant.menu.path');
    
    
    
    Route::get('/{tenant}/reports', function () {
        $tenant = app('tenant');
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        return app(\App\Http\Controllers\Tenant\ReportsController::class)->index(request());
    })->name('tenant.reports.path');
    
    // KOT Settings routes for web interface
    Route::post('/{tenant}/kot/toggle', [KotSettingsController::class, 'toggle']);
    Route::get('/{tenant}/kot/status', [KotSettingsController::class, 'getStatus']);
    Route::post('/{tenant}/kot/settings', [KotSettingsController::class, 'updateSettings']);
    
    Route::get('/{tenant}/settings', function () {
        $tenant = app('tenant');
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        return app(\App\Http\Controllers\Tenant\SettingsController::class)->index(request());
    })->name('tenant.settings.path');
    
    // Role and Permission Management Routes
    Route::resource('{tenant}/roles', \App\Http\Controllers\RoleController::class)->names([
        'index' => 'tenant.roles.index',
        'create' => 'tenant.roles.create',
        'store' => 'tenant.roles.store',
        'show' => 'tenant.roles.show',
        'edit' => 'tenant.roles.edit',
        'update' => 'tenant.roles.update',
        'destroy' => 'tenant.roles.destroy',
    ]);
    
    Route::resource('{tenant}/permissions', \App\Http\Controllers\PermissionController::class)->names([
        'index' => 'tenant.permissions.index',
        'create' => 'tenant.permissions.create',
        'store' => 'tenant.permissions.store',
        'show' => 'tenant.permissions.show',
        'edit' => 'tenant.permissions.edit',
        'update' => 'tenant.permissions.update',
        'destroy' => 'tenant.permissions.destroy',
    ]);
    
    // User Management Routes
    Route::get('{tenant}/users', [\App\Http\Controllers\UserManagementController::class, 'index'])->name('tenant.users.index');
    Route::get('{tenant}/users/create', [\App\Http\Controllers\UserManagementController::class, 'create'])->name('tenant.users.create');
    Route::post('{tenant}/users', [\App\Http\Controllers\UserManagementController::class, 'store'])->name('tenant.users.store');
    Route::get('{tenant}/users/{user}', [\App\Http\Controllers\UserManagementController::class, 'show'])->name('tenant.users.show');
    Route::get('{tenant}/users/{user}/edit-roles', [\App\Http\Controllers\UserManagementController::class, 'editRoles'])->name('tenant.users.edit-roles');
    Route::put('{tenant}/users/{user}/roles', [\App\Http\Controllers\UserManagementController::class, 'updateRoles'])->name('tenant.users.update-roles');
    
    // Terminal User Management Routes (integrated into users page)
    Route::post('{tenant}/users/terminal', [\App\Http\Controllers\UserManagementController::class, 'storeTerminalUser'])->name('tenant.users.store-terminal');
    Route::put('{tenant}/users/terminal/{terminalUser}', [\App\Http\Controllers\UserManagementController::class, 'updateTerminalUser'])->name('tenant.users.update-terminal');
    Route::delete('{tenant}/users/terminal/{terminalUser}', [\App\Http\Controllers\UserManagementController::class, 'destroyTerminalUser'])->name('tenant.users.destroy-terminal');
    Route::post('{tenant}/users/terminal/{terminalUser}/toggle-status', [\App\Http\Controllers\UserManagementController::class, 'toggleTerminalUserStatus'])->name('tenant.users.toggle-terminal-status');
    
    
    
    // Debug route to test tenant resolution
    Route::get('/{tenant}/debug', function () {
        $tenant = app('tenant');
        $tenantId = app('tenant.id');
        return response()->json([
            'tenant' => $tenant ? $tenant->toArray() : null,
            'tenant_id' => $tenantId,
            'path' => request()->getPathInfo(),
            'url' => request()->fullUrl()
        ]);
    })->name('tenant.debug');
});

// Public orders route (no authentication required)
Route::get('/{tenant}/orders', function ($tenant) {
    $account = \App\Models\Account::where('slug', $tenant)->first();
    if (!$account) {
        abort(404, 'Tenant not found');
    }
    
    $orderTypes = \App\Models\OrderType::where('tenant_id', $account->id)->get();
    
    return view('tenant.orders', ['tenant' => $account, 'orderTypes' => $orderTypes]);
})->name('tenant.orders.path');

// Path-based tenant web routes mount (re-uses routes/tenant.php under /{tenant}/*)
Route::middleware(['web', 'auth', 'resolve.tenant'])
    ->prefix('{tenant}')
    ->group(function () {
        require base_path('routes/tenant.php');
    });

// Path-based tenant API mount (uses dedicated API routes file)
Route::middleware(['api', 'resolve.tenant'])
    ->prefix('{tenant}/pos/api')
    ->group(function () {
        require base_path('routes/tenant-api.php');
    });

// KOT API routes
Route::middleware(['api', 'auth', 'resolve.tenant'])
    ->prefix('{tenant}/api')
    ->group(function () {
        Route::get('kot', [\App\Http\Controllers\Tenant\Pos\KotController::class, 'index']);
        Route::get('kot/{kot}', [\App\Http\Controllers\Tenant\Pos\KotController::class, 'show']);
    });

// Removed default dashboard route - using custom redirect logic in AuthenticatedSessionController

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
