<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use App\Models\TaxGroup;
use App\Models\Item;
use App\Models\OrderType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-taxes');
    }

    /**
     * Display a listing of tax rates.
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $taxRates = TaxRate::where('tenant_id', $tenantId)
            ->with('taxGroup')
            ->orderBy('name')
            ->paginate(15);

        return view('tenant.taxes.index', compact('taxRates'));
    }

    /**
     * Show the form for creating a new tax rate.
     */
    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $taxGroups = TaxGroup::where('tenant_id', $tenantId)->active()->get();
        $items = Item::where('tenant_id', $tenantId)->active()->get();
        $orderTypes = OrderType::where('tenant_id', $tenantId)->active()->get();

        return view('tenant.taxes.create', compact('taxGroups', 'items', 'orderTypes'));
    }

    /**
     * Store a newly created tax rate.
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
                Rule::unique('tax_rates', 'code')->where('tenant_id', $tenantId),
            ],
            'tax_group_id' => 'nullable|exists:tax_groups,id',
            'rate' => 'required|numeric|min:0|max:100',
            'inclusive' => 'boolean',
            'description' => 'nullable|string',
            'calculation_type' => 'required|in:percentage,fixed_amount',
            'fixed_amount' => 'nullable|numeric|min:0',
            'is_compound' => 'boolean',
            'applicable_items' => 'nullable|array',
            'applicable_items.*' => 'exists:items,id',
            'applicable_order_types' => 'nullable|array',
            'applicable_order_types.*' => 'exists:order_types,id',
            'regional_settings' => 'nullable|array',
            'is_active' => 'boolean',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        $data = $request->all();
        $data['tenant_id'] = $tenantId;

        // Validate fixed_amount is required when calculation_type is fixed_amount
        if ($data['calculation_type'] === 'fixed_amount' && !$data['fixed_amount']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fixed amount is required when calculation type is fixed amount.');
        }

        TaxRate::create($data);

        return redirect()->route('tenant.taxes.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Tax rate created successfully.');
    }

    /**
     * Display the specified tax rate.
     */
    public function show($tenant, TaxRate $taxRate)
    {
        if ($taxRate->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $taxRate->load('taxGroup');
        return view('tenant.taxes.show', compact('taxRate'));
    }

    /**
     * Show the form for editing the specified tax rate.
     */
    public function edit($tenant, TaxRate $taxRate)
    {
        if ($taxRate->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $tenantId = Auth::user()->tenant_id;
        $taxGroups = TaxGroup::where('tenant_id', $tenantId)->active()->get();
        $items = Item::where('tenant_id', $tenantId)->active()->get();
        $orderTypes = OrderType::where('tenant_id', $tenantId)->active()->get();

        return view('tenant.taxes.edit', compact('taxRate', 'taxGroups', 'items', 'orderTypes'));
    }

    /**
     * Update the specified tax rate.
     */
    public function update(Request $request, $tenant, TaxRate $taxRate)
    {
        if ($taxRate->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $tenantId = Auth::user()->tenant_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tax_rates', 'code')->where('tenant_id', $tenantId)->ignore($taxRate->id),
            ],
            'tax_group_id' => 'nullable|exists:tax_groups,id',
            'rate' => 'required|numeric|min:0|max:100',
            'inclusive' => 'boolean',
            'description' => 'nullable|string',
            'calculation_type' => 'required|in:percentage,fixed_amount',
            'fixed_amount' => 'nullable|numeric|min:0',
            'is_compound' => 'boolean',
            'applicable_items' => 'nullable|array',
            'applicable_items.*' => 'exists:items,id',
            'applicable_order_types' => 'nullable|array',
            'applicable_order_types.*' => 'exists:order_types,id',
            'regional_settings' => 'nullable|array',
            'is_active' => 'boolean',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        $data = $request->all();

        // Validate fixed_amount is required when calculation_type is fixed_amount
        if ($data['calculation_type'] === 'fixed_amount' && !$data['fixed_amount']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fixed amount is required when calculation type is fixed amount.');
        }

        $taxRate->update($data);

        return redirect()->route('tenant.taxes.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Tax rate updated successfully.');
    }

    /**
     * Remove the specified tax rate.
     */
    public function destroy($tenant, TaxRate $taxRate)
    {
        if ($taxRate->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $taxRate->delete();

        return redirect()->route('tenant.taxes.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Tax rate deleted successfully.');
    }

    /**
     * Get applicable tax rates for an item/order type combination.
     */
    public function getApplicable(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $itemId = $request->get('item_id');
        $orderType = $request->get('order_type');
        $region = $request->get('region');

        $taxRates = TaxRate::where('tenant_id', $tenantId)
            ->applicable($itemId, $orderType, $region)
            ->with('taxGroup')
            ->get();

        return response()->json($taxRates);
    }

    /**
     * Calculate tax for a given amount.
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'item_id' => 'nullable|exists:items,id',
            'order_type' => 'nullable|string',
            'region' => 'nullable|string',
        ]);

        $taxRate = TaxRate::find($request->tax_rate_id);
        
        if ($taxRate->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $taxAmount = $taxRate->calculateTax(
            $request->amount,
            $request->item_id,
            $request->order_type,
            $request->region
        );

        return response()->json([
            'tax_amount' => $taxAmount,
            'tax_rate' => $taxRate,
        ]);
    }
}