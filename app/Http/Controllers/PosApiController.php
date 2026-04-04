<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\OrderType;
use App\Models\Device;
use App\Models\Outlet;
use App\Models\Shift;
use App\Models\TerminalSession;
use App\Models\RestaurantTable;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PosApiController extends Controller
{
    /**
     * Get the tenant ID, resolving it if necessary
     */
    private function getTenantId()
    {
        // First try to get from middleware context
        if (app()->bound('tenant.id')) {
            return app('tenant.id');
        }
        
        // Fallback: resolve tenant from URL parameter
        $tenantSlug = request()->route('tenant');
        if ($tenantSlug) {
            $tenant = \App\Models\Account::where('slug', $tenantSlug)->first();
            if ($tenant) {
                // Cache the tenant context for this request
                app()->instance('tenant.id', $tenant->id);
                app()->instance('tenant.model', $tenant);
                app()->instance('tenant', $tenant);
                return $tenant->id;
            }
        }
        
        throw new \Exception('Tenant not found');
    }
    
    /**
     * Get categories for the current tenant.
     */
    public function categories()
    {
        $tenantId = $this->getTenantId();
        return Category::where('tenant_id', $tenantId)->get();
    }
    
    /**
     * Get items for the current tenant.
     */
    public function items(Request $request)
    {
        $tenantId = $this->getTenantId();
        $query = Item::where('tenant_id', $tenantId);
        
        // Filter for POS if requested
        if ($request->boolean('pos')) {
            $query->where('is_active', true);
        }
        
        // Include pricing information
        $items = $query->with(['prices' => function($q) {
            $q->where('is_available', true);
        }])->get();
        
        // Transform items to include the correct price
        $items->transform(function($item) {
            $item->price = $item->prices->first()->price ?? $item->price ?? 0;
            return $item;
        });
        
        return $items;
    }
    
    /**
     * Get order types for the current tenant.
     */
    public function orderTypes()
    {
        $tenantId = $this->getTenantId();

        return OrderType::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
    
    /**
     * Get devices for the current tenant.
     */
    public function devices(Request $request)
    {
        $tenantId = $this->getTenantId();
        $outletId = $request->get('outlet_id');
        
        $query = Device::where('tenant_id', $tenantId);
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }
        
        return $query->with('outlet')->get();
    }
    
    /**
     * Get outlets for the current tenant.
     */
    public function outlets(Request $request)
    {
        $tenantId = $this->getTenantId();
        
        return Outlet::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();
    }
    
    /**
     * Get restaurant tables for the current tenant.
     */
    public function tables(Request $request)
    {
        $tenantId = $this->getTenantId();
        $outletId = $request->get('outlet_id');
        
        $query = RestaurantTable::where('tenant_id', $tenantId)
            ->where('is_active', true);
            
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }
        
        return $query->orderBy('name')->get();
    }
    
    /**
     * Get current shift for the tenant (actor's open shift on the requested outlet).
     */
    public function currentShift(Request $request)
    {
        $tenantId = $this->getTenantId();
        $outletId = (int) $request->get('outlet_id', 1);

        $terminalUser = TerminalSession::terminalUserFromHttpRequest($request);
        if ($terminalUser) {
            $terminalUser->ensureLinkedShadowUser();
            $terminalUser->refresh();
        }

        $userId = auth()->check()
            ? (int) auth()->id()
            : (int) ($terminalUser?->user_id ?? 0);

        if ($userId === 0) {
            return response()->json([
                'shift' => null,
                'has_shift' => false,
                'summary' => [
                    'total_sales' => 0,
                    'total_orders' => 0,
                    'cash_sales' => 0,
                    'card_sales' => 0,
                    'upi_sales' => 0,
                    'opening_float' => 0,
                ],
            ]);
        }

        $shift = Shift::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenantId)
            ->where('outlet_id', $outletId)
            ->where('opened_by', $userId)
            ->whereNull('closed_at')
            ->first();

        $openShiftsAnyOutlet = Shift::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenantId)
            ->whereNull('closed_at')
            ->get(['id', 'outlet_id', 'opening_float', 'created_at']);
            
        if (!$shift) {
            $payload = [
                'shift' => null,
                'has_shift' => false,
                'summary' => [
                    'total_sales' => 0,
                    'total_orders' => 0,
                    'cash_sales' => 0,
                    'card_sales' => 0,
                    'upi_sales' => 0,
                    'opening_float' => 0,
                ],
            ];
            if (config('app.debug')) {
                $payload['_debug'] = [
                    'reason' => 'no_open_shift_for_outlet',
                    'requested_outlet_id' => $outletId,
                    'tenant_id' => $tenantId,
                    'open_shifts' => $openShiftsAnyOutlet->toArray(),
                ];
            }

            return response()->json($payload);
        }
        
        // Calculate summary using ShiftService
        $shiftService = new \App\Services\ShiftService();
        $summary = $shiftService->getShiftSummary($shift);

        $payload = [
            'shift' => $shift,
            'has_shift' => true,
            'summary' => $summary,
        ];
        if (config('app.debug')) {
            $payload['_debug'] = array_merge(
                \App\Services\ShiftService::debugShiftSummaryContext($shift),
                ['requested_outlet_id' => $outletId, 'tenant_id' => $tenantId]
            );
        }

        return response()->json($payload);
    }
    
    /**
     * Update table status for the current tenant.
     */
    public function updateTableStatus(Request $request, $tableId)
    {
        \Log::info('=== UPDATE TABLE STATUS API CALL ===');
        \Log::info('Table ID:', $tableId);
        \Log::info('Request data:', $request->all());
        \Log::info('Tenant ID:', ['tenant_id' => $this->getTenantId()]);
        
        $tenantId = $this->getTenantId();
        
        $request->validate([
            'status' => 'required|in:free,occupied,reserved',
            'total_amount' => 'nullable|numeric|min:0',
            'orders' => 'nullable|array',
        ]);
        
        $table = RestaurantTable::where('tenant_id', $tenantId)
            ->where('id', $tableId)
            ->first();
            
        if (!$table) {
            \Log::error('Table not found:', ['table_id' => $tableId, 'tenant_id' => $tenantId]);
            return response()->json(['error' => 'Table not found'], 404);
        }
        
        \Log::info('Table found:', ['id' => $table->id, 'name' => $table->name, 'current_status' => $table->status]);
        
        $updateData = [
            'status' => $request->status,
        ];
        
        if ($request->has('total_amount')) {
            $updateData['total_amount'] = $request->total_amount;
        }
        
        if ($request->has('orders')) {
            $updateData['orders'] = $request->orders;
        }
        
        \Log::info('Update data:', $updateData);
        
        $table->update($updateData);
        
        \Log::info('Table updated successfully:', ['id' => $table->id, 'new_status' => $table->status]);
        
        return response()->json($table);
    }

    /**
     * Place an order and mark table as occupied
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:restaurant_tables,id',
        ]);

        $tableId = $request->input('table_id');
        $tenantId = $this->getTenantId();

        try {
            $order = DB::transaction(function () use ($tableId, $tenantId) {
                $table = RestaurantTable::where('tenant_id', $tenantId)
                    ->lockForUpdate()
                    ->findOrFail($tableId);

                // Reuse existing running order for this table (single active order rule)
                $existingOrder = $table->orders()
                    ->where('status', 'OPEN')
                    ->latest('id')
                    ->first();
                if ($existingOrder) {
                    $table->update([
                        'status' => 'occupied',
                        'current_order_id' => $existingOrder->id,
                    ]);
                    return ['order' => $existingOrder, 'existing' => true];
                }

                // Get the first available order type for this tenant, or create default ones
                $orderType = \App\Models\OrderType::where('tenant_id', $tenantId)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->first();
                
                if (!$orderType) {
                    // Create tenant fallback order type with globally unique slug.
                    $baseSlug = 'dine-in';
                    $slug = $baseSlug;
                    $suffix = 1;
                    while (\App\Models\OrderType::withoutGlobalScopes()
                        ->where('slug', $slug)
                        ->exists()) {
                        $suffix++;
                        $slug = Str::slug("{$baseSlug}-{$tenantId}-{$suffix}");
                    }

                    $orderType = \App\Models\OrderType::create([
                        'tenant_id' => $tenantId,
                        'name' => 'Dine In',
                        'slug' => $slug,
                        'color' => '#10B981',
                        'is_active' => true,
                        'sort_order' => 1,
                    ]);
                }

                $shiftId = $this->resolveActiveShiftId($tenantId, $table->outlet_id);
                $cashierId = $this->resolveCashierId();

                $order = Order::create([
                    'tenant_id' => $tenantId,
                    'outlet_id' => $table->outlet_id ?? 1, // Use table's outlet or default
                    'table_id' => $table->id,
                    'status' => 'OPEN',
                    'order_type_id' => $orderType->id,
                    'mode' => 'DINE_IN',
                    'state' => 'NEW',
                    'meta' => [
                        'session_type' => 'running',
                        'shift_id' => $shiftId,
                        'cashier_id' => $cashierId,
                    ],
                ]);

                $table->update([
                    'status' => 'occupied',
                    'current_order_id' => $order->id,
                ]);

                return ['order' => $order, 'existing' => false];
            });

            return response()->json([
                'success' => true, 
                'order_id' => $order['order']->id,
                'table_id' => $tableId,
                'existing_order' => $order['existing'],
                'message' => $order['existing']
                    ? 'Existing running order loaded'
                    : 'Running order started successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to place order', [
                'table_id' => $tableId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Close an order and mark table as free
     */
    public function closeOrder(Request $request)
    {
        \Log::info('=== CLOSE ORDER API CALL START ===');
        \Log::info('Request data:', $request->all());
        \Log::info('Tenant ID:', ['tenant_id' => $this->getTenantId()]);
        
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'sometimes|in:CLOSED,PAID',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $orderId = $request->input('order_id');
        $status = $request->input('status', 'CLOSED'); // Default to CLOSED if not specified
        $tenantId = $this->getTenantId();
        
        \Log::info('Order ID to close:', ['order_id' => $orderId]);
        \Log::info('Tenant ID:', ['tenant_id' => $tenantId]);

        try {
            $paymentMethod = $request->input('payment_method');

            $result = DB::transaction(function () use ($orderId, $tenantId, $status, $paymentMethod) {
                \Log::info('Starting transaction for order close');
                
                $order = Order::where('tenant_id', $tenantId)
                    ->lockForUpdate()
                    ->findOrFail($orderId);
                    
                \Log::info('Order found:', [
                    'id' => $order->id,
                    'status' => $order->status,
                    'table_id' => $order->table_id,
                    'tenant_id' => $order->tenant_id
                ]);

                $table = RestaurantTable::where('tenant_id', $tenantId)
                    ->lockForUpdate()
                    ->findOrFail($order->table_id);
                    
                \Log::info('Table found:', [
                    'id' => $table->id,
                    'name' => $table->name,
                    'status' => $table->status,
                    'current_order_id' => $table->current_order_id
                ]);

                $orderPayload = [
                    'status' => $status,
                    'state' => 'CLOSED',
                ];
                if ($paymentMethod !== null && $paymentMethod !== '') {
                    $orderPayload['payment_method'] = $paymentMethod;
                }
                $order->update($orderPayload);
                \Log::info("Order status updated to {$status}, state updated to CLOSED");

                // Use the table's syncStatus method for consistent logic
                $table->syncStatus();
                \Log::info('Table status synced based on active orders');
                
                return [
                    'order_id' => $order->id,
                    'table_id' => $table->id,
                    'table_status' => $table->status
                ];
            });

            \Log::info('Transaction completed successfully:', $result);
            \Log::info('=== CLOSE ORDER API CALL SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Order closed successfully',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ FAILED TO CLOSE ORDER', [
                'order_id' => $orderId,
                'tenant_id' => $tenantId,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'debug_info' => [
                    'order_id' => $orderId,
                    'tenant_id' => $tenantId,
                    'error_code' => $e->getCode()
                ]
            ], 400);
        }
    }

    /**
     * List tables with true status from database
     */
    public function listTables(Request $request)
    {
        $tenantId = $this->getTenantId();
        $outletId = $request->get('outlet_id');

        $query = RestaurantTable::where('tenant_id', $tenantId)
            ->where('is_active', true);

        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        // Get tables with their open orders count in a single query
        $tables = $query->select('id', 'name', 'status', 'current_order_id', 'total_amount')
            ->withCount(['orders as open_orders_count' => function ($q) {
                $q->where('status', 'OPEN');
            }])
            ->orderBy('id')
            ->get();

        // Backfill current_order_id when table has OPEN orders but pointer was never set (fixes Mark Paid)
        foreach ($tables as $table) {
            if ($table->open_orders_count > 0 && ! $table->current_order_id) {
                $openOrder = Order::where('tenant_id', $tenantId)
                    ->where('table_id', $table->id)
                    ->where('status', 'OPEN')
                    ->latest('id')
                    ->first();
                if ($openOrder) {
                    $table->current_order_id = $openOrder->id;
                    $table->save();
                }
            }
        }

        // Update total amounts for occupied tables (one query for all open orders + lines)
        $tableIds = $tables->pluck('id');
        $openOrdersByTable = Order::where('tenant_id', $tenantId)
            ->where('status', Order::STATUS_OPEN)
            ->whereIn('table_id', $tableIds)
            ->with('items.modifiers')
            ->get()
            ->groupBy(fn (Order $o) => (int) $o->table_id);

        foreach ($tables as $table) {
            if ($table->open_orders_count > 0) {
                $orders = $openOrdersByTable->get($table->id, collect());
                $calculatedTotal = (float) round($orders->sum(fn (Order $order) => $order->total));
                if ($table->total_amount != $calculatedTotal) {
                    $table->total_amount = $calculatedTotal;
                    $table->save();
                }
            } else {
                if ($table->total_amount > 0) {
                    $table->total_amount = 0;
                    $table->save();
                }
            }
        }

        \Log::info('ListTables: Optimized query', [
            'tables_count' => $tables->count(),
            'tables' => $tables->map(function($table) {
                return [
                    'id' => $table->id,
                    'name' => $table->name,
                    'status' => $table->status,
                    'current_order_id' => $table->current_order_id,
                    'open_orders_count' => $table->open_orders_count
                ];
            })
        ]);

        // Update table status based on open orders count (no additional DB queries)
        $tablesToUpdate = [];
        foreach ($tables as $table) {
            $newStatus = $table->open_orders_count > 0 ? 'occupied' : 'free';
            if ($table->status !== $newStatus) {
                $tablesToUpdate[] = [
                    'id' => $table->id,
                    'status' => $newStatus,
                    'current_order_id' => $newStatus === 'free' ? null : $table->current_order_id
                ];
                $table->status = $newStatus;
                if ($newStatus === 'free') {
                    $table->current_order_id = null;
                }
            }
        }

        // Batch update tables that need status changes
        if (!empty($tablesToUpdate)) {
            foreach ($tablesToUpdate as $update) {
                RestaurantTable::where('id', $update['id'])
                    ->update([
                        'status' => $update['status'],
                        'current_order_id' => $update['current_order_id']
                    ]);
            }
            \Log::info('ListTables: Updated table statuses', $tablesToUpdate);
        }

        return response()->json([
            'success' => true,
            'tables' => $tables->map(function($table) {
                return [
                    'id' => $table->id,
                    'name' => $table->name,
                    'status' => $table->status,
                    'current_order_id' => $table->current_order_id,
                    'total_amount' => (float) $table->total_amount,
                    'capacity' => $table->capacity,
                    'shape' => $table->shape,
                    'type' => $table->type,
                    'description' => $table->description,
                    // Omit orders here — loading all orders per table made list refresh very slow; total_amount is authoritative.
                    'orders' => [],
                    'is_active' => $table->is_active,
                    'created_at' => $table->created_at,
                    'updated_at' => $table->updated_at,
                ];
            }),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get all occupied tables with their latest orders
     */
    public function getOccupiedTables(Request $request)
    {
        $tenantId = $this->getTenantId();
        $outletId = $request->get('outlet_id', 1);

        try {
            // Get occupied tables with their latest OPEN order
            $occupiedTables = RestaurantTable::where('tenant_id', $tenantId)
                ->where('outlet_id', $outletId)
                ->where('status', 'occupied')
                ->whereHas('orders', function($query) {
                    $query->where('status', 'OPEN');
                })
                ->with(['orders' => function($query) {
                    $query->where('status', 'OPEN')
                          ->latest()
                          ->limit(1)
                          ->with(['items', 'items.modifiers']);
                }])
                ->get()
                ->map(function($table) {
                    return [
                        'id' => $table->id,
                        'name' => $table->name,
                        'status' => $table->status,
                        'capacity' => $table->capacity,
                        'latest_order' => $table->orders->first(),
                        'total_amount' => $table->total_amount,
                        'created_at' => $table->created_at,
                        'updated_at' => $table->updated_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'occupied_tables' => $occupiedTables,
                'count' => $occupiedTables->count(),
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting occupied tables', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenantId,
                'outlet_id' => $outletId
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to get occupied tables',
                'occupied_tables' => []
            ], 500);
        }
    }

    /**
     * Get orders for a specific table
     */
    public function getTableOrders(Request $request)
    {
        $tenantId = $this->getTenantId();
        $tableId = $request->get('table_id');

        \Log::info('=== GET TABLE ORDERS API CALL START ===');
        \Log::info('Table ID:', ['table_id' => $tableId]);
        \Log::info('Tenant ID:', ['tenant_id' => $tenantId]);

        try {
            // Get the table first to verify it exists and belongs to tenant
            $table = RestaurantTable::where('tenant_id', $tenantId)
                ->where('id', $tableId)
                ->first();

            if (!$table) {
                \Log::warning('Table not found', ['table_id' => $tableId, 'tenant_id' => $tenantId]);
                return response()->json([
                    'success' => false,
                    'error' => 'Table not found',
                    'orders' => []
                ], 404);
            }

            // Get only the latest OPEN order for this table (current active order)
            $orders = Order::with(['items.item', 'items.modifiers'])
                ->where('tenant_id', $tenantId)
                ->where('table_id', $tableId)
                ->where('status', 'OPEN')
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->get();

            \Log::info('Found OPEN orders for table', [
                'table_id' => $tableId,
                'open_orders_count' => $orders->count(),
                'orders' => $orders->toArray()
            ]);

            return response()->json([
                'success' => true,
                'orders' => $orders,
                'table_id' => $tableId,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ FAILED TO GET TABLE ORDERS', [
                'table_id' => $tableId,
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'orders' => []
            ], 500);
        }
    }

    /**
     * Update an existing order
     */
    public function updateOrder(Request $request, $orderId)
    {
        \Log::info('=== UPDATE ORDER API CALL ===');
        \Log::info('Order ID parameter:', ['order_id' => $orderId, 'type' => gettype($orderId)]);
        \Log::info('Request URL:', ['url' => $request->url()]);
        \Log::info('Request path:', ['path' => $request->path()]);
        \Log::info('Route parameters:', $request->route()->parameters());
        
        // Get the correct order ID from route parameters
        $correctOrderId = $request->route('orderId');
        \Log::info('Correct Order ID from route:', ['order_id' => $correctOrderId, 'type' => gettype($correctOrderId)]);
        
        // Use the correct order ID
        $orderId = $correctOrderId;
        
        $tenantId = $this->getTenantId();
        
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required_with:items|exists:items,id',
            'items.*.qty' => 'required_with:items|integer|min:1',
            'discount_code' => 'nullable|string|max:50',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            $order = Order::where('tenant_id', $tenantId)
                ->where('id', $orderId)
                ->firstOrFail();

            // Update order details
            $order->update([
                'customer_id' => $request->input('customer_id'),
                'customer_name' => $request->input('customer_name'),
                'customer_phone' => $request->input('customer_phone'),
                'customer_address' => $request->input('customer_address'),
                'special_instructions' => $request->input('special_instructions'),
            ]);

            // Update order items if provided
            if ($request->has('items')) {
                // Remove existing items
                $order->items()->delete();
                
                // Add new items
                foreach ($request->input('items') as $itemData) {
                    $order->items()->create([
                        'item_id' => $itemData['item_id'],
                        'qty' => $itemData['qty'],
                        'price' => Item::find($itemData['item_id'])->price ?? 0,
                    ]);
                }
                
                // Update table total amount if this is a dine-in order
                if ($order->mode === 'DINE_IN' && $order->table_id) {
                    $table = $order->table;
                    if ($table) {
                        $table->updateTotalAmount();
                    }
                }
            }

            return response()->json([
                'success' => true,
                'order' => $order->load('items'),
                'message' => 'Order updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to update order', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get table status with order information
     */
    public function getTableStatus(Request $request)
    {
        $tenantId = $this->getTenantId();
        $tableId = $request->get('table_id');

        try {
            $table = RestaurantTable::where('tenant_id', $tenantId)
                ->where('id', $tableId)
                ->with(['orders' => function($query) {
                    $query->where('status', 'OPEN')
                          ->latest()
                          ->limit(1);
                }])
                ->first();

            if (!$table) {
                return response()->json([
                    'success' => false,
                    'error' => 'Table not found'
                ], 404);
            }

            $activeOrder = $table->getActiveOrder();

            if ($activeOrder && ! $table->current_order_id) {
                $table->current_order_id = $activeOrder->id;
                $table->save();
            }
            
            return response()->json([
                'success' => true,
                'table' => [
                    'id' => $table->id,
                    'name' => $table->name,
                    'status' => $table->status,
                    'current_order_id' => $table->current_order_id,
                    'total_amount' => $table->total_amount,
                    'has_active_order' => $activeOrder !== null,
                    'active_order' => $activeOrder ? [
                        'id' => $activeOrder->id,
                        'status' => $activeOrder->status,
                        'total' => $activeOrder->total,
                        'created_at' => $activeOrder->created_at,
                        'items_count' => $activeOrder->items->count()
                    ] : null
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to get table status', [
                'table_id' => $tableId,
                'tenant_id' => $tenantId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add items to an existing dine-in order
     */
    public function addItemsToOrder(Request $request)
    {
        $tenantId = $this->getTenantId();
        
        $request->validate([
            'table_id' => 'required|exists:restaurant_tables,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.variant_id' => 'nullable|exists:item_variants,id',
            'items.*.modifiers' => 'nullable|array',
            'items.*.modifiers.*' => 'exists:modifiers,id',
            'items.*.note' => 'nullable|string|max:255',
        ]);

        try {
            $table = RestaurantTable::where('tenant_id', $tenantId)
                ->findOrFail($request->input('table_id'));

            // Get the active order for this table
            $order = $table->getActiveOrder();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'error' => 'No active order found for this table'
                ], 404);
            }

            if ($order->status !== 'OPEN') {
                return response()->json([
                    'success' => false,
                    'error' => 'Order is not open for modifications'
                ], 400);
            }

            $addedItems = [];
            $orderService = app(\App\Services\OrderService::class);

            // Batch add: avoid OrderItemObserver calling updateTotalAmount() once per line (very slow).
            OrderItem::withoutEvents(function () use ($request, $order, $orderService, &$addedItems) {
                foreach ($request->input('items') as $itemData) {
                    $item = \App\Models\Item::find($itemData['item_id']);
                    $variant = isset($itemData['variant_id']) ? \App\Models\ItemVariant::find($itemData['variant_id']) : null;

                    $orderItem = $orderService->addItem(
                        $order,
                        $item,
                        $itemData['qty'],
                        $variant,
                        $itemData['modifiers'] ?? [],
                        $itemData['note'] ?? null
                    );

                    $addedItems[] = $orderItem->load('item', 'variant', 'modifiers.modifier');
                }
            });

            // Refresh the order with all items
            $order->refresh();
            $order->load(['items.item', 'items.variant', 'items.modifiers.modifier']);

            $table->updateTotalAmount();
            $table->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Items added successfully to the order',
                'order' => $order,
                'added_items' => $addedItems,
                'total' => $order->total,
                'table' => [
                    'id' => $table->id,
                    'total_amount' => (float) $table->total_amount,
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to add items to order', [
                'table_id' => $request->input('table_id'),
                'tenant_id' => $tenantId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the current order for a table
     */
    public function getTableOrder(Request $request)
    {
        $tenantId = $this->getTenantId();
        $tableId = $request->get('table_id');

        try {
            $table = RestaurantTable::where('tenant_id', $tenantId)
                ->findOrFail($tableId);

            $order = $table->getActiveOrder();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'error' => 'No active order found for this table'
                ], 404);
            }

            $order->load(['items.item', 'items.variant', 'items.modifiers.modifier', 'orderType']);

            return response()->json([
                'success' => true,
                'order' => $order,
                'table' => [
                    'id' => $table->id,
                    'name' => $table->name,
                    'status' => $table->status
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to get table order', [
                'table_id' => $tableId,
                'tenant_id' => $tenantId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm a QR/mobile order: assign table (dine-in), tag shift, then allow KOT (state NEW).
     */
    public function approveQrOrder(Request $request, $orderId)
    {
        try {
            $orderId = (int) $orderId;
            if ($orderId < 1) {
                return response()->json(['error' => 'Invalid order id'], 422);
            }

            $tenantId = $this->getTenantId();

            $terminalUser = TerminalSession::terminalUserFromHttpRequest($request);
            if ($terminalUser) {
                try {
                    $terminalUser->ensureLinkedShadowUser();
                } catch (\Throwable $e) {
                    \Log::warning('approveQrOrder: ensureLinkedShadowUser', ['message' => $e->getMessage()]);
                }
                $terminalUser->refresh();
            }

            $userId = auth()->check()
                ? (int) auth()->id()
                : (int) ($terminalUser?->user_id ?? 0);

            // Web user, or terminal session (shift may be opened by another user — still allow confirm)
            if ($userId === 0 && ! $terminalUser) {
                return response()->json([
                    'error' => 'Authentication required. Sign in to the POS terminal again, then retry.',
                ], 401);
            }

            $order = Order::where('tenant_id', $tenantId)->findOrFail($orderId);

            if ($order->source !== 'mobile_qr' || ! $order->isPendingQrApproval()) {
                return response()->json(['error' => 'Order is not awaiting QR approval'], 422);
            }

            $validated = $request->validate([
                'shift_id' => 'required|integer|exists:shifts,id',
                'table_id' => 'nullable|integer',
            ]);

            $shift = Shift::withoutGlobalScope('tenant')
                ->where('id', $validated['shift_id'])
                ->where('tenant_id', $tenantId)
                ->where('outlet_id', $order->outlet_id)
                ->whereNull('closed_at')
                ->first();

            if (! $shift) {
                return response()->json([
                    'error' => 'This shift is closed or does not match the order outlet. Refresh and try again.',
                ], 422);
            }

            $tableId = $validated['table_id'] ?? null;

            if ($order->mode === 'DINE_IN' && (empty($tableId) || $tableId < 1)) {
                return response()->json(['error' => 'Assign a table for dine-in orders before confirming'], 422);
            }

            if (! empty($tableId)) {
                $ok = RestaurantTable::where('tenant_id', $tenantId)
                    ->where('outlet_id', $order->outlet_id)
                    ->whereKey($tableId)
                    ->exists();
                if (! $ok) {
                    return response()->json([
                        'error' => 'Selected table is not valid for this order\'s outlet.',
                    ], 422);
                }
            }

            DB::transaction(function () use ($tableId, $order, $tenantId, $shift, $terminalUser) {
                $meta = $order->meta ?? [];
                unset($meta['qr_has_unsent_items'], $meta['qr_new_items_at']);
                $meta['shift_id'] = $shift->id;
                $meta['qr_approved_at'] = now()->toIso8601String();
                $meta['qr_confirmed_by_user_id'] = auth()->id() ?? ($terminalUser?->user_id ?? null);
                if ($terminalUser) {
                    $meta['qr_confirmed_by_terminal_user_id'] = $terminalUser->id;
                }

                $updates = [
                    'state' => 'NEW',
                    'meta' => $meta,
                ];

                if (! empty($tableId)) {
                    $table = RestaurantTable::where('tenant_id', $tenantId)
                        ->where('outlet_id', $order->outlet_id)
                        ->whereKey($tableId)
                        ->firstOrFail();

                    $conflict = Order::where('tenant_id', $tenantId)
                        ->where('table_id', $table->id)
                        ->where('status', Order::STATUS_OPEN)
                        ->where('id', '!=', $order->id)
                        ->exists();

                    if ($conflict) {
                        throw new \RuntimeException('That table already has an open order. Close or bill it first.');
                    }

                    $updates['table_id'] = $table->id;
                    $updates['table_no'] = $table->name;

                    $table->update([
                        'status' => 'occupied',
                        'current_order_id' => $order->id,
                    ]);
                }

                $order->update($updates);
            });
        } catch (ValidationException $e) {
            $first = collect($e->errors())->flatten()->first();

            return response()->json([
                'error' => $first ?: $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            \Log::error('approveQrOrder failed', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => $e->getMessage() ?: 'Server error while confirming order',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'order' => $order->fresh()->load(['items.item', 'table', 'outlet']),
        ]);
    }

    private function resolveActiveShiftId(int $tenantId, ?int $outletId): ?int
    {
        $query = Shift::where('tenant_id', $tenantId)->whereNull('closed_at');
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        return optional($query->latest('id')->first())->id;
    }

    private function resolveCashierId(): ?int
    {
        $sessionToken = request()->header('X-Terminal-Session-Token') ?? request()->cookie('terminal_session_token');
        if ($sessionToken && strlen($sessionToken) > 100) {
            try {
                $sessionToken = decrypt($sessionToken);
            } catch (\Throwable $e) {
                $sessionToken = request()->header('X-Terminal-Session-Token');
            }
        }

        if ($sessionToken) {
            $session = \App\Models\TerminalSession::where('session_token', $sessionToken)
                ->where('expires_at', '>', now())
                ->with('terminalUser')
                ->first();
            if ($session && $session->terminalUser) {
                return $session->terminalUser->id;
            }
        }

        return optional(auth()->user())->id;
    }
}
