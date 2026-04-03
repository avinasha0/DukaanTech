<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\TerminalSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * For browser pages: allow Laravel web auth or a valid terminal session; otherwise redirect to terminal login.
 */
class RedirectUnlessWebOrTerminal
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            return $next($request);
        }

        if (TerminalSession::terminalUserFromHttpRequest($request)) {
            return $next($request);
        }

        $tenantSlug = $request->route('tenant');

        return redirect()
            ->route('terminal.login', ['tenant' => $tenantSlug])
            ->with('error', 'Please sign in with your terminal PIN to continue.');
    }
}
