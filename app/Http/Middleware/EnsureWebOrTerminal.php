<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\TerminalSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Allow POS JSON routes when either a web user or a valid terminal session is present.
class EnsureWebOrTerminal
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            return $next($request);
        }

        $sessionToken = $request->header('X-Terminal-Session-Token') ?? $request->cookie('terminal_session_token');

        if ($sessionToken && strlen((string) $sessionToken) > 100) {
            try {
                $sessionToken = decrypt($sessionToken);
            } catch (\Throwable $e) {
                $sessionToken = $request->header('X-Terminal-Session-Token');
            }
        }

        if ($sessionToken) {
            $session = TerminalSession::where('session_token', $sessionToken)
                ->where('expires_at', '>', now())
                ->first();

            if ($session) {
                return $next($request);
            }
        }

        return response()->json([
            'error' => 'Authentication required',
            'message' => 'Please log in to the POS (web user or terminal login).',
        ], 401);
    }
}
