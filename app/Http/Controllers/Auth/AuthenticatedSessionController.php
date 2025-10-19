<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Shift;
use App\Models\Outlet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Get the authenticated user
        $user = Auth::user();
        
        // If user has a tenant, check if organization setup is complete
        if ($user->tenant_id) {
            $tenant = $user->tenant;
            
            // Check if organization setup is complete
            if ($this->isOrganizationSetupComplete($tenant)) {
                // Setup complete, redirect based on plan
                if ($tenant->plan === 'free') {
                    return redirect()->to(url("/{$tenant->slug}/dashboard"));
                } else {
                    return redirect()->to("https://{$tenant->slug}.dukaantech.com/dashboard");
                }
            } else {
                // Setup incomplete, redirect to organization setup
                return redirect()->route('organization.setup')
                    ->with('warning', 'Please complete your organization setup to continue.');
            }
        }
        
        // If no tenant, redirect to organization setup
        return redirect()->route('organization.setup');
    }

    /**
     * Check if organization setup is complete
     */
    private function isOrganizationSetupComplete($tenant): bool
    {
        // Check if tenant has at least one outlet
        if (!$tenant->outlets()->exists()) {
            return false;
        }
        
        // Check if tenant has basic settings
        if (!$tenant->settings || !isset($tenant->settings['currency'])) {
            return false;
        }
        
        return true;
    }

    /**
     * Automatically open a shift for the user
     */
    private function openShiftForUser($user, $tenant)
    {
        try {
            // Get the first available outlet for this tenant
            $outlet = Outlet::where('tenant_id', $tenant->id)
                ->where('is_active', true)
                ->first();
            
            if (!$outlet) {
                Log::warning("No active outlet found for tenant {$tenant->id}, cannot open shift");
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

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
