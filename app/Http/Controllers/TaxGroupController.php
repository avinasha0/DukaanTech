<?php

namespace App\Http\Controllers;

use App\Models\TaxGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaxGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-taxes');
    }

    /**
     * Display a listing of tax groups.
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $taxGroups = TaxGroup::where('tenant_id', $tenantId)
            ->withCount('taxRates')
            ->orderBy('name')
            ->paginate(15);

        return view('tenant.tax-groups.index', compact('taxGroups'));
    }

    /**
     * Show the form for creating a new tax group.
     */
    public function create()
    {
        return view('tenant.tax-groups.create');
    }

    /**
     * Store a newly created tax group.
     */
    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tax_groups', 'code')->where('tenant_id', $tenantId),
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['tenant_id'] = $tenantId;

        TaxGroup::create($data);

        return redirect()->route('tenant.tax-groups.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Tax group created successfully.');
    }

    /**
     * Display the specified tax group.
     */
    public function show($tenant, TaxGroup $taxGroup)
    {
        if ($taxGroup->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $taxGroup->load('taxRates');
        return view('tenant.tax-groups.show', compact('taxGroup'));
    }

    /**
     * Show the form for editing the specified tax group.
     */
    public function edit($tenant, TaxGroup $taxGroup)
    {
        if ($taxGroup->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        return view('tenant.tax-groups.edit', compact('taxGroup'));
    }

    /**
     * Update the specified tax group.
     */
    public function update(Request $request, $tenant, TaxGroup $taxGroup)
    {
        if ($taxGroup->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $tenantId = Auth::user()->tenant_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tax_groups', 'code')->where('tenant_id', $tenantId)->ignore($taxGroup->id),
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $taxGroup->update($data);

        return redirect()->route('tenant.tax-groups.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Tax group updated successfully.');
    }

    /**
     * Remove the specified tax group.
     */
    public function destroy($tenant, TaxGroup $taxGroup)
    {
        if ($taxGroup->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        // Check if tax group has associated tax rates
        if ($taxGroup->taxRates()->count() > 0) {
            return redirect()->route('tenant.tax-groups.index', ['tenant' => Auth::user()->tenant->slug])
                ->with('error', 'Cannot delete tax group with associated tax rates.');
        }

        $taxGroup->delete();

        return redirect()->route('tenant.tax-groups.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Tax group deleted successfully.');
    }

    /**
     * Toggle tax group active status.
     */
    public function toggle($tenant, TaxGroup $taxGroup)
    {
        if ($taxGroup->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $taxGroup->update(['is_active' => !$taxGroup->is_active]);

        $status = $taxGroup->is_active ? 'activated' : 'deactivated';
        return redirect()->route('tenant.tax-groups.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', "Tax group {$status} successfully.");
    }

    /**
     * Get all active tax groups for API.
     */
    public function getActive()
    {
        $tenantId = Auth::user()->tenant_id;
        $taxGroups = TaxGroup::where('tenant_id', $tenantId)
            ->active()
            ->with('taxRates')
            ->get();

        return response()->json($taxGroups);
    }
}