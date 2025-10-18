<?php

namespace App\Http\Controllers;

use App\Models\TerminalUser;
use App\Models\TerminalSession;
use App\Models\TerminalLoginLog;
use App\Models\Device;
use App\Models\Shift;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TerminalAuthController extends Controller
{
    /**
     * Show terminal login form
     */
    public function showLogin(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Get available devices for this tenant
        $devices = Device::where('tenant_id', $tenant->id)
            ->where('type', 'POS')
            ->get();

        return view('terminal.login', compact('tenant', 'devices'));
    }

    /**
     * Handle terminal login
     */
    public function login(Request $request)
    {
        $request->validate([
            'terminal_id' => 'required|string|max:20',
            'pin' => 'required|string|min:4|max:6',
            'device_id' => 'nullable|exists:devices,id',
            'g-recaptcha-response' => 'required|string',
        ]);

        // Verify reCAPTCHA
        $recaptcha = new \ReCaptcha\ReCaptcha(config('services.recaptcha.secret_key'));
        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());

        if (!$response->isSuccess()) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => ['Please complete the reCAPTCHA verification.'],
            ]);
        }

        $tenant = app('tenant');
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Rate limiting
        $key = 'terminal_login:' . $request->ip() . ':' . $request->terminal_id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'terminal_id' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        // Find terminal user
        $terminalUser = TerminalUser::where('tenant_id', $tenant->id)
            ->where('terminal_id', $request->terminal_id)
            ->where('is_active', true)
            ->first();

        if (!$terminalUser || !$terminalUser->verifyPin($request->pin)) {
            RateLimiter::hit($key, 300); // 5 minutes lockout
            return response()->json([
                'message' => 'Invalid terminal ID or PIN.',
                'errors' => [
                    'terminal_id' => ['Invalid terminal ID or PIN.']
                ]
            ], 422);
        }

        // Clear rate limiting on successful login
        RateLimiter::clear($key);

        // Clean up expired sessions first (globally and for this specific user)
        $cleanupResult = TerminalSession::comprehensiveCleanup();
        $cleanedCount = $terminalUser->cleanupExpiredSessions();
        \Log::info("Cleaned up sessions for terminal user: {$terminalUser->terminal_id}", [
            'user_cleaned' => $cleanedCount,
            'global_cleanup' => $cleanupResult
        ]);

        // Allow multiple sessions - no need to check for existing sessions

        // Get device if provided
        $device = null;
        if ($request->device_id) {
            $device = Device::where('tenant_id', $tenant->id)
                ->where('id', $request->device_id)
                ->first();
        }

        // Create session
        $session = $terminalUser->createSession($device?->id, 8); // 8 hour session

        // Update last login
        $terminalUser->updateLastLogin();

        // Log the login event
        TerminalLoginLog::logAction(
            tenantId: $tenant->id,
            terminalUserId: $terminalUser->id,
            action: 'login',
            deviceId: $device?->id,
            sessionToken: $session->session_token,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            metadata: [
                'device_name' => $device?->name,
                'device_type' => $device?->type,
                'session_duration_hours' => 8,
                'login_method' => 'terminal_pin'
            ]
        );

        // Set session cookie
        $response = response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $terminalUser->id,
                'terminal_id' => $terminalUser->terminal_id,
                'name' => $terminalUser->name,
                'role' => $terminalUser->role,
            ],
            'session_token' => $session->session_token,
            'expires_at' => $session->expires_at->toISOString(),
        ]);

        // Set HTTP-only cookie for session token
        $response->cookie('terminal_session_token', $session->session_token, 480, '/', null, true, true); // 8 hours

        return $response;
    }

    /**
     * Handle terminal logout
     */
    public function logout(Request $request)
    {
        $sessionToken = $request->header('X-Terminal-Session-Token') ?? $request->cookie('terminal_session_token');
        
        if ($sessionToken) {
            // Find the session to get the terminal user
            $session = TerminalSession::where('session_token', $sessionToken)->first();
            
            if ($session) {
                $terminalUser = $session->terminalUser;
                $tenant = $terminalUser->tenant;
                
                // Log the logout event before deleting sessions
                TerminalLoginLog::logAction(
                    tenantId: $tenant->id,
                    terminalUserId: $terminalUser->id,
                    action: 'logout',
                    deviceId: $session->device_id,
                    sessionToken: $sessionToken,
                    ipAddress: $request->ip(),
                    userAgent: $request->userAgent(),
                    metadata: [
                        'device_name' => $session->device?->name,
                        'session_duration' => $session->created_at->diffInMinutes(now()) . ' minutes',
                        'logout_method' => 'manual'
                    ]
                );
                
                // Logout only the current session
                $session->delete();
                \Log::info("Terminal user logout: {$terminalUser->terminal_id}, deleted session {$session->id}");
            } else {
                \Log::warning("Terminal session not found for logout: {$sessionToken}");
            }
        } else {
            \Log::warning("No session token provided for logout");
        }

        $response = response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);

        // Clear session cookie
        $response->cookie('terminal_session_token', '', -1, '/', null, true, true);

        return $response;
    }

    /**
     * Get current terminal session info
     */
    public function session(Request $request)
    {
        $terminalUser = $request->attributes->get('terminal_user');
        $session = $request->attributes->get('terminal_session');
        $device = $request->attributes->get('device');

        return response()->json([
            'user' => [
                'id' => $terminalUser->id,
                'terminal_id' => $terminalUser->terminal_id,
                'name' => $terminalUser->name,
                'role' => $terminalUser->role,
            ],
            'device' => $device ? [
                'id' => $device->id,
                'name' => $device->name,
                'type' => $device->type,
            ] : null,
            'session' => [
                'expires_at' => $session->expires_at->toISOString(),
                'last_activity' => $session->last_activity_at->toISOString(),
            ]
        ]);
    }

    /**
     * Validate terminal session (for AJAX calls)
     */
    public function validateSession(Request $request)
    {
        $terminalUser = $request->attributes->get('terminal_user');
        $session = $request->attributes->get('terminal_session');

        if (!$terminalUser || !$session) {
            return response()->json([
                'valid' => false,
                'message' => 'Session not found'
            ], 401);
        }

        return response()->json([
            'valid' => true,
            'user' => [
                'terminal_id' => $terminalUser->terminal_id,
                'name' => $terminalUser->name,
                'role' => $terminalUser->role,
            ]
        ]);
    }

    /**
     * Extend terminal session
     */
    public function extend(Request $request)
    {
        $session = $request->attributes->get('terminal_session');
        
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 401);
        }

        $session->extend(8); // Extend by 8 hours

        return response()->json([
            'success' => true,
            'expires_at' => $session->expires_at->toISOString()
        ]);
    }

    /**
     * Automatically open a shift for the terminal user
     */
    private function openShiftForUser(TerminalUser $terminalUser, $tenant)
    {
        try {
            // Get the default outlet for this tenant
            $outlet = Outlet::where('tenant_id', $tenant->id)->first();
            
            if (!$outlet) {
                \Log::warning("No outlet found for tenant {$tenant->id}, cannot open shift");
                return null;
            }

            // Ensure TerminalUser has a linked User record
            if (!$terminalUser->user_id) {
                $user = User::create([
                    'tenant_id' => $tenant->id,
                    'name' => $terminalUser->name,
                    'email' => $terminalUser->terminal_id . '@terminal.local',
                    'password' => bcrypt('terminal_password'), // Default password
                    'role' => 'terminal_user',
                    'is_active' => true,
                ]);
                
                $terminalUser->update(['user_id' => $user->id]);
                \Log::info("Created User record {$user->id} for TerminalUser {$terminalUser->terminal_id}");
            }

            // Check if there's already an open shift for this user
            $existingShift = Shift::where('tenant_id', $tenant->id)
                ->where('opened_by', $terminalUser->user_id)
                ->where('outlet_id', $outlet->id)
                ->whereNull('closed_at')
                ->first();

            if ($existingShift) {
                \Log::info("Terminal user {$terminalUser->terminal_id} already has an open shift: {$existingShift->id}");
                return $existingShift;
            }

            // Create a new shift
            $shift = Shift::create([
                'tenant_id' => $tenant->id,
                'opened_by' => $terminalUser->user_id,
                'outlet_id' => $outlet->id,
                'opening_float' => 0, // Default opening float
            ]);

            \Log::info("Automatically opened shift {$shift->id} for terminal user {$terminalUser->terminal_id}");
            
            return $shift;
        } catch (\Exception $e) {
            \Log::error("Failed to open shift for terminal user {$terminalUser->terminal_id}: " . $e->getMessage());
            return null;
        }
    }
}