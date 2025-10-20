<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\Cache;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Only update table status for dine-in orders with table_id
        if ($order->mode === 'DINE_IN' && $order->table_id) {
            $table = $order->table;
            if ($table) {
                $table->update([
                    'status' => 'occupied',
                    'current_order_id' => $order->id
                ]);
                
                // Update table total amount
                $table->updateTotalAmount();
                
                // Invalidate table cache
                $this->invalidateTableCache($order->table_id);
            }
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Only process table-related status changes for dine-in orders
        if ($order->mode === 'DINE_IN' && $order->table_id) {
            $this->syncTableStatus($order);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        // Only update table status for dine-in orders with table_id
        if ($order->mode === 'DINE_IN' && $order->table_id) {
            $this->syncTableStatus($order);
        }
    }

    /**
     * Sync table status based on active orders
     */
    private function syncTableStatus(Order $order): void
    {
        $table = $order->table;
        
        if (!$table) {
            return;
        }

        // Use the table's syncStatus method for consistent logic
        $table->syncStatus();
        
        // Update table total amount
        $table->updateTotalAmount();

        // Invalidate table cache
        $this->invalidateTableCache($table->id);
    }

    /**
     * Invalidate table-related cache
     */
    private function invalidateTableCache(int $tableId): void
    {
        // Invalidate specific table cache
        Cache::forget("table:{$tableId}");
        
        // Invalidate tables list cache
        Cache::forget('tables:list');
        
        // Invalidate tenant-specific table cache
        $table = RestaurantTable::find($tableId);
        if ($table) {
            Cache::forget("tables:tenant:{$table->tenant_id}");
            Cache::forget("tables:outlet:{$table->outlet_id}");
        }
    }
}
