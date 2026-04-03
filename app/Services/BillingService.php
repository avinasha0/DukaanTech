<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Order;
use App\Models\OrderItem;

class BillingService
{
    public function __construct(
        private TaxService $taxService,
        private DiscountService $discountService
    ) {}

    public function createBillFromOrder(Order $order): Bill
    {
        [$sub, $tax, $disc] = $this->totals($order);
        $invoice = $this->invoiceNo($order->outlet_id);
        [$netTotal, $roundOff] = $this->payableRounded($sub, $tax, $disc);

        return Bill::create([
            'tenant_id' => app('tenant.id'),
            'outlet_id' => $order->outlet_id,
            'device_id' => app()->bound('device.id') ? app('device.id') : $order->device_id,
            'order_id' => $order->id,
            'invoice_no' => $invoice,
            'sub_total' => $sub,
            'tax_total' => $tax,
            'discount_total' => $disc,
            'round_off' => $roundOff,
            'net_total' => $netTotal,
            'state' => 'OPEN'
        ]);
    }

    /**
     * Nearest-rupee payable: e.g. 12.98 → 13 with round_off +0.02.
     *
     * @return array{0: float, 1: float} [net_total, round_off]
     */
    protected function payableRounded(float $sub, float $tax, float $disc): array
    {
        $raw = $sub + $tax - $disc;
        $net = (float) round($raw);
        $roundOff = round($net - $raw, 2);

        return [$net, $roundOff];
    }

    protected function totals(Order $order): array
    {
        $subtotal = 0;
        $taxTotal = 0;
        $discountTotal = 0;

        foreach ($order->items as $item) {
            $itemSubtotal = $item->qty * $item->price;
            $modifierTotal = $item->modifiers->sum('price');
            $itemTotal = $itemSubtotal + $modifierTotal;
            
            $subtotal += $itemTotal;
            
            // Use enhanced tax calculation
            $itemTaxes = $this->taxService->calculateItemTaxes($item, $order->order_type);
            $taxTotal += $itemTaxes['total_tax'];
            
            // Use existing discount from order item
            $discountTotal += $item->discount;
        }

        return [round($subtotal, 2), round($taxTotal, 2), round($discountTotal, 2)];
    }

    protected function invoiceNo($outletId): string
    {
        $count = Bill::where('outlet_id', $outletId)->count() + 1;
        return now()->format('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function voidBill(Bill $bill): Bill
    {
        $bill->update(['state' => 'VOID']);
        return $bill;
    }

    /**
     * Calculate detailed bill breakdown with tax and discount details.
     */
    public function calculateDetailedBill(Order $order): array
    {
        $subtotal = 0;
        $itemDetails = [];
        $taxSummary = $this->taxService->getTaxSummary($order);
        $discountSummary = [];

        foreach ($order->items as $item) {
            $itemSubtotal = $item->qty * $item->price;
            $modifierTotal = $item->modifiers->sum('price');
            $itemTotal = $itemSubtotal + $modifierTotal;
            
            $subtotal += $itemTotal;
            
            $itemTaxes = $this->taxService->calculateItemTaxes($item, $order->order_type);
            
            $itemDetails[] = [
                'item_id' => $item->id,
                'item_name' => $item->item->name,
                'quantity' => $item->qty,
                'unit_price' => $item->price,
                'subtotal' => round($itemSubtotal, 2),
                'modifier_total' => round($modifierTotal, 2),
                'item_total' => round($itemTotal, 2),
                'taxes' => $itemTaxes['taxes'],
                'tax_total' => round($itemTaxes['total_tax'], 2),
                'discount' => $item->discount,
                'final_total' => round($itemTotal + $itemTaxes['total_tax'] - $item->discount, 2),
            ];
        }

        $totalTax = round($taxSummary['total_tax'], 2);
        $totalDiscount = round($order->items->sum('discount'), 2);
        $subR = round($subtotal, 2);
        $rawPayable = $subR + $totalTax - $totalDiscount;
        [$finalTotal, $roundOff] = $this->payableRounded($subR, $totalTax, $totalDiscount);
        $netBeforeRound = round($rawPayable, 2);

        return [
            'subtotal' => $subR,
            'tax_summary' => $taxSummary,
            'discount_summary' => $discountSummary,
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'net_total' => $netBeforeRound,
            'round_off' => $roundOff,
            'final_total' => $finalTotal,
            'item_details' => $itemDetails,
        ];
    }

    /**
     * Apply discount to order before billing.
     */
    public function applyDiscountToOrder(Order $order, string $discountCode): array
    {
        return $this->discountService->applyOrderDiscount($order, $discountCode);
    }

    /**
     * Remove discount from order.
     */
    public function removeOrderDiscounts(Order $order): void
    {
        $this->discountService->removeOrderDiscounts($order);
    }

    /**
     * Get available discounts for order.
     */
    public function getAvailableDiscounts(Order $order): array
    {
        return $this->discountService->findOrderApplicableDiscounts($order);
    }

    /**
     * Validate order for billing.
     */
    public function validateOrderForBilling(Order $order): array
    {
        $issues = [];

        if ($order->state !== 'SERVED') {
            $issues[] = 'Order must be served before billing';
        }

        if ($order->items->isEmpty()) {
            $issues[] = 'Order must have at least one item';
        }

        // Check for negative totals
        $billCalculation = $this->calculateDetailedBill($order);
        if ($billCalculation['final_total'] < 0) {
            $issues[] = 'Order total cannot be negative';
        }

        return $issues;
    }
}
