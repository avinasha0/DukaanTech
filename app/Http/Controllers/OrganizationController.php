<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Outlet;
use App\Models\TaxRate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    public function showSetupForm()
    {
        return view('organization.setup');
    }

    public function setup(Request $request)
    {
        $user = Auth::user();
        $existingAccount = Account::where('owner_id', $user->id)->first();
        
        $validationRules = [
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/'],
            'outlet_name' => ['required', 'string', 'max:255'],
            'outlet_code' => ['nullable', 'string', 'max:50'],
            'gstin' => ['nullable', 'string', 'max:15'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'country' => ['required', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:3'],
            'timezone' => ['required', 'string', 'max:50'],
        ];
        
        // Add unique validation for slug only if it's different from existing
        if (!$existingAccount || $existingAccount->slug !== $request->organization_slug) {
            $validationRules['organization_slug'][] = 'unique:accounts,slug';
        }
        
        $request->validate($validationRules);

        DB::beginTransaction();

        try {
            
            // Get or create account for the user
            $account = Account::where('owner_id', $user->id)->first();
            
            if (!$account) {
                // Create new account if none exists
                $account = Account::create([
                    'name' => $request->organization_name,
                    'slug' => $request->organization_slug,
                    'phone' => $user->phone ?? null,
                    'owner_id' => $user->id,
                    'status' => 'trial',
                    'plan' => 'free', // Default to free plan
                    'settings' => [
                        'currency' => $request->currency,
                        'timezone' => $request->timezone,
                        'tax_inclusive' => true,
                    ]
                ]);
            } else {
                // Update existing account
                $account->update([
                    'name' => $request->organization_name,
                    'slug' => $request->organization_slug,
                    'settings' => [
                        'currency' => $request->currency,
                        'timezone' => $request->timezone,
                        'tax_inclusive' => true,
                    ]
                ]);
            }

            // Create outlet
            $outlet = Outlet::create([
                'tenant_id' => $account->id,
                'name' => $request->outlet_name,
                'code' => $request->outlet_code,
                'gstin' => $request->gstin,
                'address' => [
                    'street' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'pincode' => $request->pincode,
                    'country' => $request->country,
                ]
            ]);

            // Create default tax rates
            TaxRate::create([
                'tenant_id' => $account->id,
                'code' => 'GST5',
                'rate' => 5.00,
                'inclusive' => true,
            ]);

            TaxRate::create([
                'tenant_id' => $account->id,
                'code' => 'GST18',
                'rate' => 18.00,
                'inclusive' => true,
            ]);

            // Update user with tenant_id
            $user->update([
                'tenant_id' => $account->id,
            ]);

            DB::commit();

            // Redirect based on plan
            if ($account->plan === 'free') {
                return redirect()->to("http://localhost:8000/{$account->slug}/dashboard")
                    ->with('success', 'Organization setup completed successfully!');
            } else {
                return redirect()->to("http://{$account->slug}.localhost:8000/dashboard")
                    ->with('success', 'Organization setup completed successfully!');
            }

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Organization setup failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request_data' => $request->all(),
                'exception' => $e
            ]);
            return back()->with('error', 'Failed to setup organization: ' . $e->getMessage());
        }
    }
}
