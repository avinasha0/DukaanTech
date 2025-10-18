<?php

namespace App\Http\Middleware;

use App\Models\Shift;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureShiftOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = app('tenant.id');
        
        $openShift = Shift::where('tenant_id', $tenantId)
            ->whereNull('closed_at')
            ->first();
            
        if (!$openShift) {
            return response()->json([
                'error' => 'No open shift found. Please open a shift before proceeding.'
            ], 403);
        }
        
        app()->instance('shift', $openShift);
        
        return $next($request);
    }
}
