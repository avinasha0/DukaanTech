<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Item;
use App\Models\Category;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $tenant = app('tenant');
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        return view('tenant.analytics.index', compact('tenant'));
    }

    public function getSalesData(Request $request)
    {
        $tenantId = app('tenant.id');
        $period = $request->get('period', '7days'); // 7days, 30days, 90days, 1year
        
        $endDate = Carbon::now();
        $startDate = match($period) {
            '7days' => $endDate->copy()->subDays(7),
            '30days' => $endDate->copy()->subDays(30),
            '90days' => $endDate->copy()->subDays(90),
            '1year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subDays(7)
        };

        // Get daily sales data
        $orders = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'CANCELLED')
            ->with('items')
            ->get();
        
        $salesData = $orders->groupBy(function ($order) {
            return $order->created_at->format('Y-m-d');
        })->map(function ($dayOrders, $date) {
            $totalSales = $dayOrders->sum(function ($order) {
                return $order->items->sum('total'); // Use the getTotalAttribute() method
            });
            
            return (object) [
                'date' => $date,
                'orders_count' => $dayOrders->count(),
                'total_sales' => $totalSales
            ];
        })->values();

        // Get hourly sales data for today
        $todayStart = Carbon::today();
        $todayEnd = Carbon::tomorrow();
        
        $todayOrders = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', '!=', 'CANCELLED')
            ->with('items')
            ->get();
        
        $hourlyData = $todayOrders->groupBy(function ($order) {
            return $order->created_at->format('H');
        })->map(function ($hourOrders, $hour) {
            $totalSales = $hourOrders->sum(function ($order) {
                return $order->items->sum('total'); // Use the getTotalAttribute() method
            });
            
            return (object) [
                'hour' => (int) $hour,
                'orders_count' => $hourOrders->count(),
                'total_sales' => $totalSales
            ];
        })->values();

        return response()->json([
            'daily_sales' => $salesData,
            'hourly_sales' => $hourlyData,
            'period' => $period,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString()
        ]);
    }

    public function getTopItems(Request $request)
    {
        $tenantId = app('tenant.id');
        $period = $request->get('period', '30days');
        
        $endDate = Carbon::now();
        $startDate = match($period) {
            '7days' => $endDate->copy()->subDays(7),
            '30days' => $endDate->copy()->subDays(30),
            '90days' => $endDate->copy()->subDays(90),
            '1year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subDays(30)
        };

        $topItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->where('orders.tenant_id', $tenantId)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.state', '!=', 'CANCELLED')
            ->select(
                'items.name',
                'items.id',
                DB::raw('SUM(order_items.qty) as total_quantity'),
                DB::raw('SUM(order_items.qty * order_items.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return response()->json([
            'top_items' => $topItems,
            'period' => $period
        ]);
    }

    public function getCategoryPerformance(Request $request)
    {
        $tenantId = app('tenant.id');
        $period = $request->get('period', '30days');
        
        $endDate = Carbon::now();
        $startDate = match($period) {
            '7days' => $endDate->copy()->subDays(7),
            '30days' => $endDate->copy()->subDays(30),
            '90days' => $endDate->copy()->subDays(90),
            '1year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subDays(30)
        };

        $categoryPerformance = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->where('orders.tenant_id', $tenantId)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.state', '!=', 'CANCELLED')
            ->select(
                'categories.name',
                'categories.id',
                DB::raw('SUM(order_items.qty) as total_quantity'),
                DB::raw('SUM(order_items.qty * order_items.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        return response()->json([
            'category_performance' => $categoryPerformance,
            'period' => $period
        ]);
    }

    public function getShiftAnalytics(Request $request)
    {
        $tenantId = app('tenant.id');
        $period = $request->get('period', '30days');
        
        $endDate = Carbon::now();
        $startDate = match($period) {
            '7days' => $endDate->copy()->subDays(7),
            '30days' => $endDate->copy()->subDays(30),
            '90days' => $endDate->copy()->subDays(90),
            '1year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subDays(30)
        };

        $shiftAnalytics = Shift::where('tenant_id', $tenantId)
            ->whereBetween('opened_at', [$startDate, $endDate])
            ->whereNotNull('closed_at')
            ->select(
                DB::raw('DATE(opened_at) as date'),
                DB::raw('COUNT(*) as shifts_count'),
                DB::raw('SUM(total_sales) as total_sales'),
                DB::raw('AVG(total_sales) as avg_sales_per_shift'),
                DB::raw('SUM(expected_cash) as total_expected_cash'),
                DB::raw('SUM(actual_cash) as total_actual_cash')
            )
            ->groupBy(DB::raw('DATE(opened_at)'))
            ->orderBy('date')
            ->get();

        return response()->json([
            'shift_analytics' => $shiftAnalytics,
            'period' => $period
        ]);
    }

    public function getOrderTypeAnalytics(Request $request)
    {
        $tenantId = app('tenant.id');
        $period = $request->get('period', '30days');
        
        // Debug logging
        \Log::info('Analytics getOrderTypeAnalytics called', [
            'tenant_id' => $tenantId,
            'period' => $period,
            'request_url' => $request->fullUrl()
        ]);
        
        $endDate = Carbon::now();
        $startDate = match($period) {
            '7days' => $endDate->copy()->subDays(7),
            '30days' => $endDate->copy()->subDays(30),
            '90days' => $endDate->copy()->subDays(90),
            '1year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subDays(30)
        };

        try {
            $orders = Order::where('tenant_id', $tenantId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'CANCELLED')
                ->with(['items', 'orderType'])
                ->get();
            
            \Log::info('Orders loaded for order type analytics', [
                'orders_count' => $orders->count(),
                'tenant_id' => $tenantId
            ]);
            
            $orderTypeAnalytics = $orders->groupBy('order_type_id')->map(function ($typeOrders, $orderTypeId) {
                $orderType = $typeOrders->first()->orderType;
                $totalSales = $typeOrders->sum(function ($order) {
                    return $order->items->sum('total'); // Use the getTotalAttribute() method
                });
                
                return (object) [
                    'id' => $orderTypeId,
                    'name' => $orderType ? $orderType->name : 'Unknown',
                    'orders_count' => $typeOrders->count(),
                    'total_sales' => $totalSales,
                    'avg_order_value' => $typeOrders->count() > 0 ? $totalSales / $typeOrders->count() : 0
                ];
            })->values()->sortByDesc('total_sales');
            
        } catch (\Exception $e) {
            \Log::error('Error in getOrderTypeAnalytics', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenantId,
                'trace' => $e->getTraceAsString()
            ]);
            
            $orderTypeAnalytics = collect();
        }

        $result = [
            'order_type_analytics' => $orderTypeAnalytics,
            'period' => $period
        ];
        
        // Debug logging
        \Log::info('Analytics order type analytics result', [
            'tenant_id' => $tenantId,
            'result' => $result,
            'orders_count' => $orders->count()
        ]);

        return response()->json($result);
    }

    public function getSummaryStats(Request $request)
    {
        $tenantId = app('tenant.id');
        $period = $request->get('period', '30days');
        
        // Debug logging
        \Log::info('Analytics getSummaryStats called', [
            'tenant_id' => $tenantId,
            'period' => $period,
            'request_url' => $request->fullUrl()
        ]);
        
        $endDate = Carbon::now();
        $startDate = match($period) {
            '7days' => $endDate->copy()->subDays(7),
            '30days' => $endDate->copy()->subDays(30),
            '90days' => $endDate->copy()->subDays(90),
            '1year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subDays(30)
        };

        // Get total sales and orders
        $orders = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'CANCELLED')
            ->with('items')
            ->get();
        
        $totalSales = $orders->sum(function ($order) {
            return $order->items->sum('total'); // Use the getTotalAttribute() method
        });

        $totalOrders = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'CANCELLED')
            ->count();

        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Get previous period for comparison
        $previousStartDate = $startDate->copy()->subDays($endDate->diffInDays($startDate));
        $previousEndDate = $startDate;

        $previousOrders = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'CANCELLED')
            ->with('items')
            ->get();
        
        $previousTotalSales = $previousOrders->sum(function ($order) {
            return $order->items->sum('total'); // Use the getTotalAttribute() method
        });

        $previousTotalOrders = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'CANCELLED')
            ->count();

        $salesGrowth = $previousTotalSales > 0 ? 
            (($totalSales - $previousTotalSales) / $previousTotalSales) * 100 : 0;

        $ordersGrowth = $previousTotalOrders > 0 ? 
            (($totalOrders - $previousTotalOrders) / $previousTotalOrders) * 100 : 0;

        $result = [
            'total_sales' => round($totalSales, 2),
            'total_orders' => $totalOrders,
            'avg_order_value' => round($avgOrderValue, 2),
            'sales_growth' => round($salesGrowth, 2),
            'orders_growth' => round($ordersGrowth, 2),
            'period' => $period
        ];
        
        // Debug logging
        \Log::info('Analytics summary stats result', [
            'tenant_id' => $tenantId,
            'result' => $result,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString()
        ]);

        return response()->json($result);
    }
}
