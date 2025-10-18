<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Order;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function addPayment(Bill $bill, string $method, float $amount, ?string $ref = null): Payment
    {
        return $bill->payments()->create([
            'tenant_id' => app('tenant.id'),
            'device_id' => app()->bound('device.id') ? app('device.id') : $bill->device_id,
            'method' => $method,
            'amount' => $amount,
            'ref' => $ref
        ]);
    }

    public function settle(Bill $bill): Bill
    {
        $sum = $bill->payments()->sum('amount');
        
        if (bccomp($sum, $bill->net_total, 2) === 0) {
            $bill->update(['state' => 'PAID']);
            
            // Update order status to PAID and handle table status
            $order = $bill->order;
            if ($order && $order->mode === 'DINE_IN' && $order->table_id) {
                DB::transaction(function () use ($order) {
                    $order->update([
                        'status' => 'PAID',
                        'state' => 'CLOSED'
                    ]);
                    
                    $table = $order->table;
                    if ($table) {
                        // Use the table's syncStatus method for consistent logic
                        $table->syncStatus();
                    }
                });
            }
            
            // Fire event for bill settled
            // event(new \App\Events\BillSettled($bill));
        }
        
        return $bill;
    }

    public function getPaymentMethods(): array
    {
        return [
            'CASH' => 'Cash',
            'CARD' => 'Card',
            'UPI' => 'UPI',
            'WALLET' => 'Wallet',
            'OTHER' => 'Other'
        ];
    }

    public function calculateChange(float $paidAmount, float $billTotal): float
    {
        return max(0, $paidAmount - $billTotal);
    }
}
