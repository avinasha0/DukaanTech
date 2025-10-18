<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shift;
use App\Services\ShiftService;
use App\Services\SetupStatusService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        private ShiftService $shiftService,
        private SetupStatusService $setupStatusService
    ) {}

    public function index()
    {
        $tenant = app('tenant');
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        // Get setup status for dynamic getting started steps
        $setupStatus = $this->setupStatusService->getSetupStepsStatus($tenant);
        $nextStep = $this->setupStatusService->getNextStep($tenant);
        $completionPercentage = $this->setupStatusService->getCompletionPercentage($tenant);
        
        return view('tenant.dashboard', compact('tenant', 'setupStatus', 'nextStep', 'completionPercentage'));
    }

    public function menu()
    {
        $tenant = app('tenant');
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        return view('tenant.menu', compact('tenant'));
    }

    public function items()
    {
        $tenantId = app('tenant.id');
        
        $items = \App\Models\Item::where('tenant_id', $tenantId)->get();
        
        return response()->json($items);
    }

    public function categories()
    {
        $tenantId = app('tenant.id');
        
        $categories = \App\Models\Category::where('tenant_id', $tenantId)->get();
        
        return response()->json($categories);
    }

    public function statistics()
    {
        $tenantId = app('tenant.id');
        
        // Get today's date range
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        
        // Calculate today's sales and orders
        $todayOrders = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->where('status', '!=', 'CANCELLED')
            ->with('items')
            ->get();
        
        $todaySales = $todayOrders->sum(function ($order) {
            return $order->items->sum(function ($item) {
                return $item->price * $item->qty;
            });
        });
        
        $ordersToday = $todayOrders->count();
        
        // Calculate average order value
        $avgOrderValue = $ordersToday > 0 ? $todaySales / $ordersToday : 0;
        
        // Get current shift status
        $currentShift = Shift::where('tenant_id', $tenantId)
            ->whereNull('closed_at')
            ->first();
        
        $shiftStatus = $currentShift ? 'Open' : 'Closed';
        
        return response()->json([
            'today_sales' => round($todaySales, 2),
            'orders_today' => $ordersToday,
            'avg_order_value' => round($avgOrderValue, 2),
            'current_shift' => $shiftStatus,
            'shift_id' => $currentShift ? $currentShift->id : null,
            'last_updated' => now()->toISOString()
        ]);
    }

    public function getCurrentShift()
    {
        $tenantId = app('tenant.id');
        
        // Get the first outlet for this tenant (assuming single outlet for now)
        $outlet = \App\Models\Outlet::where('tenant_id', $tenantId)->first();
        
        if (!$outlet) {
            return response()->json(['error' => 'No outlet found'], 404);
        }
        
        // Check if this is a terminal user request
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
        
        // If terminal user, look for shift by user, otherwise by outlet
        if ($terminalUser && $terminalUser->user_id) {
            $shift = \App\Models\Shift::where('tenant_id', $tenantId)
                ->where('opened_by', $terminalUser->user_id)
                ->whereNull('closed_at')
                ->first();
        } else {
            $shift = $this->shiftService->getCurrentShift($outlet->id);
        }
        
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
        
        $summary = $this->shiftService->getShiftSummary($shift);
        
        return response()->json([
            'shift' => $shift,
            'has_shift' => true,
            'summary' => $summary
        ]);
    }

    public function checkoutShift(Request $request)
    {
        $data = $request->validate([
            'actual_cash' => 'required|numeric|min:0',
        ]);
        
        $tenantId = app('tenant.id');
        
        // Get the first outlet for this tenant (assuming single outlet for now)
        $outlet = \App\Models\Outlet::where('tenant_id', $tenantId)->first();
        
        if (!$outlet) {
            return response()->json(['error' => 'No outlet found'], 404);
        }
        
        // Check if this is a terminal user request
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
        
        // If terminal user, look for shift by user, otherwise by outlet
        if ($terminalUser && $terminalUser->user_id) {
            $shift = \App\Models\Shift::where('tenant_id', $tenantId)
                ->where('opened_by', $terminalUser->user_id)
                ->whereNull('closed_at')
                ->first();
        } else {
            $shift = $this->shiftService->getCurrentShift($outlet->id);
        }
        
        if (!$shift) {
            return response()->json(['error' => 'No open shift found'], 404);
        }
        
        if ($shift->closed_at) {
            return response()->json(['error' => 'Shift is already closed'], 400);
        }
        
        $shift = $this->shiftService->closeShift($shift, $data['actual_cash']);
        $summary = $this->shiftService->getShiftSummary($shift);
        
        return response()->json([
            'shift' => $shift,
            'summary' => $summary,
            'message' => 'Shift closed successfully'
        ]);
    }

    public function getCustomers()
    {
        $tenantId = app('tenant.id');
        
        // Get unique customers from orders
        $customers = \App\Models\Order::where('tenant_id', $tenantId)
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
    }

}