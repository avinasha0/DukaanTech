<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        Order::observe(OrderObserver::class);

        // Define permission gates
        Gate::define('manage-users', function ($user) {
            return $user->hasPermission('manage-users') || $user->isAdmin();
        });

        Gate::define('manage-roles', function ($user) {
            return $user->hasPermission('manage-roles') || $user->isAdmin();
        });

        Gate::define('manage-settings', function ($user) {
            return $user->hasPermission('manage-settings') || $user->isAdmin() || $user->isManager();
        });

        Gate::define('view-reports', function ($user) {
            return $user->hasPermission('view-reports') || $user->isAdmin() || $user->isManager();
        });

        Gate::define('manage-menu', function ($user) {
            return $user->hasPermission('manage-menu') || $user->isAdmin() || $user->isManager();
        });

        Gate::define('create-orders', function ($user) {
            return $user->hasPermission('create-orders') || $user->isAdmin() || $user->isManager() || $user->isCashier();
        });
    }
}
