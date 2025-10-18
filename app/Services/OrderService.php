<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Modifier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function create(array $data): Order
    {
        $items = $data['items'] ?? [];
        unset($data['items']);
        
        // Default device_id from app container if not provided
        if (!isset($data['device_id']) && app()->bound('device.id')) {
            $data['device_id'] = app('device.id');
        }

        // For dine-in orders, enforce one open order per table constraint
        if (isset($data['mode']) && $data['mode'] === 'DINE_IN' && isset($data['table_id'])) {
            return DB::transaction(function () use ($data, $items) {
                $table = RestaurantTable::lockForUpdate()->findOrFail($data['table_id']);
                
                // Check if table already has an open order
                $hasOpenOrder = $table->orders()->whereIn('status', ['OPEN'])->exists();
                
                if ($hasOpenOrder) {
                    throw ValidationException::withMessages([
                        'table_id' => 'This table already has an open order. Please close the existing order first.'
                    ]);
                }

                $order = Order::create($data);
                
                // Add items to the order
                if (!empty($items)) {
                    foreach ($items as $itemData) {
                        $item = Item::find($itemData['item_id']);
                        if ($item) {
                            $this->addItem($order, $item, $itemData['qty']);
                        }
                    }
                }
                
                return $order->load('items.item');
            });
        }

        // For non-dine-in orders, create normally
        $order = Order::create($data);
        
        // Add items to the order
        if (!empty($items)) {
            foreach ($items as $itemData) {
                $item = Item::find($itemData['item_id']);
                if ($item) {
                    $this->addItem($order, $item, $itemData['qty']);
                }
            }
        }
        
        return $order->load('items.item');
    }

    public function addItem(
        Order $order,
        Item $item,
        int $qty,
        ?ItemVariant $variant = null,
        array $modifiers = [],
        ?string $note = null
    ): OrderItem {
        $tax = 0; // Default tax rate, can be updated later if needed
        
        $line = $order->items()->create([
            'tenant_id' => app('tenant')->id,
            'item_id' => $item->id,
            'item_variant_id' => $variant?->id,
            'qty' => $qty,
            'price' => $item->priceFor($variant, $order->outlet),
            'tax_rate' => $tax,
            'note' => $note
        ]);

        foreach ($modifiers as $modId) {
            $modifier = Modifier::find($modId);
            if ($modifier) {
                $line->modifiers()->create([
                    'tenant_id' => app('tenant')->id,
                    'modifier_id' => $modId,
                    'price' => $modifier->price
                ]);
            }
        }

        return $line->refresh()->load('modifiers');
    }

    public function updateOrderState(Order $order, string $state): Order
    {
        $order->update(['state' => $state]);
        return $order;
    }

    public function getOrderTotal(Order $order): float
    {
        return round($order->items->sum(function ($item) {
            $subtotal = $item->qty * $item->price;
            $modifierTotal = $item->modifiers->sum('price');
            $tax = ($subtotal + $modifierTotal) * ($item->tax_rate / 100);
            return $subtotal + $modifierTotal + $tax - $item->discount;
        }), 2);
    }
}
