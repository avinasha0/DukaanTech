<?php

namespace App\Services;

use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class ShiftService
{
    public function openShift(int $outletId, float $openingFloat = 0): Shift
    {
        return Shift::create([
            'tenant_id' => app('tenant.id'),
            'outlet_id' => $outletId,
            'opened_by' => Auth::id(),
            'opening_float' => $openingFloat
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
        $cashOrders = \App\Models\Order::where('tenant_id', app('tenant.id'))
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
        $orders = \App\Models\Order::where('tenant_id', app('tenant.id'))
            ->where('outlet_id', $shift->outlet_id)
            ->whereBetween('created_at', [$shift->created_at, $shift->closed_at ?? now()])
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
