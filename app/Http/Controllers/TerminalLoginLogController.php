<?php

namespace App\Http\Controllers;

use App\Models\TerminalLoginLog;
use App\Models\TerminalUser;
use Illuminate\Http\Request;

class TerminalLoginLogController extends Controller
{
    /**
     * Display login logs for all terminal users
     */
    public function index(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $query = TerminalLoginLog::where('tenant_id', $tenant->id)
            ->with(['terminalUser', 'device'])
            ->orderBy('logged_at', 'desc');

        // Filter by action if provided
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range if provided
        if ($request->has('from_date')) {
            $query->where('logged_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('logged_at', '<=', $request->to_date);
        }

        // Paginate results
        $logs = $query->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }

    /**
     * Display login logs for a specific terminal user
     */
    public function userLogs(Request $request, TerminalUser $terminalUser)
    {
        $tenant = app('tenant');
        if (!$tenant || $terminalUser->tenant_id !== $tenant->id) {
            return response()->json(['error' => 'Terminal user not found'], 404);
        }

        $query = TerminalLoginLog::where('terminal_user_id', $terminalUser->id)
            ->with(['device'])
            ->orderBy('logged_at', 'desc');

        // Filter by action if provided
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range if provided
        if ($request->has('from_date')) {
            $query->where('logged_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('logged_at', '<=', $request->to_date);
        }

        // Paginate results
        $logs = $query->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'terminal_user' => [
                'id' => $terminalUser->id,
                'terminal_id' => $terminalUser->terminal_id,
                'name' => $terminalUser->name,
                'role' => $terminalUser->role,
            ],
            'data' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }

    /**
     * Get login statistics for a terminal user
     */
    public function statistics(Request $request, TerminalUser $terminalUser)
    {
        $tenant = app('tenant');
        if (!$tenant || $terminalUser->tenant_id !== $tenant->id) {
            return response()->json(['error' => 'Terminal user not found'], 404);
        }

        $days = $request->get('days', 30);
        $startDate = now()->subDays($days);

        $stats = [
            'total_logins' => TerminalLoginLog::where('terminal_user_id', $terminalUser->id)
                ->where('action', 'login')
                ->where('logged_at', '>=', $startDate)
                ->count(),
            'total_logouts' => TerminalLoginLog::where('terminal_user_id', $terminalUser->id)
                ->whereIn('action', ['logout', 'force_logout', 'session_expired'])
                ->where('logged_at', '>=', $startDate)
                ->count(),
            'force_logouts' => TerminalLoginLog::where('terminal_user_id', $terminalUser->id)
                ->where('action', 'force_logout')
                ->where('logged_at', '>=', $startDate)
                ->count(),
            'session_expirations' => TerminalLoginLog::where('terminal_user_id', $terminalUser->id)
                ->where('action', 'session_expired')
                ->where('logged_at', '>=', $startDate)
                ->count(),
            'last_login' => TerminalLoginLog::where('terminal_user_id', $terminalUser->id)
                ->where('action', 'login')
                ->orderBy('logged_at', 'desc')
                ->first()?->logged_at,
            'last_logout' => TerminalLoginLog::where('terminal_user_id', $terminalUser->id)
                ->whereIn('action', ['logout', 'force_logout', 'session_expired'])
                ->orderBy('logged_at', 'desc')
                ->first()?->logged_at,
        ];

        return response()->json([
            'success' => true,
            'terminal_user' => [
                'id' => $terminalUser->id,
                'terminal_id' => $terminalUser->terminal_id,
                'name' => $terminalUser->name,
            ],
            'statistics' => $stats,
            'period_days' => $days
        ]);
    }
}
