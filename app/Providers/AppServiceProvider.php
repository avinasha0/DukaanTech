<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Observers\OrderObserver;
use App\Observers\OrderItemObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Provide safe defaults so app('tenant*') never throws when
        // a request is outside tenant-resolved routes/middleware.
        $this->app->singleton('tenant', fn () => null);
        $this->app->singleton('tenant.id', fn () => null);
        $this->app->singleton('tenant.model', fn () => null);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        Order::observe(OrderObserver::class);
        OrderItem::observe(OrderItemObserver::class);

        // Define permission gates
        Gate::define('manage-users', function ($user) {
            return $user->hasPermission('view-users') || $user->isAdmin();
        });

        Gate::define('manage-roles', function ($user) {
            return $user->hasPermission('view-roles') || $user->isAdmin();
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
