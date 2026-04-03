<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ShiftService
{
    /**
     * Diagnostics for API / logs when APP_DEBUG is true (order counts by filter).
     */
    public static function debugShiftSummaryContext(Shift $shift): array
    {
        $tid = $shift->tenant_id;
        $windowEnd = $shift->closed_at ?? now();

        $countOutletEver = Order::withoutGlobalScope('tenant')
            ->where('tenant_id', $tid)
            ->where('outlet_id', $shift->outlet_id)
            ->count();

        $countInWindow = (new self)->baseShiftOrdersQuery($shift)->count();

        $countOutletLast24h = Order::withoutGlobalScope('tenant')
            ->where('tenant_id', $tid)
            ->where('outlet_id', $shift->outlet_id)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        $outletMix = Order::withoutGlobalScope('tenant')
            ->where('tenant_id', $tid)
            ->selectRaw('outlet_id, COUNT(*) as c')
            ->groupBy('outlet_id')
            ->get()
            ->mapWithKeys(fn ($row) => [(int) $row->outlet_id => (int) $row->c])
            ->all();

        $taggedThisShift = Order::withoutGlobalScope('tenant')
            ->where('tenant_id', $tid)
            ->where('meta->shift_id', $shift->id)
            ->count();

        $tenantLast24hAnyOutlet = Order::withoutGlobalScope('tenant')
            ->where('tenant_id', $tid)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        $countByOutletFkNotOrderTenantCol = Order::withoutGlobalScope('tenant')
            ->whereHas('outlet', function ($q) use ($tid) {
                $q->withoutGlobalScope('tenant')->where('tenant_id', $tid);
            })
            ->where('outlet_id', $shift->outlet_id)
            ->count();

        return [
            'shift_id' => $shift->id,
            'shift_outlet_id' => $shift->outlet_id,
            'shift_opening_float' => (float) $shift->opening_float,
            'shift_created_at' => $shift->created_at?->toIso8601String(),
            'summary_window_end' => $windowEnd->toIso8601String(),
            'orders_for_shift_outlet_total' => $countOutletEver,
            'orders_in_shift_time_window_legacy_outlet_only' => Order::withoutGlobalScope('tenant')
                ->where('tenant_id', $tid)
                ->where('outlet_id', $shift->outlet_id)
                ->whereBetween('created_at', [$shift->created_at, $windowEnd])
                ->where(function ($q) {
                    $q->whereNull('status')->orWhere('status', '!=', 'CANCELLED');
                })
                ->count(),
            'orders_in_shift_time_window' => $countInWindow,
            'orders_shift_outlet_last_24h' => $countOutletLast24h,
            'orders_with_meta_shift_id' => $taggedThisShift,
            'orders_by_outlet_id_tenant' => $outletMix,
            'orders_tenant_last_24h_any_outlet' => $tenantLast24hAnyOutlet,
            'orders_on_shift_outlet_where_outlet_tenant_matches' => $countByOutletFkNotOrderTenantCol,
        ];
    }

    /**
     * Orders that belong to this shift window: same outlet, or tagged meta.shift_id, or dine-in table at this outlet.
     * Strict outlet-only matching misses rows when order.outlet_id ≠ shift.outlet_id but the table (or meta) says otherwise.
     */
    protected function baseShiftOrdersQuery(Shift $shift): Builder
    {
        $tid = $shift->tenant_id;
        $windowEnd = $shift->closed_at ?? now();
        $sid = (int) $shift->id;

        $orderTable = (new Order)->getTable();

        // Join outlets (no whereHas) — TenantScoped / subquery issues were excluding real rows.
        return Order::withoutGlobalScope('tenant')
            ->select($orderTable . '.*')
            ->join('outlets', $orderTable . '.outlet_id', '=', 'outlets.id')
            ->where('outlets.tenant_id', $tid)
            ->where($orderTable . '.created_at', '>=', $shift->created_at)
            ->where($orderTable . '.created_at', '<=', $windowEnd)
            ->where(function ($q) use ($orderTable) {
                $q->whereNull($orderTable . '.status')
                    ->orWhereRaw('UPPER(TRIM(' . $orderTable . '.status)) <> ?', ['CANCELLED']);
            })
            ->where(function ($q) use ($shift, $tid, $sid, $orderTable) {
                $driver = $q->getModel()->getConnection()->getDriverName();
                $q->where($orderTable . '.outlet_id', $shift->outlet_id);
                if ($driver === 'sqlite') {
                    $q->orWhereRaw(
                        '(' . $orderTable . '.meta IS NOT NULL AND CAST(json_extract(' . $orderTable . '.meta, \'$.shift_id\') AS INTEGER) = ?)',
                        [$sid]
                    );
                } else {
                    $q->orWhereRaw(
                        '(' . $orderTable . '.meta IS NOT NULL AND CAST(JSON_UNQUOTE(JSON_EXTRACT(' . $orderTable . '.meta, \'$.shift_id\')) AS UNSIGNED) = ?)',
                        [$sid]
                    );
                }
                $q->orWhereHas('table', function ($tq) use ($shift, $tid) {
                    $tq->withoutGlobalScope('tenant')
                        ->where('tenant_id', $tid)
                        ->where('outlet_id', $shift->outlet_id);
                })
                    ->orWhereHas('device', function ($dq) use ($shift, $tid) {
                        $dq->withoutGlobalScope('tenant')
                            ->where('tenant_id', $tid)
                            ->where('outlet_id', $shift->outlet_id);
                    });
            });
    }

    public function openShift(int $outletId, float $openingFloat = 0, ?int $openedBy = null): Shift
    {
        $userId = $openedBy ?? Auth::id();
        if ($userId === null) {
            throw new \RuntimeException('Cannot open shift without opened_by user.');
        }

        return Shift::create([
            'tenant_id' => app('tenant.id'),
            'outlet_id' => $outletId,
            'opened_by' => $userId,
            'opening_float' => $openingFloat,
        ]);
    }

    public function closeShift(Shift $shift, float $actualCash): Shift
    {
        $expectedCash = $this->calculateExpectedCash($shift);
        $variance = $actualCash - $expectedCash;
        
        $shift->update([
            'closed_at' => now(),
            'expected_cash' => $expectedCash,
            'actual_cash' => $actualCash,
            'variance' => $variance
        ]);
        
        return $shift;
    }

    protected function calculateExpectedCash(Shift $shift): float
    {
        // Calculate expected cash from cash payments during the shift
        $tid = $shift->tenant_id;
        $cashOrders = $this->baseShiftOrdersQuery($shift)
            ->whereRaw('LOWER(IFNULL(payment_method, ?)) = ?', ['', 'cash'])
            ->with([
                'items' => fn ($q) => $q->withoutGlobalScope('tenant'),
                'items.modifiers' => fn ($q) => $q->withoutGlobalScope('tenant'),
            ])
            ->get();
            
        $cashSales = $cashOrders->sum(function ($order) {
            return $order->items->sum(function ($item) {
                $subtotal = $item->qty * $item->price;
                $modifierTotal = $item->modifiers->sum('price');
                $tax = ($subtotal + $modifierTotal) * ($item->tax_rate / 100);
                return $subtotal + $modifierTotal + $tax - $item->discount;
            });
        });
            
        return $shift->opening_float + $cashSales;
    }

    public function getCurrentShift(int $outletId): ?Shift
    {
        return Shift::where('tenant_id', app('tenant.id'))
            ->where('outlet_id', $outletId)
            ->whereNull('closed_at')
            ->first();
    }

    public function getShiftSummary(Shift $shift): array
    {
        $orders = $this->baseShiftOrdersQuery($shift)
            ->with([
                'items' => fn ($q) => $q->withoutGlobalScope('tenant'),
                'items.modifiers' => fn ($q) => $q->withoutGlobalScope('tenant'),
            ])
            ->get();

        $orderGrandTotal = function ($order) {
            return $order->items->sum(function ($item) {
                $subtotal = $item->qty * $item->price;
                $modifierTotal = $item->modifiers->sum('price');
                $tax = ($subtotal + $modifierTotal) * ($item->tax_rate / 100);
                return $subtotal + $modifierTotal + $tax - $item->discount;
            });
        };

        $totalSales = $orders->sum($orderGrandTotal);

        $paymentMatches = function ($order, string $method): bool {
            return strcasecmp((string) ($order->payment_method ?? ''), $method) === 0;
        };

        $cashSales = $orders->filter(fn ($o) => $paymentMatches($o, 'cash'))->sum($orderGrandTotal);
        $cardSales = $orders->filter(fn ($o) => $paymentMatches($o, 'card'))->sum($orderGrandTotal);
        $upiSales = $orders->filter(fn ($o) => $paymentMatches($o, 'upi'))->sum($orderGrandTotal);
        
        return [
            'total_sales' => $totalSales,
            'total_orders' => $orders->count(),
            'cash_sales' => $cashSales,
            'card_sales' => $cardSales,
            'upi_sales' => $upiSales,
            'opening_float' => $shift->opening_float,
            'expected_cash' => $shift->expected_cash,
            'actual_cash' => $shift->actual_cash,
            'variance' => $shift->variance,
        ];
    }
}
