<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Order;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing customer data from orders table to customers table
        $ordersWithCustomers = Order::whereNotNull('customer_phone')
            ->where('customer_phone', '!=', '')
            ->select('tenant_id', 'customer_name', 'customer_phone', 'customer_address')
            ->distinct()
            ->get();

        foreach ($ordersWithCustomers as $orderData) {
            // Create customer if not exists
            $customer = Customer::firstOrCreate(
                [
                    'tenant_id' => $orderData->tenant_id,
                    'phone' => $orderData->customer_phone,
                ],
                [
                    'name' => $orderData->customer_name ?? 'Unknown Customer',
                    'address' => $orderData->customer_address,
                ]
            );

            // Update all orders with this customer phone to link to the customer
            Order::where('tenant_id', $orderData->tenant_id)
                ->where('customer_phone', $orderData->customer_phone)
                ->update(['customer_id' => $customer->id]);
        }

        // Also handle orders with customer_name but no phone
        $ordersWithNamesOnly = Order::whereNotNull('customer_name')
            ->where('customer_name', '!=', '')
            ->where(function($query) {
                $query->whereNull('customer_phone')
                      ->orWhere('customer_phone', '');
            })
            ->select('tenant_id', 'customer_name', 'customer_address')
            ->distinct()
            ->get();

        foreach ($ordersWithNamesOnly as $orderData) {
            // Create customer with generated phone number
            $customer = Customer::firstOrCreate(
                [
                    'tenant_id' => $orderData->tenant_id,
                    'phone' => 'NO_PHONE_' . $orderData->tenant_id . '_' . substr(md5($orderData->customer_name), 0, 8),
                ],
                [
                    'name' => $orderData->customer_name,
                    'address' => $orderData->customer_address,
                ]
            );

            // Update all orders with this customer name to link to the customer
            Order::where('tenant_id', $orderData->tenant_id)
                ->where('customer_name', $orderData->customer_name)
                ->where(function($query) {
                    $query->whereNull('customer_phone')
                          ->orWhere('customer_phone', '');
                })
                ->update(['customer_id' => $customer->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove customer_id from orders table
        Order::whereNotNull('customer_id')->update(['customer_id' => null]);
        
        // Note: We don't delete customers table as it might have new data
        // This is a safe rollback that preserves data integrity
    }
};
