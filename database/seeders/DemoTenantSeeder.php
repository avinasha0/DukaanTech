<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Device;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\ItemVariant;
use App\Models\KotRoute;
use App\Models\Modifier;
use App\Models\ModifierGroup;
use App\Models\Outlet;
use App\Models\Printer;
use App\Models\TaxRate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoTenantSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo tenant
        $tenant = Account::create([
            'name' => 'Demo Restaurant',
            'slug' => 'demo',
            'settings' => [
                'currency' => 'INR',
                'timezone' => 'Asia/Kolkata',
                'tax_inclusive' => true,
            ]
        ]);

        // Create outlet
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

        // Create user
        $user = User::create([
            'name' => 'Demo Manager',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
        ]);

        // Create devices
        Device::create([
            'tenant_id' => $tenant->id,
            'outlet_id' => $outlet->id,
            'name' => 'POS Terminal 1',
            'type' => 'POS',
            'api_key' => 'pos_' . uniqid(),
        ]);

        Device::create([
            'tenant_id' => $tenant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Kitchen Display',
            'type' => 'KITCHEN',
            'api_key' => 'kitchen_' . uniqid(),
        ]);

        // Create printers
        $printer = Printer::create([
            'tenant_id' => $tenant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Receipt Printer',
            'iface' => 'USB',
            'address' => '/dev/usb/lp0',
            'profile' => [
                'width' => 48,
                'charset' => 'utf-8'
            ]
        ]);

        // Create tax rates
        $tax5 = TaxRate::create([
            'tenant_id' => $tenant->id,
            'name' => 'GST 5%',
            'code' => 'GST5',
            'rate' => 5.00,
            'inclusive' => true,
        ]);

        $tax18 = TaxRate::create([
            'tenant_id' => $tenant->id,
            'name' => 'GST 18%',
            'code' => 'GST18',
            'rate' => 18.00,
            'inclusive' => true,
        ]);

        // Create categories
        $categories = [
            ['name' => 'Appetizers', 'position' => 1],
            ['name' => 'Main Course', 'position' => 2],
            ['name' => 'Beverages', 'position' => 3],
            ['name' => 'Desserts', 'position' => 4],
            ['name' => 'Salads', 'position' => 5],
            ['name' => 'Soups', 'position' => 6],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $createdCategories[] = Category::create([
                'tenant_id' => $tenant->id,
                'outlet_id' => $outlet->id,
                'name' => $categoryData['name'],
                'position' => $categoryData['position'],
            ]);
        }

        // Create modifier groups
        $sizeGroup = ModifierGroup::create([
            'tenant_id' => $tenant->id,
            'name' => 'Size',
            'is_required' => true,
            'min' => 1,
            'max' => 1,
        ]);

        $toppingsGroup = ModifierGroup::create([
            'tenant_id' => $tenant->id,
            'name' => 'Extra Toppings',
            'is_required' => false,
            'min' => 0,
            'max' => 5,
        ]);

        // Create modifiers
        Modifier::create([
            'tenant_id' => $tenant->id,
            'modifier_group_id' => $sizeGroup->id,
            'name' => 'Small',
            'price' => 0,
        ]);

        Modifier::create([
            'tenant_id' => $tenant->id,
            'modifier_group_id' => $sizeGroup->id,
            'name' => 'Medium',
            'price' => 20,
        ]);

        Modifier::create([
            'tenant_id' => $tenant->id,
            'modifier_group_id' => $sizeGroup->id,
            'name' => 'Large',
            'price' => 40,
        ]);

        Modifier::create([
            'tenant_id' => $tenant->id,
            'modifier_group_id' => $toppingsGroup->id,
            'name' => 'Extra Cheese',
            'price' => 30,
        ]);

        Modifier::create([
            'tenant_id' => $tenant->id,
            'modifier_group_id' => $toppingsGroup->id,
            'name' => 'Extra Spice',
            'price' => 10,
        ]);

        // Create items
        $items = [
            // Appetizers
            ['name' => 'Chicken Wings', 'sku' => 'APP001', 'category' => 'Appetizers', 'price' => 250, 'tax' => $tax18, 'veg' => false],
            ['name' => 'Paneer Tikka', 'sku' => 'APP002', 'category' => 'Appetizers', 'price' => 200, 'tax' => $tax18, 'veg' => true],
            ['name' => 'Samosa', 'sku' => 'APP003', 'category' => 'Appetizers', 'price' => 50, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Spring Rolls', 'sku' => 'APP004', 'category' => 'Appetizers', 'price' => 120, 'tax' => $tax18, 'veg' => true],
            
            // Main Course
            ['name' => 'Butter Chicken', 'sku' => 'MAIN001', 'category' => 'Main Course', 'price' => 350, 'tax' => $tax18, 'veg' => false],
            ['name' => 'Dal Makhani', 'sku' => 'MAIN002', 'category' => 'Main Course', 'price' => 280, 'tax' => $tax18, 'veg' => true],
            ['name' => 'Biryani', 'sku' => 'MAIN003', 'category' => 'Main Course', 'price' => 300, 'tax' => $tax18, 'veg' => false],
            ['name' => 'Paneer Butter Masala', 'sku' => 'MAIN004', 'category' => 'Main Course', 'price' => 320, 'tax' => $tax18, 'veg' => true],
            ['name' => 'Chicken Curry', 'sku' => 'MAIN005', 'category' => 'Main Course', 'price' => 280, 'tax' => $tax18, 'veg' => false],
            ['name' => 'Fish Curry', 'sku' => 'MAIN006', 'category' => 'Main Course', 'price' => 400, 'tax' => $tax18, 'veg' => false],
            
            // Beverages
            ['name' => 'Coca Cola', 'sku' => 'BEV001', 'category' => 'Beverages', 'price' => 50, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Fresh Lime Soda', 'sku' => 'BEV002', 'category' => 'Beverages', 'price' => 60, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Mango Lassi', 'sku' => 'BEV003', 'category' => 'Beverages', 'price' => 80, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Masala Chai', 'sku' => 'BEV004', 'category' => 'Beverages', 'price' => 30, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Coffee', 'sku' => 'BEV005', 'category' => 'Beverages', 'price' => 40, 'tax' => $tax5, 'veg' => true],
            
            // Desserts
            ['name' => 'Gulab Jamun', 'sku' => 'DES001', 'category' => 'Desserts', 'price' => 80, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Ice Cream', 'sku' => 'DES002', 'category' => 'Desserts', 'price' => 100, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Kheer', 'sku' => 'DES003', 'category' => 'Desserts', 'price' => 90, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Ras Malai', 'sku' => 'DES004', 'category' => 'Desserts', 'price' => 120, 'tax' => $tax5, 'veg' => true],
            
            // Salads
            ['name' => 'Green Salad', 'sku' => 'SAL001', 'category' => 'Salads', 'price' => 150, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Caesar Salad', 'sku' => 'SAL002', 'category' => 'Salads', 'price' => 200, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Chicken Salad', 'sku' => 'SAL003', 'category' => 'Salads', 'price' => 250, 'tax' => $tax5, 'veg' => false],
            
            // Soups
            ['name' => 'Tomato Soup', 'sku' => 'SOUP001', 'category' => 'Soups', 'price' => 120, 'tax' => $tax5, 'veg' => true],
            ['name' => 'Chicken Soup', 'sku' => 'SOUP002', 'category' => 'Soups', 'price' => 150, 'tax' => $tax5, 'veg' => false],
            ['name' => 'Corn Soup', 'sku' => 'SOUP003', 'category' => 'Soups', 'price' => 130, 'tax' => $tax5, 'veg' => true],
        ];

        foreach ($items as $itemData) {
            $category = collect($createdCategories)->firstWhere('name', $itemData['category']);
            
            $item = Item::create([
                'tenant_id' => $tenant->id,
                'category_id' => $category->id,
                'name' => $itemData['name'],
                'sku' => $itemData['sku'],
                'is_veg' => $itemData['veg'],
                'is_active' => true,
                'meta' => [
                    'description' => 'Delicious ' . strtolower($itemData['name']),
                    'preparation_time' => rand(5, 30) . ' minutes'
                ]
            ]);

            // Create item price
            ItemPrice::create([
                'tenant_id' => $tenant->id,
                'outlet_id' => $outlet->id,
                'item_id' => $item->id,
                'tax_rate_id' => $itemData['tax']->id,
                'price' => $itemData['price'],
                'is_available' => true,
            ]);

            // Create variants for some items
            if (in_array($itemData['category'], ['Main Course', 'Beverages'])) {
                ItemVariant::create([
                    'tenant_id' => $tenant->id,
                    'item_id' => $item->id,
                    'name' => 'Regular',
                ]);

                ItemVariant::create([
                    'tenant_id' => $tenant->id,
                    'item_id' => $item->id,
                    'name' => 'Large',
                ]);
            }
        }

        // Create KOT routes
        foreach ($createdCategories as $category) {
            KotRoute::create([
                'tenant_id' => $tenant->id,
                'outlet_id' => $outlet->id,
                'category_id' => $category->id,
                'station' => 'Kitchen Station ' . $category->position,
                'printer_id' => $printer->id,
            ]);
        }

        $this->command->info('Demo tenant created successfully!');
        $this->command->info('Tenant slug: demo');
        $this->command->info('User email: demo@example.com');
        $this->command->info('User password: password');
    }
}
