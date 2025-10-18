<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        // Get the teabench1 tenant
        $tenant = Account::where('slug', 'teabench1')->first();
        
        if (!$tenant) {
            $this->command->info('Tenant teabench1 not found. Skipping order items creation.');
            return;
        }

        // Get orders for this tenant
        $orders = Order::where('tenant_id', $tenant->id)->get();
        
        if ($orders->isEmpty()) {
            $this->command->info('No orders found for teabench1. Skipping order items creation.');
            return;
        }

        // Get or create categories and items
        $category = Category::where('tenant_id', $tenant->id)->first();
        if (!$category) {
            $category = Category::create([
                'tenant_id' => $tenant->id,
                'name' => 'Beverages',
                'position' => 1,
            ]);
        }

        // Create sample items
        $items = [
            [
                'name' => 'Masala Chai',
                'price' => 25.00,
            ],
            [
                'name' => 'Green Tea',
                'price' => 30.00,
            ],
            [
                'name' => 'Coffee',
                'price' => 35.00,
            ],
            [
                'name' => 'Samosa',
                'price' => 15.00,
            ],
            [
                'name' => 'Biscuits',
                'price' => 20.00,
            ],
        ];

        $createdItems = [];
        foreach ($items as $itemData) {
            $item = Item::create([
                'tenant_id' => $tenant->id,
                'category_id' => $category->id,
                'name' => $itemData['name'],
                'is_active' => true,
            ]);

            // Create item price
            ItemPrice::create([
                'tenant_id' => $tenant->id,
                'item_id' => $item->id,
                'outlet_id' => 1, // Assuming outlet ID 1
                'price' => $itemData['price'],
                'is_available' => true,
            ]);

            $createdItems[] = $item;
        }

        // Add items to orders
        foreach ($orders as $index => $order) {
            // Add 1-3 random items to each order
            $numItems = rand(1, 3);
            $selectedItems = array_slice($createdItems, 0, $numItems);
            
            foreach ($selectedItems as $item) {
                $qty = rand(1, 3);
                $price = $item->prices()->where('is_available', true)->first()->price ?? 0;
                
                OrderItem::create([
                    'tenant_id' => $tenant->id,
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'qty' => $qty,
                    'price' => $price,
                    'tax_rate' => 0,
                    'discount' => 0,
                ]);
            }
        }

        $this->command->info('Created order items for ' . $orders->count() . ' orders');
    }
}
