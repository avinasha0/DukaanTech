<?php

use App\Http\Controllers\Tenant\OrderTypeController;
use App\Http\Controllers\Tenant\OrdersController;

// Order types routes
Route::apiResource('order-types', OrderTypeController::class);

// Orders API routes
Route::get('orders', [OrdersController::class, 'index']);
Route::get('orders/{order}', [OrdersController::class, 'show']);
Route::put('orders/{order}/status', [OrdersController::class, 'updateStatus']);

// Items and Categories API routes for menu
Route::get('items', [\App\Http\Controllers\Tenant\DashboardController::class, 'items']);
Route::get('categories', [\App\Http\Controllers\Tenant\DashboardController::class, 'categories']);

// Item management API routes
Route::put('items/{item}', [\App\Http\Controllers\Tenant\Catalog\ItemController::class, 'update']);
Route::delete('items/{item}', [\App\Http\Controllers\Tenant\Catalog\ItemController::class, 'destroy']);

// Dashboard statistics API routes
Route::get('dashboard/statistics', [\App\Http\Controllers\Tenant\DashboardController::class, 'statistics']);
Route::get('dashboard/shift/current', [\App\Http\Controllers\Tenant\DashboardController::class, 'getCurrentShift']);
Route::post('dashboard/shift/checkout', [\App\Http\Controllers\Tenant\DashboardController::class, 'checkoutShift']);
