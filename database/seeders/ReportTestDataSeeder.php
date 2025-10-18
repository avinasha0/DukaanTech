<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bill;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Outlet;
use App\Models\OrderType;
use Carbon\Carbon;

class ReportTestDataSeeder extends Seeder
{
    public function run()
    {
        $tenantId = app('tenant.id');
        if (!$tenantId) {
            $message = 'No tenant context found. Please run this seeder within tenant context.';
            if ($this->command) {
                $this->command->error($message);
            }
            throw new \Exception($message);
        }

        $outlet = Outlet::where('tenant_id', $tenantId)->first();
        if (!$outlet) {
            $message = 'No outlet found for tenant.';
            if ($this->command) {
                $this->command->error($message);
            }
            throw new \Exception($message);
        }

        // Get or create order types
        $dineInType = OrderType::firstOrCreate([
            'tenant_id' => $tenantId,
            'name' => 'Dine In'
        ], [
            'slug' => 'dine-in',
            'color' => '#3B82F6',
            'is_active' => true,
            'sort_order' => 1
        ]);

        $takeawayType = OrderType::firstOrCreate([
            'tenant_id' => $tenantId,
            'name' => 'Takeaway'
        ], [
            'slug' => 'takeaway',
            'color' => '#10B981',
            'is_active' => true,
            'sort_order' => 2
        ]);

        $deliveryType = OrderType::firstOrCreate([
            'tenant_id' => $tenantId,
            'name' => 'Delivery'
        ], [
            'slug' => 'delivery',
            'color' => '#F59E0B',
            'is_active' => true,
            'sort_order' => 3
        ]);

        // Get or create items
        $items = [
            ['name' => 'Chicken Biryani', 'price' => 150.00],
            ['name' => 'Mutton Curry', 'price' => 150.00],
            ['name' => 'Fish Fry', 'price' => 150.00],
            ['name' => 'Vegetable Biryani', 'price' => 120.00],
            ['name' => 'Chicken Tikka', 'price' => 150.00],
            ['name' => 'Dal Makhani', 'price' => 100.00],
            ['name' => 'Naan', 'price' => 30.00],
            ['name' => 'Rice', 'price' => 50.00],
        ];

        $createdItems = [];
        foreach ($items as $itemData) {
            $item = Item::firstOrCreate([
                'tenant_id' => $tenantId,
                'name' => $itemData['name']
            ], [
                'price' => $itemData['price'],
                'category_id' => 1, // Assuming category exists
                'is_active' => true
            ]);
            $createdItems[] = $item;
        }

        // Generate test data for the last 7 days
        for ($i = 7; $i >= 1; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Generate 5-15 orders per day
            $ordersPerDay = rand(5, 15);
            
            for ($j = 0; $j < $ordersPerDay; $j++) {
                $this->createTestOrder($tenantId, $outlet, $createdItems, [$dineInType, $takeawayType, $deliveryType], $date);
            }
        }

        if ($this->command) {
            $this->command->info('Test data generated successfully for reports!');
        }
        // Don't echo anything when called via web endpoint to avoid corrupting JSON response
    }

    private function createTestOrder($tenantId, $outlet, $items, $orderTypes, $date)
    {
        // Random order time within the day
        $orderTime = $date->copy()->addHours(rand(9, 21))->addMinutes(rand(0, 59));
        
        // Create order
        $order = Order::create([
            'tenant_id' => $tenantId,
            'outlet_id' => $outlet->id,
            'order_type_id' => $orderTypes[array_rand($orderTypes)]->id,
            'customer_name' => $this->getRandomCustomerName(),
            'customer_phone' => $this->getRandomPhone(),
            'table_no' => rand(1, 10),
            'state' => 'CLOSED',
            'status' => 'PAID',
            'created_at' => $orderTime,
            'updated_at' => $orderTime
        ]);

        // Add 1-5 items to the order
        $itemCount = rand(1, 5);
        $selectedItems = array_rand($items, min($itemCount, count($items)));
        if (!is_array($selectedItems)) {
            $selectedItems = [$selectedItems];
        }

        $subtotal = 0;
        foreach ($selectedItems as $itemIndex) {
            $item = $items[$itemIndex];
            $qty = rand(1, 3);
            $price = $item['price'];
            
            OrderItem::create([
                'tenant_id' => $tenantId,
                'order_id' => $order->id,
                'item_id' => $item['id'] ?? 1,
                'qty' => $qty,
                'price' => $price,
                'total' => $qty * $price
            ]);
            
            $subtotal += $qty * $price;
        }

        // Calculate totals
        $taxRate = 0.10; // 10% tax
        $taxTotal = $subtotal * $taxRate;
        $discountTotal = rand(0, 50); // Random discount
        $netTotal = $subtotal + $taxTotal - $discountTotal;

        // Create bill
        $bill = Bill::create([
            'tenant_id' => $tenantId,
            'outlet_id' => $outlet->id,
            'order_id' => $order->id,
            'invoice_no' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'sub_total' => $subtotal,
            'tax_total' => $taxTotal,
            'discount_total' => $discountTotal,
            'net_total' => $netTotal,
            'state' => 'PAID',
            'created_at' => $orderTime,
            'updated_at' => $orderTime
        ]);

        // Create payment
        $paymentMethods = ['CASH', 'CARD', 'UPI', 'WALLET'];
        $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
        
        Payment::create([
            'tenant_id' => $tenantId,
            'bill_id' => $bill->id,
            'method' => $paymentMethod,
            'amount' => $netTotal,
            'created_at' => $orderTime,
            'updated_at' => $orderTime
        ]);
    }

    private function getRandomCustomerName()
    {
        $names = ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Wilson', 'David Brown', 'Lisa Davis', 'Tom Miller', 'Amy Garcia'];
        return $names[array_rand($names)];
    }

    private function getRandomPhone()
    {
        return '9' . rand(100000000, 999999999);
    }
}
