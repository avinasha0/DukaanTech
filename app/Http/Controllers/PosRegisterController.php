<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Outlet;
use App\Models\Shift;
use App\Models\TerminalSession;
use App\Models\TerminalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PosRegisterController extends Controller
{
    /**
     * Display the POS register page.
     */
    public function index(Request $request, $tenant)
    {
        $account = app('tenant'); // Get tenant from middleware context
        
        // Get the first available outlet for this tenant, or default to 1
        $outlet = Outlet::where('tenant_id', $account->id)
            ->where('is_active', true)
            ->first();
        
        $outletId = $outlet ? $outlet->id : 1;
        
        // Check for active shift server-side
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
        
        // Get the first available outlet for this tenant, or default to 1
        $outlet = Outlet::where('tenant_id', $account->id)
            ->where('is_active', true)
            ->first();
        
        $outletId = $outlet ? $outlet->id : 1;
        
        // Check for active shift server-side
        $activeShift = Shift::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->whereNull('closed_at')
            ->first();
        
        // Check for terminal session
        $sessionToken = $request->cookie('terminal_session_token');
        $terminalUser = null;
        $isTerminalAuth = false;
        
        if ($sessionToken) {
            $session = TerminalSession::where('session_token', $sessionToken)
                ->where('expires_at', '>', now())
                ->with('terminalUser')
                ->first();
            
            if ($session && $session->terminalUser && $session->isValid()) {
                $terminalUser = $session->terminalUser;
                $isTerminalAuth = true;
            }
        }
        
        // If no terminal authentication found, redirect to terminal login
        if (!$isTerminalAuth) {
            return redirect()->route('terminal.login', ['tenant' => $tenant])
                ->with('error', 'Please login with terminal credentials to access the POS terminal');
        }
        
        // If no active shift found, redirect to shift opening page
        if (!$activeShift) {
            return redirect()->route('tenant.pos.shift-open', ['tenant' => $tenant])
                ->with('info', 'Please open a shift to access the POS terminal');
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

    /**
     * Display the POS register page for authenticated terminal users only.
     * This method requires terminal authentication via middleware.
     */
    public function terminalAuthenticated(Request $request, $tenant)
    {
        $account = app('tenant'); // Get tenant from middleware context
        
        // Get the first available outlet for this tenant, or default to 1
        $outlet = Outlet::where('tenant_id', $account->id)
            ->where('is_active', true)
            ->first();
        
        $outletId = $outlet ? $outlet->id : 1;
        
        // Check for active shift server-side
        $activeShift = Shift::where('tenant_id', $account->id)
            ->where('outlet_id', $outletId)
            ->whereNull('closed_at')
            ->first();
        
        // Get terminal user from middleware (terminal.auth middleware ensures this exists)
        $terminalUser = $request->attributes->get('terminal_user');
        
        return view('pos.register', [
            'tenant' => $account,
            'activeShift' => $activeShift,
            'outletId' => $outletId,
            'kotEnabled' => $account->kot_enabled,
            'terminalUser' => $terminalUser,
            'isTerminalAuth' => true,
            'isRegularAuth' => false
        ]);
    }

    /**
     * Display the shift opening screen for terminal users
     */
    public function shiftOpen(Request $request, $tenant)
    {
        $account = app('tenant'); // Get tenant from middleware context
        
        // Get the current authenticated user
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access the POS terminal');
        }
        
        // Find or create terminal user for web user
        $terminalUser = TerminalUser::where('user_id', $user->id)->first();
        if (!$terminalUser) {
            $terminalUser = TerminalUser::create([
                'tenant_id' => $account->id,
                'terminal_id' => 'WEB_' . $user->id,
                'name' => $user->name,
                'pin' => '0000',
                'role' => 'cashier',
                'is_active' => true,
                'user_id' => $user->id,
            ]);
        }

        // Get available outlets
        $outlets = Outlet::where('tenant_id', $account->id)
            ->where('is_active', true)
            ->get();

        // Check if there's already an open shift
        $existingShift = Shift::where('tenant_id', $account->id)
            ->where('opened_by', $user->id)
            ->whereNull('closed_at')
            ->first();

        return view('pos.shift-open', [
            'tenant' => $account,
            'terminalUser' => $terminalUser,
            'outlets' => $outlets,
            'existingShift' => $existingShift,
        ]);
    }

    /**
     * Automatically open a shift for the user
     */
    private function openShiftForUser($user, $tenant, $outletId = null)
    {
        try {
            // Get the outlet
            $outlet = null;
            if ($outletId) {
                $outlet = Outlet::where('tenant_id', $tenant->id)
                    ->where('id', $outletId)
                    ->first();
            } else {
                $outlet = Outlet::where('tenant_id', $tenant->id)
                    ->where('is_active', true)
                    ->first();
            }
            
            if (!$outlet) {
                Log::warning("No outlet found for tenant {$tenant->id}, cannot open shift");
                return null;
            }

            // Check if there's already an open shift for this user
            $existingShift = Shift::where('tenant_id', $tenant->id)
                ->where('opened_by', $user->id)
                ->where('outlet_id', $outlet->id)
                ->whereNull('closed_at')
                ->first();

            if ($existingShift) {
                Log::info("User {$user->id} already has an open shift: {$existingShift->id}");
                return $existingShift;
            }

            // Create a new shift
            $shift = Shift::create([
                'tenant_id' => $tenant->id,
                'opened_by' => $user->id,
                'outlet_id' => $outlet->id,
                'opening_float' => 0, // Default opening float
            ]);

            Log::info("Automatically opened shift {$shift->id} for user {$user->id}");
            
            return $shift;
        } catch (\Exception $e) {
            Log::error("Failed to open shift for user {$user->id}: " . $e->getMessage());
            return null;
        }
    }
}
