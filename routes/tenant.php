<?php

use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\Catalog\CategoryController;
use App\Http\Controllers\Tenant\Catalog\ItemController;
use App\Http\Controllers\Tenant\Catalog\ModifierController;
use App\Http\Controllers\Tenant\OrderTypeController;
use App\Http\Controllers\Tenant\OrdersController;
use App\Http\Controllers\Tenant\Pos\OrderController;
use App\Http\Controllers\Tenant\Pos\BillController;
use App\Http\Controllers\Tenant\Pos\PaymentController;
use App\Http\Controllers\Tenant\Pos\KotController;
use App\Http\Controllers\Tenant\Pos\ShiftController;
use App\Http\Controllers\Tenant\BillTemplateController;
use App\Http\Controllers\Tenant\SettingsController;
use App\Http\Controllers\Tenant\KotSettingsController;
use App\Http\Controllers\Tenant\ReportsController;
use App\Http\Controllers\Tenant\DeviceController;
use App\Http\Controllers\Tenant\TerminalUserController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\TaxGroupController;
use App\Http\Controllers\Tenant\QRCodeController;
use App\Http\Controllers\Tenant\RestaurantTableController;
use App\Http\Controllers\Tenant\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Models\Device;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');

// Analytics routes
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('tenant.analytics');
Route::get('/analytics/sales-data', [AnalyticsController::class, 'getSalesData']);
Route::get('/analytics/top-items', [AnalyticsController::class, 'getTopItems']);
Route::get('/analytics/category-performance', [AnalyticsController::class, 'getCategoryPerformance']);
Route::get('/analytics/shift-analytics', [AnalyticsController::class, 'getShiftAnalytics']);
Route::get('/analytics/order-type-analytics', [AnalyticsController::class, 'getOrderTypeAnalytics']);
Route::get('/analytics/summary-stats', [AnalyticsController::class, 'getSummaryStats']);

// Table Management routes
Route::get('/tables', function() {
    $tenant = app('tenant');
    return view('tenant.tables.index', compact('tenant'));
})->name('tenant.tables.index');

// API routes for table management
Route::apiResource('api/tables', RestaurantTableController::class);
Route::patch('/api/tables/{table}/status', [RestaurantTableController::class, 'updateStatus'])->name('tables.update-status');

// KOT Dashboard route is defined in web.php

// Settings Management
Route::get('/settings', [SettingsController::class, 'index'])->name('tenant.settings');
Route::post('/settings/general', [SettingsController::class, 'updateGeneral']);
Route::get('/settings/bill-format', [SettingsController::class, 'getBillFormat']);
Route::post('/settings/bill-format', [SettingsController::class, 'updateBillFormat']);
Route::post('/settings/preview-bill', [SettingsController::class, 'previewBillFormat']);

// Handle old slug redirects (catch-all for old URLs)
Route::get('/old-slug-redirect/{oldSlug}', [SettingsController::class, 'handleOldSlugRedirect'])->name('tenant.old-slug-redirect');

// Outlet Management
Route::post('/settings/outlets', [SettingsController::class, 'storeOutlet']);
Route::put('/settings/outlets/{outlet}', [SettingsController::class, 'updateOutlet']);
Route::delete('/settings/outlets/{outlet}', [SettingsController::class, 'destroyOutlet']);
Route::get('/outlets', function() {
    return \App\Models\Outlet::where('tenant_id', app('tenant.id'))->get();
});

// KOT Settings routes
Route::post('/kot/toggle', [KotSettingsController::class, 'toggle']);
Route::get('/kot/status', [KotSettingsController::class, 'getStatus']);
Route::post('/kot/settings', [KotSettingsController::class, 'updateSettings']);

// QR Code Management routes
Route::get('/qr-codes', [QRCodeController::class, 'index'])->name('tenant.qr-codes');
Route::get('/qr-codes/debug', [QRCodeController::class, 'debugQRCodes']);
Route::post('/qr-codes/generate-menu', [QRCodeController::class, 'generateMenuQR']);
Route::post('/qr-codes/generate-category/{category}', [QRCodeController::class, 'generateCategoryQR']);
Route::post('/qr-codes/generate-item/{item}', [QRCodeController::class, 'generateItemQR']);
Route::post('/qr-codes/generate-table', [QRCodeController::class, 'generateTableQR']);
Route::post('/qr-codes/download', [QRCodeController::class, 'downloadQR']);
Route::post('/qr-codes/test-delete/{qrCodeId}', [QRCodeController::class, 'testDelete'])->where('qrCodeId', '[0-9]+');
Route::post('/qr-codes/{qrCodeId}/delete', [QRCodeController::class, 'deleteQR'])->where('qrCodeId', '[0-9]+');

// POS Register route is now handled by web.php as tenant.pos.register.public

// Catalog routes
Route::apiResource('categories', CategoryController::class);
Route::apiResource('items', ItemController::class);
Route::post('items/{item}/price', [ItemController::class, 'setPrice']);
Route::post('menu/import', [ItemController::class, 'import']);
Route::get('menu/template', [ItemController::class, 'downloadTemplate']);

// Order types routes
Route::apiResource('order-types', OrderTypeController::class);

