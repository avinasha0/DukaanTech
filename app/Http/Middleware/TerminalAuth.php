<?php

namespace App\Http\Middleware;

use App\Models\TerminalSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TerminalAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionToken = $request->header('X-Terminal-Session-Token') ?? $request->cookie('terminal_session_token');
        
        if (!$sessionToken) {
            return response()->json([
                'error' => 'Terminal session required',
                'message' => 'Please login to access the terminal'
            ], 401);
        }

        $session = TerminalSession::where('session_token', $sessionToken)
            ->with(['terminalUser', 'device'])
            ->first();

        if (!$session || !$session->isValid()) {
            return response()->json([
                'error' => 'Invalid or expired session',
                'message' => 'Please login again'
            ], 401);
        }

        // Update session activity
        $session->updateActivity();

        // Set terminal user context
        $request->attributes->set('terminal_user', $session->terminalUser);
        $request->attributes->set('terminal_session', $session);
        
        if ($session->device) {
            $request->attributes->set('device', $session->device);
        }

        // Set tenant context if not already set
        if (!$request->attributes->has('tenant')) {
            $request->attributes->set('tenant', $session->terminalUser->tenant);
        }

        return $next($request);
    }
}