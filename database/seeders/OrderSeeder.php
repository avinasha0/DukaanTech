<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\Outlet;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get the teabench1 tenant
        $tenant = Account::where('slug', 'teabench1')->first();
        
        if (!$tenant) {
            $this->command->info('Tenant teabench1 not found. Creating...');
            $tenant = Account::create([
                'name' => 'Tea Bench 1',
                'slug' => 'teabench1',
                'settings' => [
                    'currency' => 'INR',
                    'timezone' => 'Asia/Kolkata',
                    'tax_inclusive' => true,
                ]
            ]);
        }

        // Get or create outlet
        $outlet = Outlet::where('tenant_id', $tenant->id)->first();
        if (!$outlet) {
            $outlet = Outlet::create([
                'tenant_id' => $tenant->id,
                'name' => 'Main Outlet',
                'code' => 'OUT001',
                'gstin' => '29ABCDE1234F1Z5',
                'address' => [
                    'street' => '123 Main Street',
                    'city' => 'Mumbai',
                    'state' => 'Maharashtra',
                    'pincode' => '400001',
                    'country' => 'India'
                ]
            ]);
        }

        // Get or create order types
        $orderTypes = OrderType::where('tenant_id', $tenant->id)->get();
        if ($orderTypes->isEmpty()) {
            $orderTypes = collect([
                OrderType::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'Dine In',
                    'slug' => 'dine-in',
                    'color' => '#10B981',
                    'sort_order' => 1,
                ]),
                OrderType::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'Delivery',
                    'slug' => 'delivery',
                    'color' => '#F59E0B',
                    'sort_order' => 2,
                ]),
                OrderType::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'Takeaway',
                    'slug' => 'takeaway',
                    'color' => '#3B82F6',
                    'sort_order' => 3,
                ])
            ]);
        }

        // Create sample orders
        $orders = [
            [
                'customer_name' => 'John Doe',
                'customer_phone' => '1234567890',
                'table_no' => 'T1',
                'mode' => 'DINE_IN',
                'state' => 'NEW',
                'order_type_id' => $orderTypes->first()->id,
            ],
            [
                'customer_name' => 'Jane Smith',
                'customer_phone' => '9876543210',
                'table_no' => 'T2',
                'mode' => 'DINE_IN',
                'state' => 'IN_KITCHEN',
                'order_type_id' => $orderTypes->first()->id,
            ],
            [
                'customer_name' => 'Mike Johnson',
                'customer_phone' => '5555555555',
                'delivery_address' => '123 Delivery St, Mumbai',
                'mode' => 'DELIVERY',
                'state' => 'READY',
                'order_type_id' => $orderTypes->skip(1)->first()->id,
            ],
            [
                'customer_name' => 'Sarah Wilson',
                'customer_phone' => '1111111111',
                'mode' => 'TAKEAWAY',
                'state' => 'SERVED',
                'order_type_id' => $orderTypes->last()->id,
            ],
            [
                'customer_name' => 'David Brown',
                'customer_phone' => '2222222222',
                'table_no' => 'T3',
                'mode' => 'DINE_IN',
                'state' => 'BILLED',
                'order_type_id' => $orderTypes->first()->id,
            ],
        ];

        foreach ($orders as $orderData) {
            Order::create([
                'tenant_id' => $tenant->id,
                'outlet_id' => $outlet->id,
                'created_at' => now()->subHours(rand(1, 24)),
                ...$orderData
            ]);
        }

        $this->command->info('Created ' . count($orders) . ' sample orders for teabench1');
    }
}
