<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load POS routes
            require __DIR__.'/../routes/pos.php';
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'pos.access' => \App\Http\Middleware\EnsureWebOrTerminal::class,
            'web.or.terminal' => \App\Http\Middleware\RedirectUnlessWebOrTerminal::class,
            'resolve.tenant' => \App\Http\Middleware\ResolveTenant::class,
            'ensure.shift.open' => \App\Http\Middleware\EnsureShiftOpen::class,
            'manager.pin' => \App\Http\Middleware\ManagerPinGuard::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'terminal.auth' => \App\Http\Middleware\TerminalAuth::class,
            'restrict.kot.display.only' => \App\Http\Middleware\RestrictKotDisplayOnlyUsers::class,
            'ensure.kot.tenant' => \App\Http\Middleware\EnsureWebUserMatchesKotTenant::class,
        ]);
        
        // Laravel 11 uses ValidateCsrfToken in the web group (not VerifyCsrfToken).
        // The old remove targeted the wrong class, so CSRF stayed on and POST /logout could 419.
        // Exclude logout so users can sign out even if the session token is stale.
        $middleware->validateCsrfTokens(except: [
            'logout',
            '*/logout',
        ]);

        // Add tenant middleware to web routes
        $middleware->web(append: [
            \App\Http\Middleware\ResolveTenant::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
