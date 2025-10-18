<?php

namespace Database\Seeders;

use App\Models\OrderType;
use App\Models\Account;
use Illuminate\Database\Seeder;

class OrderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all tenants
        $tenants = Account::all();

        foreach ($tenants as $tenant) {
            $orderTypes = [
                [
                    'name' => 'Dine In',
                    'slug' => 'dine-in',
                    'color' => '#10B981', // Green
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Delivery',
                    'slug' => 'delivery',
                    'color' => '#F59E0B', // Amber
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Pick Up',
                    'slug' => 'pick-up',
                    'color' => '#3B82F6', // Blue
                    'sort_order' => 3,
                ],
            ];

            foreach ($orderTypes as $orderTypeData) {
                OrderType::create([
                    'tenant_id' => $tenant->id,
                    ...$orderTypeData
                ]);
            }
        }
    }
}