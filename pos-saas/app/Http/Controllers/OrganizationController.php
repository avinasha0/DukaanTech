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
        $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_slug' => ['required', 'string', 'max:255', 'unique:accounts,slug', 'regex:/^[a-z0-9-]+$/'],
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
        ]);

        DB::beginTransaction();

        try {
            // Create organization (tenant)
            $account = Account::create([
                'name' => $request->organization_name,
                'slug' => $request->organization_slug,
                'settings' => [
                    'currency' => $request->currency,
                    'timezone' => $request->timezone,
                    'tax_inclusive' => true,
                ]
            ]);

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
                'name' => 'GST 5%',
                'code' => 'GST5',
                'rate' => 5.00,
                'inclusive' => true,
            ]);

            TaxRate::create([
                'tenant_id' => $account->id,
                'name' => 'GST 18%',
                'code' => 'GST18',
                'rate' => 18.00,
                'inclusive' => true,
            ]);

            // Update user with tenant_id
            Auth::user()->update([
                'tenant_id' => $account->id,
            ]);

            DB::commit();

            return redirect()->route('tenant.dashboard', ['tenant' => $account->slug])
                ->with('success', 'Organization setup completed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to setup organization. Please try again.');
        }
    }
}
