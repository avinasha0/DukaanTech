<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                    return redirect()->to("http://localhost:8000/{$tenant->slug}/dashboard");
                } else {
                    return redirect()->to("http://{$tenant->slug}.localhost:8000/dashboard");
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
