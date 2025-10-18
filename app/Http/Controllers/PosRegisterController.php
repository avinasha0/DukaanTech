<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Shift;
use App\Models\TerminalSession;
use Illuminate\Http\Request;

class PosRegisterController extends Controller
{
    /**
     * Display the POS register page.
     */
    public function index(Request $request, $tenant)
    {
        $account = app('tenant'); // Get tenant from middleware context
        
        // Check for active shift server-side
        $outletId = 1; // Default outlet
        $activeShift = Shift::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->whereNull('closed_at')
            ->first();
        
        // Check if user is authenticated via terminal or regular auth
        $terminalUser = null;
        $isTerminalAuth = false;
        $isRegularAuth = false;
        
        // Check for regular web authentication first
        if (auth()->check()) {
            $isRegularAuth = true;
        }
        
        // Check for terminal session
        $sessionToken = $request->cookie('terminal_session_token');
        if ($sessionToken) {
            $session = TerminalSession::where('session_token', $sessionToken)
                ->where('expires_at', '>', now())
                ->with('terminalUser')
                ->first();
            
            if ($session && $session->terminalUser) {
                $terminalUser = $session->terminalUser;
                $isTerminalAuth = true;
            }
        }
        
        // If no authentication found, redirect to terminal login
        if (!$isTerminalAuth && !$isRegularAuth) {
            return redirect()->route('terminal.login', ['tenant' => $tenant])
                ->with('error', 'Please login to access the POS terminal');
        }
        
        return view('pos.register', [
            'tenant' => $account,
            'activeShift' => $activeShift,
            'outletId' => $outletId,
            'kotEnabled' => $account->kot_enabled,
            'terminalUser' => $terminalUser,
            'isTerminalAuth' => $isTerminalAuth,
            'isRegularAuth' => $isRegularAuth
        ]);
    }

    /**
     * Display the POS register page for terminal-only access.
     * This method only allows terminal authentication and blocks regular web authentication.
     */
    public function terminalOnly(Request $request, $tenant)
    {
        $account = app('tenant'); // Get tenant from middleware context
        
        // Check for active shift server-side
        $outletId = 1; // Default outlet
        $activeShift = Shift::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->whereNull('closed_at')
            ->first();
        
        // Check for terminal session only - NO regular web authentication allowed
        $terminalUser = null;
        $isTerminalAuth = false;
        
        // Check for terminal session
        $sessionToken = $request->cookie('terminal_session_token');
        if ($sessionToken) {
            $session = TerminalSession::where('session_token', $sessionToken)
                ->where('expires_at', '>', now())
                ->with('terminalUser')
                ->first();
            
            if ($session && $session->terminalUser) {
                $terminalUser = $session->terminalUser;
                $isTerminalAuth = true;
            }
        }
        
        // If no terminal authentication found, redirect to terminal login
        if (!$isTerminalAuth) {
            return redirect()->route('terminal.login', ['tenant' => $tenant])
                ->with('error', 'Please login with terminal credentials to access the POS terminal');
        }
        
        return view('pos.register', [
            'tenant' => $account,
            'activeShift' => $activeShift,
            'outletId' => $outletId,
            'kotEnabled' => $account->kot_enabled,
            'terminalUser' => $terminalUser,
            'isTerminalAuth' => $isTerminalAuth,
            'isRegularAuth' => false // Always false for terminal-only access
        ]);
    }
}
