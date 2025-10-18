<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\Order;

class DiscountService
{
    /**
     * Find applicable discounts for an order item.
     */
    public function findApplicableDiscounts(OrderItem $orderItem, $orderType = null): array
    {
        $tenantId = $orderItem->tenant_id;
        $itemId = $orderItem->item_id;
        $amount = $orderItem->qty * $orderItem->price;
        
        // Add modifier amounts
        $modifierAmount = $orderItem->modifiers->sum('price');
        $totalAmount = $amount + $modifierAmount;

        return Discount::where('tenant_id', $tenantId)
            ->available()
            ->get()
            ->filter(function ($discount) use ($itemId, $orderType, $totalAmount) {
                return $discount->isApplicableToItem($itemId, $orderType) && 
                       (!$discount->minimum_amount || $totalAmount >= $discount->minimum_amount);
            })
            ->values()
            ->toArray();
    }

    /**
     * Find applicable discounts for an entire order.
     */
    public function findOrderApplicableDiscounts(Order $order): array
    {
        $tenantId = $order->tenant_id;
        $orderType = $order->order_type;
        $orderTotal = $order->items->sum(function ($item) {
            return ($item->qty * $item->price) + $item->modifiers->sum('price');
        });

        return Discount::where('tenant_id', $tenantId)
            ->available()
            ->get()
            ->filter(function ($discount) use ($orderType, $orderTotal) {
                // Check if discount applies to order type
                if ($discount->applicable_order_types && !in_array($orderType, $discount->applicable_order_types)) {
                    return false;
                }

                // Check minimum amount requirement
                if ($discount->minimum_amount && $orderTotal < $discount->minimum_amount) {
                    return false;
                }

                return true;
            })
            ->values()
            ->toArray();
    }

    /**
     * Apply a discount to an order item.
     */
    public function applyDiscountToItem(OrderItem $orderItem, string $discountCode, $orderType = null): array
    {
        $tenantId = $orderItem->tenant_id;
        
        $discount = Discount::where('tenant_id', $tenantId)
            ->where('code', $discountCode)
            ->first();

        if (!$discount) {
            return ['success' => false, 'message' => 'Invalid discount code'];
        }

        if (!$discount->isApplicableToItem($orderItem->item_id, $orderType)) {
            return ['success' => false, 'message' => 'Discount not applicable to this item'];
        }

        $amount = ($orderItem->qty * $orderItem->price) + $orderItem->modifiers->sum('price');
        $discountAmount = $discount->calculateDiscount($amount, $orderItem->item_id, $orderType);

        if ($discountAmount <= 0) {
            return ['success' => false, 'message' => 'No discount applicable'];
        }

        // Update order item with discount
        $orderItem->update(['discount' => $discountAmount]);

        // Increment usage count
        $discount->incrementUsage();

        return [
            'success' => true,
            'discount_amount' => $discountAmount,
            'discount' => $discount,
        ];
    }

    /**
     * Apply a discount to an entire order.
     */
    public function applyOrderDiscount(Order $order, string $discountCode): array
    {
        $tenantId = $order->tenant_id;
        
        $discount = Discount::where('tenant_id', $tenantId)
            ->where('code', $discountCode)
            ->first();

        if (!$discount) {
            return ['success' => false, 'message' => 'Invalid discount code'];
        }

        $orderTotal = $order->items->sum(function ($item) {
            return ($item->qty * $item->price) + $item->modifiers->sum('price');
        });

        if (!$discount->isApplicableToItem(null, $order->order_type)) {
            return ['success' => false, 'message' => 'Discount not applicable to this order'];
        }

        $discountAmount = $discount->calculateDiscount($orderTotal, null, $order->order_type);

        if ($discountAmount <= 0) {
            return ['success' => false, 'message' => 'No discount applicable'];
        }

        // Apply discount proportionally to items
        $this->applyProportionalDiscount($order, $discountAmount);

        // Increment usage count
        $discount->incrementUsage();

        return [
            'success' => true,
            'discount_amount' => $discountAmount,
            'discount' => $discount,
        ];
    }

