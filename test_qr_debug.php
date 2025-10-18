<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing QR Code Debug:\n";

$tenant = \App\Models\Account::where('slug', 'dukaantech')->first();
if ($tenant) {
    echo "Tenant found: " . $tenant->id . " - " . $tenant->slug . "\n";
    $qrCodes = \App\Models\QRCode::where('tenant_id', $tenant->id)->get();
    echo "QR Codes count: " . $qrCodes->count() . "\n";
    
    if ($qrCodes->count() > 0) {
        echo "QR Codes:\n";
        foreach ($qrCodes as $qr) {
            echo "- ID: {$qr->id}, Name: {$qr->name}, Type: {$qr->type}, Active: " . ($qr->is_active ? 'Yes' : 'No') . "\n";
        }
    }
} else {
    echo "Tenant not found\n";
}
