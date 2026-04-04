<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users with the KOT display role (view-kot without view-dashboard) may only use
 * tenant profile/password routes and authenticated KOT API routes — not the main dashboard.
 */
class RestrictKotDisplayOnlyUsers
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->isKotDisplayOnly()) {
            return $next($request);
        }

        if ($this->isAllowedPathForKotDisplayOnly($request)) {
            return $next($request);
        }

        $slug = $this->resolveTenantSlug($request);
        if ($request->expectsJson() || $request->isJson()) {
            return response()->json([
                'message' => 'Kitchen display accounts can only access the KOT screen.',
            ], 403);
        }

        if ($slug) {
            return redirect()->to(url("/{$slug}/kot"));
        }

        abort(403, 'Kitchen display accounts can only access the KOT screen.');
    }

    private function resolveTenantSlug(Request $request): ?string
    {
        $fromRoute = $request->route('tenant');
        if (is_string($fromRoute) && $fromRoute !== '') {
            return $fromRoute;
        }

        if (app()->bound('tenant') && app('tenant')) {
            return app('tenant')->slug;
        }

        return null;
    }

    private function isAllowedPathForKotDisplayOnly(Request $request): bool
    {
        $slug = $this->resolveTenantSlug($request);
        if (! $slug) {
            return false;
        }

        $segments = explode('/', trim($request->path(), '/'));
        if ($segments === ['']) {
            $segments = [];
        }

        // {tenant}/profile (change password, etc.)
        if (count($segments) >= 2 && $segments[0] === $slug && $segments[1] === 'profile') {
            return true;
        }

        // {tenant}/api/kot and {tenant}/api/kot/{id}
        if (count($segments) >= 3 && $segments[0] === $slug && $segments[1] === 'api' && ($segments[2] ?? '') === 'kot') {
            return true;
        }

        return false;
    }
}