    /**
     * Apply discount proportionally across order items.
     */
    protected function applyProportionalDiscount(Order $order, float $totalDiscount): void
    {
        $orderTotal = $order->items->sum(function ($item) {
            return ($item->qty * $item->price) + $item->modifiers->sum('price');
        });

        if ($orderTotal <= 0) {
            return;
        }

        $remainingDiscount = $totalDiscount;

        foreach ($order->items as $index => $item) {
            $itemTotal = ($item->qty * $item->price) + $item->modifiers->sum('price');
            $itemProportion = $itemTotal / $orderTotal;
            
            // For the last item, apply remaining discount to avoid rounding errors
            if ($index === $order->items->count() - 1) {
                $itemDiscount = $remainingDiscount;
            } else {
                $itemDiscount = round($totalDiscount * $itemProportion, 2);
                $remainingDiscount -= $itemDiscount;
            }

            $item->update(['discount' => $itemDiscount]);
        }
    }

    /**
     * Remove discount from an order item.
     */
    public function removeItemDiscount(OrderItem $orderItem): void
    {
        $orderItem->update(['discount' => 0]);
    }

    /**
     * Remove all discounts from an order.
     */
    public function removeOrderDiscounts(Order $order): void
    {
        foreach ($order->items as $item) {
            $item->update(['discount' => 0]);
        }
    }

    /**
     * Calculate potential discount amount without applying it.
     */
    public function calculateDiscountAmount(string $discountCode, float $amount, $itemId = null, $orderType = null): array
    {
        $tenantId = app('tenant.id');
        
        $discount = Discount::where('tenant_id', $tenantId)
            ->where('code', $discountCode)
            ->first();

        if (!$discount) {
            return ['success' => false, 'message' => 'Invalid discount code'];
        }

        if (!$discount->isApplicableToItem($itemId, $orderType)) {
            return ['success' => false, 'message' => 'Discount not applicable'];
        }

        $discountAmount = $discount->calculateDiscount($amount, $itemId, $orderType);

        return [
            'success' => true,
            'discount_amount' => $discountAmount,
            'discount' => $discount,
        ];
    }

    /**
     * Get discount usage statistics.
     */
    public function getDiscountStats($tenantId, $period = 'month'): array
    {
        $query = Discount::where('tenant_id', $tenantId);

        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subMonth());
                break;
            case 'year':
                $query->where('created_at', '>=', now()->subYear());
                break;
        }

        $discounts = $query->get();

        return [
            'total_discounts' => $discounts->count(),
            'active_discounts' => $discounts->where('is_active', true)->count(),
            'total_usage' => $discounts->sum('usage_count'),
            'most_used' => $discounts->sortByDesc('usage_count')->first(),
            'expiring_soon' => $discounts->where('valid_until', '>=', now())
                ->where('valid_until', '<=', now()->addDays(7))
                ->count(),
        ];
    }

    /**
     * Validate discount configuration.
     */
    public function validateDiscountConfiguration($tenantId): array
    {
        $issues = [];
        
        // Check for duplicate discount codes
        $duplicateCodes = Discount::where('tenant_id', $tenantId)
            ->selectRaw('code, COUNT(*) as count')
            ->groupBy('code')
            ->having('count', '>', 1)
            ->get();

        if ($duplicateCodes->count() > 0) {
            $issues[] = 'Duplicate discount codes found: ' . $duplicateCodes->pluck('code')->implode(', ');
        }

        // Check for invalid percentage discounts
        $invalidPercentages = Discount::where('tenant_id', $tenantId)
            ->where('type', 'percentage')
            ->where('value', '>', 100)
            ->get();

        if ($invalidPercentages->count() > 0) {
            $issues[] = 'Percentage discounts exceeding 100% found: ' . $invalidPercentages->pluck('name')->implode(', ');
        }

        // Check for expired discounts
        $expiredDiscounts = Discount::where('tenant_id', $tenantId)
            ->where('valid_until', '<', now())
            ->where('is_active', true)
            ->get();

        if ($expiredDiscounts->count() > 0) {
            $issues[] = 'Expired discounts still active: ' . $expiredDiscounts->pluck('name')->implode(', ');
        }

        return $issues;
    }
}
