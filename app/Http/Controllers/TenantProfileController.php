<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class TenantProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $tenant = $user->tenant;
        
        return view('tenant.profile.edit', [
            'user' => $user,
            'tenant' => $tenant,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('tenant.profile.edit', $user->tenant->slug)
            ->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('tenant.profile.edit', $request->user()->tenant->slug)
            ->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $tenant = $user->tenant;

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Show user activity and statistics.
     */
    public function activity(Request $request): View
    {
        $user = $request->user();
        $tenant = $user->tenant;
        
        // Get user's recent activity
        $recentAudits = $user->audits()
            ->with(['auditable'])
            ->latest()
            ->limit(10)
            ->get();
            
        // Get user's shift statistics
        $shifts = $user->shifts()
            ->where('tenant_id', $tenant->id)
            ->latest()
            ->limit(5)
            ->get();
            
        // Get user's role information
        $roles = $user->roles()->get();
        
        return view('tenant.profile.activity', [
            'user' => $user,
            'tenant' => $tenant,
            'recentAudits' => $recentAudits,
            'shifts' => $shifts,
            'roles' => $roles,
        ]);
    }

    /**
     * Show user preferences and settings.
     */
    public function preferences(Request $request): View
    {
        $user = $request->user();
        $tenant = $user->tenant;
        
        // Set default notifications if not set
        if (!$user->notifications) {
            $user->notifications = [
                'email' => true,
                'orders' => true,
                'system' => true,
                'marketing' => false,
            ];
        }
        
        return view('tenant.profile.preferences', [
            'user' => $user,
            'tenant' => $tenant,
        ]);
    }

    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'timezone' => ['nullable', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'max:10'],
            'notifications' => ['nullable', 'array'],
            'theme' => ['nullable', 'string', 'in:light,dark,auto'],
        ]);

        $user = $request->user();
        $user->update($validated);

        return Redirect::route('tenant.profile.preferences', $user->tenant->slug)
            ->with('status', 'preferences-updated');
    }
}
