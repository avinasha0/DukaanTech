<?php

namespace App\Services;

use App\Models\TaxRate;
use App\Models\TaxGroup;
use App\Models\OrderItem;
use App\Models\Order;

class TaxService
{
    /**
     * Calculate taxes for an order item.
     */
    public function calculateItemTaxes(OrderItem $orderItem, $orderType = null, $region = null): array
    {
        $tenantId = $orderItem->tenant_id;
        $itemId = $orderItem->item_id;
        $amount = $orderItem->qty * $orderItem->price;
        
        // Add modifier amounts
        $modifierAmount = $orderItem->modifiers->sum('price');
        $totalAmount = $amount + $modifierAmount;

        $applicableTaxRates = TaxRate::where('tenant_id', $tenantId)
            ->applicable($itemId, $orderType, $region)
            ->with('taxGroup')
            ->get();

        $taxes = [];
        $totalTax = 0;

        foreach ($applicableTaxRates as $taxRate) {
            $taxAmount = $taxRate->calculateTax($totalAmount, $itemId, $orderType, $region);
            
            if ($taxAmount > 0) {
                $taxes[] = [
                    'tax_rate_id' => $taxRate->id,
                    'tax_group_id' => $taxRate->tax_group_id,
                    'name' => $taxRate->name,
                    'code' => $taxRate->code,
                    'rate' => $taxRate->rate,
                    'calculation_type' => $taxRate->calculation_type,
                    'amount' => $taxAmount,
                    'is_compound' => $taxRate->is_compound,
                    'is_inclusive' => $taxRate->inclusive,
                ];
                
                $totalTax += $taxAmount;
                
                // For compound taxes, add to base amount for next calculation
                if ($taxRate->is_compound) {
                    $totalAmount += $taxAmount;
                }
            }
        }

        return [
            'taxes' => $taxes,
            'total_tax' => round($totalTax, 2),
        ];
    }

    /**
     * Calculate taxes for an entire order.
     */
    public function calculateOrderTaxes(Order $order): array
    {
        $orderType = $order->order_type;
        $region = $order->outlet->region ?? null; // Assuming outlet has region field
        
        $allTaxes = [];
        $totalTax = 0;

        foreach ($order->items as $orderItem) {
            $itemTaxes = $this->calculateItemTaxes($orderItem, $orderType, $region);
            
            $allTaxes[] = [
                'order_item_id' => $orderItem->id,
                'item_name' => $orderItem->item->name,
                'taxes' => $itemTaxes['taxes'],
                'total_tax' => $itemTaxes['total_tax'],
            ];
            
            $totalTax += $itemTaxes['total_tax'];
        }

        return [
            'item_taxes' => $allTaxes,
            'total_tax' => round($totalTax, 2),
        ];
    }

    /**
     * Get tax summary grouped by tax groups.
     */
    public function getTaxSummary(Order $order): array
    {
        $orderTaxes = $this->calculateOrderTaxes($order);
        $taxGroups = [];

        foreach ($orderTaxes['item_taxes'] as $itemTax) {
            foreach ($itemTax['taxes'] as $tax) {
                $groupId = $tax['tax_group_id'] ?? 'ungrouped';
                
                if (!isset($taxGroups[$groupId])) {
                    $taxGroups[$groupId] = [
                        'group_name' => $tax['tax_group_id'] ? TaxGroup::find($tax['tax_group_id'])->name : 'Ungrouped',
                        'taxes' => [],
                        'total_amount' => 0,
                    ];
                }

                $taxCode = $tax['code'];
                if (!isset($taxGroups[$groupId]['taxes'][$taxCode])) {
                    $taxGroups[$groupId]['taxes'][$taxCode] = [
                        'name' => $tax['name'],
                        'code' => $tax['code'],
                        'rate' => $tax['rate'],
                        'amount' => 0,
                    ];
                }

                $taxGroups[$groupId]['taxes'][$taxCode]['amount'] = round($taxGroups[$groupId]['taxes'][$taxCode]['amount'] + $tax['amount'], 2);
                $taxGroups[$groupId]['total_amount'] = round($taxGroups[$groupId]['total_amount'] + $tax['amount'], 2);
            }
        }

        return [
            'tax_groups' => array_values($taxGroups),
            'total_tax' => round($orderTaxes['total_tax'], 2),
        ];
    }

    /**
     * Apply taxes to order items and update tax rates.
     */
    public function applyTaxesToOrder(Order $order): Order
    {
        $orderTaxes = $this->calculateOrderTaxes($order);
        
        foreach ($orderTaxes['item_taxes'] as $itemTax) {
            $orderItem = OrderItem::find($itemTax['order_item_id']);
            
            // Update the tax rate on the order item (using the primary tax rate)
            if (!empty($itemTax['taxes'])) {
                $primaryTax = $itemTax['taxes'][0]; // Use first tax as primary
                $orderItem->update([
                    'tax_rate' => $primaryTax['rate'],
                ]);
            }
        }

        return $order->fresh();
    }

    /**
     * Get applicable tax rates for a specific context.
     */
    public function getApplicableTaxRates($tenantId, $itemId = null, $orderType = null, $region = null): array
    {
        return TaxRate::where('tenant_id', $tenantId)
            ->applicable($itemId, $orderType, $region)
            ->with('taxGroup')
            ->get()
            ->toArray();
    }

    /**
     * Validate tax configuration.
     */
    public function validateTaxConfiguration($tenantId): array
    {
        $issues = [];
        
        // Check for duplicate tax codes
        $duplicateCodes = TaxRate::where('tenant_id', $tenantId)
            ->selectRaw('code, COUNT(*) as count')
            ->groupBy('code')
            ->having('count', '>', 1)
            ->get();

        if ($duplicateCodes->count() > 0) {
            $issues[] = 'Duplicate tax codes found: ' . $duplicateCodes->pluck('code')->implode(', ');
        }

        // Check for invalid tax rates
        $invalidRates = TaxRate::where('tenant_id', $tenantId)
            ->where('calculation_type', 'percentage')
            ->where('rate', '>', 100)
            ->get();

        if ($invalidRates->count() > 0) {
            $issues[] = 'Tax rates exceeding 100% found: ' . $invalidRates->pluck('name')->implode(', ');
        }

        // Check for expired tax rates
        $expiredRates = TaxRate::where('tenant_id', $tenantId)
            ->where('effective_until', '<', now())
            ->where('is_active', true)
            ->get();

        if ($expiredRates->count() > 0) {
            $issues[] = 'Expired tax rates still active: ' . $expiredRates->pluck('name')->implode(', ');
        }

        return $issues;
    }
}
