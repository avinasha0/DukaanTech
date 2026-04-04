<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Account;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * KOT routes use /{tenant}/... — ensure the session user belongs to that tenant.
 */
class EnsureWebUserMatchesKotTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('tenant');
        if (! is_string($slug) || $slug === '') {
            abort(404);
        }

        $account = Account::where('slug', $slug)->first();
        if (! $account) {
            abort(404);
        }

        $user = Auth::user();
        if (! $user || ! $user->tenant_id || (int) $user->tenant_id !== (int) $account->id) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'You do not have access to this restaurant.',
                ], 403);
            }

            abort(403, 'You do not have access to this restaurant.');
        }

        return $next($request);
    }
}