// Orders management routes
Route::get('/orders', [OrdersController::class, 'index'])->name('tenant.orders');
Route::get('/api/orders', [OrdersController::class, 'index']); // API endpoint for AJAX
Route::delete('/orders/{order}', [OrdersController::class, 'destroy'])->name('tenant.orders.destroy');

// Orders management API routes (moved to tenant-api.php)

// Dashboard statistics - moved to tenant-api.php

// Customer routes
Route::get('customers', [DashboardController::class, 'getCustomers']);

// Modifier routes
Route::get('modifiers', [ModifierController::class, 'index']);
Route::post('modifier-groups', [ModifierController::class, 'storeGroup']);
Route::post('modifiers', [ModifierController::class, 'storeModifier']);
Route::put('modifiers/{modifier}', [ModifierController::class, 'updateModifier']);
Route::delete('modifiers/{modifier}', [ModifierController::class, 'destroyModifier']);
Route::post('modifiers/attach', [ModifierController::class, 'attachToItem']);
Route::post('modifiers/detach', [ModifierController::class, 'detachFromItem']);

// POS core routes - moved to web.php to avoid conflicts
// Route::apiResource('orders', OrderController::class)->except(['update', 'destroy']);
// Route::post('orders/{order}/items', [OrderController::class, 'addItem']);
Route::put('orders/{order}/state', [OrderController::class, 'updateState']);

Route::apiResource('bills', BillController::class)->except(['update', 'destroy']);
Route::post('bills/{bill}/void', [BillController::class, 'void']);
Route::post('bills/{bill}/print', [BillController::class, 'print']);
Route::get('bills/{bill}/pdf', [BillController::class, 'generatePDF']);
Route::get('bills/{bill}/html', [BillController::class, 'generateHTML']);
Route::get('bills/{bill}/qr-code', [BillController::class, 'generateQRCode']);

Route::post('bills/{bill}/payments', [PaymentController::class, 'store']);
Route::get('bills/{bill}/payments', [PaymentController::class, 'index']);
Route::get('payment-methods', [PaymentController::class, 'getPaymentMethods']);
Route::post('bills/{bill}/calculate-change', [PaymentController::class, 'calculateChange']);

// Device API routes
Route::get('devices', [DeviceController::class, 'index']);
Route::post('devices', [DeviceController::class, 'store']);
Route::delete('devices/{device}', [DeviceController::class, 'destroy']);

Route::post('orders/{order}/kot', [KotController::class, 'fire']);
Route::put('kot/{kitchenTicket}/ready', [KotController::class, 'markReady']);

Route::post('shifts/open', [ShiftController::class, 'open'])->middleware('manager.pin');
Route::post('shifts/{shift}/close', [ShiftController::class, 'close']);
Route::get('shifts/current', [ShiftController::class, 'current']);
Route::apiResource('shifts', ShiftController::class)->except(['store', 'update', 'destroy']);

// Bill Template routes
Route::apiResource('bill-templates', BillTemplateController::class);
Route::post('bill-templates/{template}/set-default', [BillTemplateController::class, 'setDefault']);
Route::post('bill-templates/{template}/duplicate', [BillTemplateController::class, 'duplicate']);
Route::get('bill-templates/{template}/preview', [BillTemplateController::class, 'preview']);

// Reports routes
Route::get('reports', [ReportsController::class, 'index'])->name('tenant.reports');
Route::get('reports/logs', [ReportsController::class, 'logs'])->name('tenant.reports.logs');
Route::get('reports/quick-stats', [ReportsController::class, 'quickStats']);
Route::post('reports/log-error', [ReportsController::class, 'logError']);
Route::post('reports/logs', [ReportsController::class, 'logs']);
Route::post('reports/generate-test-data', [ReportsController::class, 'generateTestData']);
Route::post('reports/sales', [ReportsController::class, 'sales']);
Route::post('reports/top-items', [ReportsController::class, 'topItems']);
Route::post('reports/shift', [ReportsController::class, 'shiftReport']);
Route::post('reports/order-summary', [ReportsController::class, 'orderSummary']);
Route::post('reports/export', [ReportsController::class, 'export']);

// Tax Management routes
Route::resource('tax-groups', TaxGroupController::class);
Route::post('tax-groups/{taxGroup}/toggle', [TaxGroupController::class, 'toggle']);
Route::get('tax-groups-api/active', [TaxGroupController::class, 'getActive']);

Route::resource('taxes', TaxController::class);
Route::get('taxes-api/applicable', [TaxController::class, 'getApplicable']);
Route::post('taxes-api/calculate', [TaxController::class, 'calculate']);

// Discount Management routes
Route::resource('discounts', DiscountController::class)->names([
    'index' => 'tenant.discounts.index',
    'create' => 'tenant.discounts.create',
    'store' => 'tenant.discounts.store',
    'show' => 'tenant.discounts.show',
    'edit' => 'tenant.discounts.edit',
    'update' => 'tenant.discounts.update',
    'destroy' => 'tenant.discounts.destroy',
]);
Route::post('discounts/{discount}/toggle', [DiscountController::class, 'toggle'])->name('tenant.discounts.toggle');
Route::get('discounts-api/applicable', [DiscountController::class, 'getApplicable'])->name('tenant.discounts.applicable');
Route::post('discounts-api/calculate', [DiscountController::class, 'calculate'])->name('tenant.discounts.calculate');

