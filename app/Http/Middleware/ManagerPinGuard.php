<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerPinGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pin = $request->header('X-Manager-Pin');
        
        // For now, we'll use a simple hardcoded PIN
        // In production, this should be stored securely and validated against user records
        if ($pin !== '1111') {
            return response()->json([
                'error' => 'Invalid manager PIN'
            ], 401);
        }
        
        return $next($request);
    }
}
