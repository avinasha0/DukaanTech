<?php
/**
 * Debug script to collect all stats for shift checkout issue
 * Run this on production server to diagnose the problem
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SHIFT CHECKOUT DEBUG REPORT ===\n";
echo "Generated: " . now() . "\n\n";

// 1. Check tenant info
echo "1. TENANT INFORMATION\n";
echo "====================\n";
$tenant = app('tenant');
if ($tenant) {
    echo "Tenant ID: " . $tenant->id . "\n";
    echo "Tenant Slug: " . $tenant->slug . "\n";
    echo "Tenant Name: " . $tenant->name . "\n";
} else {
    echo "❌ No tenant found\n";
}
echo "\n";

// 2. Check outlets
echo "2. OUTLET INFORMATION\n";
echo "====================\n";
$outlets = \App\Models\Outlet::where('tenant_id', $tenant->id ?? 1)->get();
echo "Total outlets: " . $outlets->count() . "\n";
foreach ($outlets as $outlet) {
    echo "- Outlet ID: {$outlet->id}, Name: {$outlet->name}\n";
}
echo "\n";

// 3. Check shifts
echo "3. SHIFT INFORMATION\n";
echo "===================\n";
$allShifts = \App\Models\Shift::where('tenant_id', $tenant->id ?? 1)->get();
echo "Total shifts: " . $allShifts->count() . "\n";

$openShifts = \App\Models\Shift::where('tenant_id', $tenant->id ?? 1)
    ->whereNull('closed_at')
    ->get();
echo "Open shifts: " . $openShifts->count() . "\n";

foreach ($openShifts as $shift) {
    echo "- Shift ID: {$shift->id}, Outlet: {$shift->outlet_id}, Opened by: {$shift->opened_by}, Created: {$shift->created_at}\n";
}
echo "\n";

// 4. Check terminal users
echo "4. TERMINAL USER INFORMATION\n";
echo "===========================\n";
$terminalUsers = \App\Models\TerminalUser::where('tenant_id', $tenant->id ?? 1)->get();
echo "Total terminal users: " . $terminalUsers->count() . "\n";

foreach ($terminalUsers as $tu) {
    echo "- Terminal User ID: {$tu->id}, Name: {$tu->name}, Terminal ID: {$tu->terminal_id}, User ID: " . ($tu->user_id ?? 'NULL') . "\n";
}
echo "\n";

// 5. Check terminal sessions
echo "5. TERMINAL SESSION INFORMATION\n";
echo "==============================\n";
$sessions = \App\Models\TerminalSession::where('expires_at', '>', now())->get();
echo "Active sessions: " . $sessions->count() . "\n";

foreach ($sessions as $session) {
    echo "- Session ID: {$session->id}, Token: " . substr($session->session_token, 0, 20) . "...\n";
    echo "  Expires: {$session->expires_at}, Terminal User ID: {$session->terminal_user_id}\n";
}
echo "\n";

// 6. Check users
echo "6. USER INFORMATION\n";
echo "==================\n";
$users = \App\Models\User::where('tenant_id', $tenant->id ?? 1)->get();
echo "Total users: " . $users->count() . "\n";

foreach ($users as $user) {
    echo "- User ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
}
echo "\n";

// 7. Test API routes
echo "7. ROUTE TESTING\n";
echo "================\n";
$routes = [
    'dashboard/shift/current' => 'GET',
    'dashboard/shift/checkout' => 'POST',
    'shifts/open' => 'POST',
    'shifts/close' => 'POST',
    'shifts/current' => 'GET'
];

foreach ($routes as $route => $method) {
    $routeExists = \Illuminate\Support\Facades\Route::has($route);
    echo "- {$method} /{$route}: " . ($routeExists ? '✅ EXISTS' : '❌ MISSING') . "\n";
}
echo "\n";

// 8. Check recent logs
echo "8. RECENT LOGS\n";
echo "==============\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = shell_exec("tail -20 " . escapeshellarg($logFile));
    echo "Last 20 log entries:\n";
    echo $logs . "\n";
} else {
    echo "❌ Log file not found\n";
}

// 9. Database connection test
echo "9. DATABASE CONNECTION\n";
echo "======================\n";
try {
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connected successfully\n";
    echo "Database: " . $pdo->query("SELECT DATABASE()")->fetchColumn() . "\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\n=== END OF DEBUG REPORT ===\n";
echo "Please share this output to diagnose the shift checkout issue.\n";
?>
