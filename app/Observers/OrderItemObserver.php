<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\RestaurantTable;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem): void
    {
        $this->updateTableTotal($orderItem);
    }

    /**
     * Handle the OrderItem "updated" event.
     */
    public function updated(OrderItem $orderItem): void
    {
        $this->updateTableTotal($orderItem);
    }

    /**
     * Handle the OrderItem "deleted" event.
     */
    public function deleted(OrderItem $orderItem): void
    {
        $this->updateTableTotal($orderItem);
    }

    /**
     * Update table total amount when order items change
     */
    private function updateTableTotal(OrderItem $orderItem): void
    {
        $order = $orderItem->order;
        
        // Only update table totals for dine-in orders with table_id
        if ($order && $order->mode === 'DINE_IN' && $order->table_id) {
            $table = $order->table;
            if ($table) {
                $table->updateTotalAmount();
            }
        }
    }
}
