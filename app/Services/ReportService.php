<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderType;
use App\Models\Shift;
use Carbon\Carbon;

class ReportService
{
    public function getDailySales(int $outletId, Carbon $date): array
    {
        $startOfDay = $date->startOfDay();
        
        // If it's today, use current time as end time, otherwise use end of day
        $endTime = $date->isToday() ? now() : $date->endOfDay();
        
        \Log::info('Getting daily sales', [
            'outlet_id' => $outletId,
            'date' => $date->format('Y-m-d'),
            'start_of_day' => $startOfDay->toISOString(),
            'end_time' => $endTime->toISOString(),
            'is_today' => $date->isToday(),
            'tenant_id' => app('tenant.id')
        ]);
        
        // Query orders instead of bills since bills table is empty
        $orders = Order::where('tenant_id', app('tenant.id'))
            ->where('outlet_id', $outletId)
            ->where('status', 'PAID') // Only get paid orders
            ->whereBetween('created_at', [$startOfDay, $endTime])
            ->with('items')
            ->get();
            
        \Log::info('Daily sales query result', [
            'orders_count' => $orders->count(),
            'total_sales' => $orders->sum('total'),
            'is_today' => $date->isToday(),
            'time_range' => $startOfDay->format('H:i') . ' to ' . $endTime->format('H:i')
        ]);
        
        // Calculate totals from orders
        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Calculate payment methods from orders
        $paymentMethods = [
            'cash' => $orders->where('payment_method', 'CASH')->sum('total'),
            'card' => $orders->where('payment_method', 'CARD')->sum('total'),
            'upi' => $orders->where('payment_method', 'UPI')->sum('total'),
            'wallet' => $orders->where('payment_method', 'WALLET')->sum('total'),
            'other' => $orders->where('payment_method', 'OTHER')->sum('total'),
        ];
        
        // Calculate tax and discount from order items
        $taxTotal = $orders->sum(function($order) {
            return $order->items->sum(function($item) {
                return ($item->qty * $item->price) * ($item->tax_rate / 100);
            });
        });
        
        $discountTotal = $orders->sum(function($order) {
            return $order->items->sum('discount');
        });
        
        $result = [
            'date' => $date->format('Y-m-d'),
            'total_sales' => $totalSales,
            'total_bills' => $totalOrders,
            'average_bill_value' => $averageOrderValue,
            'payment_methods' => $paymentMethods,
            'tax_total' => $taxTotal,
            'discount_total' => $discountTotal,
        ];
        
        // Log if no data found
        if ($orders->count() === 0) {
            \Log::warning('No sales data found for date', [
                'outlet_id' => $outletId,
                'date' => $date->format('Y-m-d'),
                'is_today' => $date->isToday(),
                'is_weekend' => $date->isWeekend(),
                'time_range' => $startOfDay->format('H:i') . ' to ' . $endTime->format('H:i'),
                'tenant_id' => app('tenant.id'),
                'note' => 'Querying orders table instead of bills table'
            ]);
        }
        
        return $result;
    }

    public function getTopSellingItems(int $outletId, Carbon $from, Carbon $to, int $limit = 10): array
    {
        $items = OrderItem::where('tenant_id', app('tenant.id'))
            ->whereHas('order', function ($query) use ($outletId, $from, $to) {
                $query->where('outlet_id', $outletId)
                    ->where('status', 'PAID') // Only get paid orders
                    ->whereBetween('created_at', [$from, $to]);
            })
            ->with('item')
            ->get()
            ->groupBy('item_id')
            ->map(function ($group) {
                $item = $group->first()->item;
                return [
                    'item' => $item,
                    'total_qty' => $group->sum('qty'),
                    'total_revenue' => $group->sum(function ($orderItem) {
                        return $orderItem->qty * $orderItem->price;
                    }),
                    'order_count' => $group->count(),
                ];
            })
            ->sortByDesc('total_qty')
            ->take($limit)
            ->values();
            
        return $items->toArray();
    }

    public function getHourlySales(int $outletId, Carbon $date): array
    {
        $startOfDay = $date->startOfDay();
        $endOfDay = $date->endOfDay();
        
        $bills = Bill::where('tenant_id', app('tenant.id'))
            ->where('outlet_id', $outletId)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->get()
            ->groupBy(function ($bill) {
                return $bill->created_at->format('H');
            });
            
        $hourlyData = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourKey = str_pad($hour, 2, '0', STR_PAD_LEFT);
            $hourBills = $bills->get($hourKey, collect());
            
            $hourlyData[] = [
                'hour' => $hourKey,
                'sales' => $hourBills->sum('net_total'),
                'bills' => $hourBills->count(),
            ];
        }
        
