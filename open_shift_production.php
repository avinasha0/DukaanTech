<?php

/**
 * Production Shift Opening Script
 * 
 * This script helps open a shift in production when no shift is available.
 * Run this script to create an open shift for the specified tenant and outlet.
 */

require_once 'vendor/autoload.php';

use App\Models\Account;
use App\Models\Shift;
use App\Models\Outlet;
use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Production Shift Opening Script ===\n\n";

// Get tenant slug from command line or prompt
$tenantSlug = $argv[1] ?? null;
if (!$tenantSlug) {
    echo "Usage: php open_shift_production.php <tenant_slug> [outlet_id] [opening_float]\n";
    echo "Example: php open_shift_production.php your-tenant-slug 1 100.00\n\n";
    
    // List available tenants
    $tenants = Account::select('slug', 'name')->get();
    if ($tenants->count() > 0) {
        echo "Available tenants:\n";
        foreach ($tenants as $tenant) {
            echo "- {$tenant->slug} ({$tenant->name})\n";
        }
    }
    exit(1);
}

$outletId = $argv[2] ?? 1;
$openingFloat = $argv[3] ?? 0.00;

echo "Tenant Slug: {$tenantSlug}\n";
echo "Outlet ID: {$outletId}\n";
echo "Opening Float: ₹{$openingFloat}\n\n";

// Find tenant
$tenant = Account::where('slug', $tenantSlug)->first();
if (!$tenant) {
    echo "❌ Error: Tenant '{$tenantSlug}' not found.\n";
    exit(1);
}

echo "✅ Found tenant: {$tenant->name}\n";

// Check if outlet exists
$outlet = Outlet::where('tenant_id', $tenant->id)
    ->where('id', $outletId)
    ->first();

if (!$outlet) {
    echo "❌ Error: Outlet ID {$outletId} not found for tenant '{$tenantSlug}'.\n";
    
    // List available outlets
    $outlets = Outlet::where('tenant_id', $tenant->id)->get();
    if ($outlets->count() > 0) {
        echo "\nAvailable outlets for this tenant:\n";
        foreach ($outlets as $out) {
            echo "- ID: {$out->id}, Name: {$out->name}\n";
        }
    }
    exit(1);
}

echo "✅ Found outlet: {$outlet->name}\n";

// Check if there's already an open shift
$existingShift = Shift::where('tenant_id', $tenant->id)
    ->where('outlet_id', $outletId)
    ->whereNull('closed_at')
    ->first();

if ($existingShift) {
    echo "⚠️  Warning: There is already an open shift (ID: {$existingShift->id}) for this outlet.\n";
    echo "Created at: {$existingShift->created_at}\n";
    echo "Opened by user ID: {$existingShift->opened_by}\n";
    echo "Opening float: ₹{$existingShift->opening_float}\n\n";
    
    echo "Do you want to:\n";
    echo "1. Close the existing shift and open a new one\n";
    echo "2. Keep the existing shift\n";
    echo "3. Exit\n";
    
    $choice = readline("Enter your choice (1-3): ");
    
    if ($choice === '1') {
        // Close existing shift
        $existingShift->update([
            'closed_at' => now(),
            'expected_cash' => $existingShift->opening_float, // Basic calculation
            'actual_cash' => $existingShift->opening_float,
            'variance' => 0
        ]);
        echo "✅ Closed existing shift.\n";
    } elseif ($choice === '2') {
        echo "✅ Keeping existing shift. No changes made.\n";
        exit(0);
    } else {
        echo "Exiting...\n";
        exit(0);
    }
}

// Find a user to assign as opened_by
$user = User::where('tenant_id', $tenant->id)->first();
if (!$user) {
    echo "❌ Error: No users found for this tenant. Cannot open shift without a user.\n";
    exit(1);
}

echo "✅ Using user: {$user->name} (ID: {$user->id})\n";

// Create new shift
try {
    $shift = Shift::create([
        'tenant_id' => $tenant->id,
        'outlet_id' => $outletId,
        'opened_by' => $user->id,
        'opening_float' => $openingFloat,
    ]);
    
    echo "✅ Successfully opened shift!\n";
    echo "Shift ID: {$shift->id}\n";
    echo "Opened at: {$shift->created_at}\n";
    echo "Opened by: {$user->name}\n";
    echo "Opening float: ₹{$shift->opening_float}\n\n";
    
    echo "🎉 The POS should now show shift information and allow order creation.\n";
    
} catch (\Exception $e) {
    echo "❌ Error creating shift: " . $e->getMessage() . "\n";
    exit(1);
}
