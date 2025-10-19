<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\OrderType;
use App\Models\Device;
use App\Models\Outlet;
use App\Models\Shift;
use App\Models\RestaurantTable;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Get current shift for the tenant.
     */
    public function currentShift(Request $request)
    {
        $tenantId = $this->getTenantId();
        $outletId = $request->get('outlet_id', 1);
        
        // Get terminal user from session
        $terminalUser = null;
        $sessionToken = request()->header('X-Terminal-Session-Token') ?? request()->cookie('terminal_session_token');
        
        \Log::info('CurrentShift API called', [
            'tenant_id' => $tenantId,
            'outlet_id' => $outletId,
            'session_token_header' => request()->header('X-Terminal-Session-Token'),
            'session_token_cookie' => request()->cookie('terminal_session_token'),
            'session_token_final' => $sessionToken,
            'token_length' => $sessionToken ? strlen($sessionToken) : 0,
            'cookies' => request()->cookies->all(),
            'headers' => request()->headers->all()
        ]);
        
        // If the token looks like a Laravel encrypted cookie, try to decrypt it
        if ($sessionToken && strlen($sessionToken) > 100) {
            try {
                $sessionToken = decrypt($sessionToken);
                \Log::info('Session token decrypted successfully', ['decrypted_length' => strlen($sessionToken)]);
            } catch (\Exception $e) {
                \Log::warning('Failed to decrypt session token', ['error' => $e->getMessage()]);
                // Fall back to header token if cookie decryption fails
                $sessionToken = request()->header('X-Terminal-Session-Token');
            }
        }
        
        if ($sessionToken) {
            $session = \App\Models\TerminalSession::where('session_token', $sessionToken)
                ->where('expires_at', '>', now())
                ->with('terminalUser')
                ->first();
            
            \Log::info('Session lookup result', [
                'session_found' => $session ? true : false,
                'session_id' => $session ? $session->id : null,
                'terminal_user_found' => $session && $session->terminalUser ? true : false,
                'terminal_user_id' => $session && $session->terminalUser ? $session->terminalUser->id : null
            ]);
            
            if ($session && $session->terminalUser) {
                $terminalUser = $session->terminalUser;
            }
        } else {
            \Log::warning('No session token provided');
        }
        
        // If terminal user, look for shift by user, otherwise by outlet
        if ($terminalUser && $terminalUser->user_id) {
            $shift = Shift::where('tenant_id', $tenantId)
                ->where('outlet_id', $outletId)
                ->where('opened_by', $terminalUser->user_id)
                ->whereNull('closed_at')
                ->first();
        } else {
            $shift = Shift::where('tenant_id', $tenantId)
                ->where('outlet_id', $outletId)
                ->whereNull('closed_at')
                ->first();
        }
        
        \Log::info('Shift lookup result', [
            'tenant_id' => $tenantId,
            'outlet_id' => $outletId,
            'terminal_user_id' => $terminalUser ? $terminalUser->id : null,
            'terminal_user_user_id' => $terminalUser ? $terminalUser->user_id : null,
            'shift_found' => $shift ? true : false,
            'shift_id' => $shift ? $shift->id : null,
            'all_shifts_count' => Shift::where('tenant_id', $tenantId)->count(),
            'open_shifts_count' => Shift::where('tenant_id', $tenantId)->whereNull('closed_at')->count()
        ]);
            
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

                // Prevent multiple open orders for the same table
                $hasOpenOrder = $table->orders()->where('status', 'OPEN')->exists();
                if ($hasOpenOrder) {
                    throw new \Exception('Table already has an open order. Please close the current order before placing a new one.');
                }

                // Get the first available order type for this tenant, or create default ones
                $orderType = \App\Models\OrderType::where('tenant_id', $tenantId)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->first();
                
                if (!$orderType) {
                    // Create default order types if none exist
                    $orderType = \App\Models\OrderType::create([
                        'tenant_id' => $tenantId,
                        'name' => 'Dine In',
                        'slug' => 'dine-in',
                        'color' => '#10B981',
                        'is_active' => true,
                        'sort_order' => 1,
                    ]);
                }

                $order = Order::create([
                    'tenant_id' => $tenantId,
                    'outlet_id' => $table->outlet_id ?? 1, // Use table's outlet or default
                    'table_id' => $table->id,
                    'status' => 'OPEN',
                    'order_type_id' => $orderType->id,
                    'mode' => 'DINE_IN',
                ]);

                $table->update([
                    'status' => 'occupied',
                    'current_order_id' => $order->id,
                ]);

                return $order;
            });

            return response()->json([
                'success' => true, 
                'order_id' => $order->id,
                'table_id' => $tableId,
                'message' => 'Order placed successfully'
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
        ]);

        $orderId = $request->input('order_id');
        $status = $request->input('status', 'CLOSED'); // Default to CLOSED if not specified
        $tenantId = $this->getTenantId();
        
        \Log::info('Order ID to close:', ['order_id' => $orderId]);
        \Log::info('Tenant ID:', ['tenant_id' => $tenantId]);

        try {
            $result = DB::transaction(function () use ($orderId, $tenantId, $status) {
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

                $order->update([
                    'status' => $status,
                    'state' => 'CLOSED'
                ]);
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
            'tables' => $tables,
            'timestamp' => now()->toISOString()
        ]);
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
            $orders = Order::with(['items', 'items.modifiers'])
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
}