        return $hourlyData;
    }

    public function getShiftReport(Shift $shift): array
    {
        $endTime = $shift->closed_at ?? now();
        
        $bills = $shift->outlet->bills()
            ->whereBetween('created_at', [$shift->created_at, $endTime])
            ->get();
            
        $payments = $bills->pluck('payments')->flatten();
        
        return [
            'shift_id' => $shift->id,
            'opened_at' => $shift->created_at,
            'closed_at' => $shift->closed_at,
            'opened_by' => $shift->openedBy->name,
            'total_sales' => $bills->sum('net_total'),
            'total_bills' => $bills->count(),
            'payment_breakdown' => [
                'cash' => $payments->where('method', 'CASH')->sum('amount'),
                'card' => $payments->where('method', 'CARD')->sum('amount'),
                'upi' => $payments->where('method', 'UPI')->sum('amount'),
                'wallet' => $payments->where('method', 'WALLET')->sum('amount'),
                'other' => $payments->where('method', 'OTHER')->sum('amount'),
            ],
            'cash_management' => [
                'opening_float' => $shift->opening_float,
                'expected_cash' => $shift->expected_cash,
                'actual_cash' => $shift->actual_cash,
                'variance' => $shift->variance,
            ],
        ];
    }

    public function getOrderSummaryReport(int $outletId, Carbon $from, Carbon $to, array $orderTypes = []): array
    {
        $query = Order::where('tenant_id', app('tenant.id'))
            ->where('outlet_id', $outletId)
            ->whereBetween('created_at', [$from, $to])
            ->with(['orderType', 'items', 'bill']);

        // Filter by order types if specified
        if (!empty($orderTypes)) {
            $query->whereIn('order_type_id', $orderTypes);
        }

        $orders = $query->get();

        // Group orders by order type
        $orderTypeSummary = $orders->groupBy('order_type_id')->map(function ($typeOrders, $typeId) {
            $orderType = $typeOrders->first()->orderType;
            $totalOrders = $typeOrders->count();
            $totalRevenue = $typeOrders->sum(function ($order) {
                return $order->bill ? $order->bill->net_total : 0;
            });
            $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

            // Get table numbers for dine-in orders
            $tableNumbers = $typeOrders->where('table_no', '!=', null)
                ->pluck('table_no')
                ->unique()
                ->values();

            // Get delivery addresses for delivery orders
            $deliveryAddresses = $typeOrders->where('delivery_address', '!=', null)
                ->pluck('delivery_address')
                ->unique()
                ->values();

            return [
                'order_type' => $orderType,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'average_order_value' => $averageOrderValue,
                'table_numbers' => $tableNumbers,
                'delivery_addresses' => $deliveryAddresses,
                'orders' => $typeOrders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'customer_name' => $order->customer_name,
                        'customer_phone' => $order->customer_phone,
                        'table_no' => $order->table_no,
                        'delivery_address' => $order->delivery_address,
                        'delivery_fee' => $order->delivery_fee,
                        'special_instructions' => $order->special_instructions,
                        'created_at' => $order->created_at,
                        'total_amount' => $order->bill ? $order->bill->net_total : 0,
                        'payment_method' => $order->payment_method,
                        'state' => $order->state,
                    ];
                })
            ];
        });

        // Overall summary
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum(function ($order) {
            return $order->bill ? $order->bill->net_total : 0;
        });
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Payment method breakdown
        $paymentMethods = $orders->groupBy('payment_method')->map(function ($orders) {
            return $orders->sum(function ($order) {
                return $order->bill ? $order->bill->net_total : 0;
            });
        });

        return [
            'period' => [
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d')
            ],
            'summary' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'average_order_value' => $averageOrderValue,
                'payment_methods' => $paymentMethods,
            ],
            'order_types' => $orderTypeSummary->values(),
            'daily_breakdown' => $this->getDailyOrderBreakdown($outletId, $from, $to, $orderTypes),
        ];
    }

    private function getDailyOrderBreakdown(int $outletId, Carbon $from, Carbon $to, array $orderTypes = []): array
    {
        $dailyData = [];
        $currentDate = $from->copy();

        while ($currentDate->lte($to)) {
            $dayOrders = Order::where('tenant_id', app('tenant.id'))
                ->where('outlet_id', $outletId)
                ->whereDate('created_at', $currentDate)
                ->with(['orderType', 'bill']);

            if (!empty($orderTypes)) {
                $dayOrders->whereIn('order_type_id', $orderTypes);
            }

            $orders = $dayOrders->get();

            $dailyData[] = [
                'date' => $currentDate->format('Y-m-d'),
                'total_orders' => $orders->count(),
                'total_revenue' => $orders->sum(function ($order) {
                    return $order->bill ? $order->bill->net_total : 0;
                }),
                'order_types' => $orders->groupBy('order_type_id')->map(function ($typeOrders, $typeId) {
                    $orderType = $typeOrders->first()->orderType;
                    return [
                        'name' => $orderType->name,
                        'count' => $typeOrders->count(),
                        'revenue' => $typeOrders->sum(function ($order) {
                            return $order->bill ? $order->bill->net_total : 0;
                        })
                    ];
                })->values()
            ];

            $currentDate->addDay();
        }

        return $dailyData;
    }
}
