<?php

/**
 * Production Fix Script: Ensure Order Types Exist for All Tenants
 * 
 * This script fixes the production issue where order creation fails
 * due to missing order types for tenants.
 * 
 * Run this script once in production to create default order types
 * for all existing tenants.
 */

require_once 'vendor/autoload.php';

use App\Models\Account;
use App\Models\OrderType;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Starting Order Types Fix for Production...\n";

// Get all tenants
$tenants = Account::all();

if ($tenants->isEmpty()) {
    echo "No tenants found.\n";
    exit(0);
}

echo "Found " . $tenants->count() . " tenants.\n";

foreach ($tenants as $tenant) {
    echo "Processing tenant: {$tenant->name} (ID: {$tenant->id})\n";
    
    // Check if order types exist for this tenant
    $existingOrderTypes = OrderType::where('tenant_id', $tenant->id)->count();
    
    if ($existingOrderTypes > 0) {
        echo "  - Order types already exist ({$existingOrderTypes} found). Skipping.\n";
        continue;
    }
    
    // Create default order types
    $orderTypes = [
        [
            'name' => 'Dine In',
            'slug' => 'dine-in',
            'color' => '#10B981',
            'is_active' => true,
            'sort_order' => 1,
        ],
        [
            'name' => 'Delivery',
            'slug' => 'delivery',
            'color' => '#F59E0B',
            'is_active' => true,
            'sort_order' => 2,
        ],
        [
            'name' => 'Takeaway',
            'slug' => 'takeaway',
            'color' => '#3B82F6',
            'is_active' => true,
            'sort_order' => 3,
        ],
    ];
    
    foreach ($orderTypes as $orderTypeData) {
        OrderType::create([
            'tenant_id' => $tenant->id,
            ...$orderTypeData
        ]);
    }
    
    echo "  - Created 3 default order types.\n";
}

echo "Order Types Fix completed successfully!\n";
echo "All tenants now have default order types.\n";
