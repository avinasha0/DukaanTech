<?php

use App\Http\Controllers\TerminalAuthController;
use App\Http\Controllers\TerminalLoginLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Terminal API Routes
|--------------------------------------------------------------------------
|
| These routes handle terminal-specific API endpoints for POS systems.
| They require terminal authentication middleware.
|
*/

// Terminal authentication routes
Route::prefix('terminal')->group(function () {
    Route::get('/login', [TerminalAuthController::class, 'showLogin'])->name('terminal.login');
    Route::post('/login', [TerminalAuthController::class, 'login']);
    Route::post('/logout', [TerminalAuthController::class, 'logout']);
});

// Terminal login logs (requires terminal auth)
Route::middleware(['terminal.auth'])->group(function () {
    Route::get('/terminal/logs', [TerminalLoginLogController::class, 'index'])->name('terminal.logs');
    Route::get('/terminal/logs/{terminalUser}', [TerminalLoginLogController::class, 'userLogs'])->name('terminal.logs.user');
});
