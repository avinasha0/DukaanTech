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
            ->where('orders.status', '!=', Order::STATUS_CANCELLED)
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
            ->where('orders.status', '!=', Order::STATUS_CANCELLED)
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

        $orders = collect();
        try {
            $orders = Order::where('tenant_id', $tenantId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->with(['items.modifiers', 'orderType'])
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

    public function getBusinessTrends(Request $request)
    {
        $tenantId = app('tenant.id');
        $period = $request->get('period', '30days');

        $endDate = Carbon::now();
        $startDate = match ($period) {
            '7days' => $endDate->copy()->subDays(7),
            '30days' => $endDate->copy()->subDays(30),
            '90days' => $endDate->copy()->subDays(90),
            '1year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subDays(30),
        };

        $orders = Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', Order::STATUS_CANCELLED)
            ->with(['items.modifiers'])
            ->get();

        $weekdayBuckets = [];
        for ($i = 1; $i <= 7; $i++) {
            $weekdayBuckets[$i] = ['total_sales' => 0.0, 'orders_count' => 0];
        }
        $weekdayLabels = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'];

        $hourBuckets = [];
        for ($h = 0; $h < 24; $h++) {
            $hourBuckets[$h] = ['total_sales' => 0.0, 'orders_count' => 0];
        }

        $modeBuckets = [];
        $paymentBuckets = [];

        foreach ($orders as $order) {
            $total = $order->items->sum('total');

            $dow = $order->created_at->isoWeekday();
            $weekdayBuckets[$dow]['total_sales'] += $total;
            $weekdayBuckets[$dow]['orders_count']++;

            $h = (int) $order->created_at->format('G');
            $hourBuckets[$h]['total_sales'] += $total;
            $hourBuckets[$h]['orders_count']++;

            $mode = $order->mode ?? 'UNKNOWN';
            if (! isset($modeBuckets[$mode])) {
                $modeBuckets[$mode] = ['total_sales' => 0.0, 'orders_count' => 0];
            }
            $modeBuckets[$mode]['total_sales'] += $total;
            $modeBuckets[$mode]['orders_count']++;

            $pm = $order->payment_method ? (string) $order->payment_method : 'Unspecified';
            if (! isset($paymentBuckets[$pm])) {
                $paymentBuckets[$pm] = ['total_sales' => 0.0, 'orders_count' => 0];
            }
            $paymentBuckets[$pm]['total_sales'] += $total;
            $paymentBuckets[$pm]['orders_count']++;
        }

        $totalSalesAll = max(0.0001, $orders->sum(fn ($o) => $o->items->sum('total')));

        $byWeekday = [];
        foreach ($weekdayLabels as $isoD => $label) {
            $b = $weekdayBuckets[$isoD];
            $c = $b['orders_count'];
            $byWeekday[] = [
                'weekday' => $isoD,
                'label' => $label,
                'total_sales' => round($b['total_sales'], 2),
                'orders_count' => $c,
                'avg_order_value' => $c > 0 ? round($b['total_sales'] / $c, 2) : 0,
            ];
        }

        $hourlyDistribution = [];
        for ($h = 0; $h < 24; $h++) {
            $b = $hourBuckets[$h];
            $c = $b['orders_count'];
            $hourlyDistribution[] = [
                'hour' => $h,
                'label' => sprintf('%02d:00', $h),
                'total_sales' => round($b['total_sales'], 2),
                'orders_count' => $c,
            ];
        }

        $modeDisplay = [
            'DINE_IN' => 'Dine-in',
            'TAKEAWAY' => 'Takeaway',
            'DELIVERY' => 'Delivery',
            'PICKUP' => 'Pickup',
            'UNKNOWN' => 'Other',
        ];

        $modeMix = [];
        foreach ($modeBuckets as $mode => $b) {
            $c = $b['orders_count'];
            $modeMix[] = [
                'mode' => $mode,
                'label' => $modeDisplay[$mode] ?? ucfirst(strtolower(str_replace('_', ' ', (string) $mode))),
                'total_sales' => round($b['total_sales'], 2),
                'orders_count' => $c,
                'pct_revenue' => round(($b['total_sales'] / $totalSalesAll) * 100, 1),
            ];
        }
        usort($modeMix, fn ($a, $b) => $b['total_sales'] <=> $a['total_sales']);

        $paymentMix = [];
        foreach ($paymentBuckets as $pm => $b) {
            $c = $b['orders_count'];
            $paymentMix[] = [
                'payment_method' => $pm,
                'label' => $pm,
                'total_sales' => round($b['total_sales'], 2),
                'orders_count' => $c,
                'pct_revenue' => round(($b['total_sales'] / $totalSalesAll) * 100, 1),
            ];
        }
        usort($paymentMix, fn ($a, $b) => $b['total_sales'] <=> $a['total_sales']);

        $bestWeekday = collect($byWeekday)->sortByDesc('total_sales')->first();

        return response()->json([
            'by_weekday' => $byWeekday,
            'hourly_distribution' => $hourlyDistribution,
            'mode_mix' => $modeMix,
            'payment_mix' => $paymentMix,
            'best_weekday' => $bestWeekday,
            'period' => $period,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);
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
