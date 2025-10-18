<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/login', function () {
    return '<h1>Login Page</h1><p>This is a simple login page for Dukaantech POS</p><a href="/register">Go to Register</a>';
})->name('login');

Route::get('/register', function () {
    return '<h1>Register Page</h1><p>This is a simple register page for Dukaantech POS</p><a href="/login">Go to Login</a>';
})->name('register');

// Custom Registration Routes (if needed for backend functionality)
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/register/success', [RegisterController::class, 'registerSuccess'])->name('register.success');
Route::get('/activate/{token}', [RegisterController::class, 'activate'])->name('activate');

// Organization Setup
Route::middleware(['auth'])->group(function () {
    Route::get('/organization/setup', [OrganizationController::class, 'showSetupForm'])->name('organization.setup');
    Route::post('/organization/setup', [OrganizationController::class, 'setup']);
});

// Removed default dashboard route - using custom redirect logic in AuthenticatedSessionController

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
