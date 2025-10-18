<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Item;
use App\Models\OrderType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-discounts');
    }

    /**
     * Display a listing of discounts.
     */
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $tenantId = $tenant->id;
        $discounts = Discount::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->paginate(15);

        return view('tenant.discounts.index', compact('discounts'))
            ->with('tenant', $tenant);
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create()
    {
        $tenant = Auth::user()->tenant;
        $tenantId = $tenant->id;
        $items = Item::where('tenant_id', $tenantId)->active()->get();
        $orderTypes = OrderType::where('tenant_id', $tenantId)->active()->get();

        return view('tenant.discounts.create', compact('items', 'orderTypes'))
            ->with('tenant', $tenant);
    }

    /**
     * Store a newly created discount.
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
                Rule::unique('discounts', 'code')->where('tenant_id', $tenantId),
            ],
            'type' => 'required|in:percentage,fixed_amount,buy_x_get_y',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
            'applicable_items' => 'nullable',
            'applicable_order_types' => 'nullable',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['tenant_id'] = $tenantId;
        
        // Handle comma-separated inputs
        if ($request->has('applicable_items') && is_string($request->applicable_items)) {
            $data['applicable_items'] = json_decode($request->applicable_items, true) ?: [];
        }
        if ($request->has('applicable_order_types') && is_string($request->applicable_order_types)) {
            $data['applicable_order_types'] = json_decode($request->applicable_order_types, true) ?: [];
        }
        
        // Handle checkbox
        $data['is_active'] = $request->has('is_active') ? true : false;

        // Validate buy_x_get_y specific fields
        if ($data['type'] === 'buy_x_get_y') {
            if (!$data['buy_quantity'] || !$data['get_quantity']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Buy quantity and Get quantity are required for Buy X Get Y discount type.');
            }
        }

        // Validate percentage limits
        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Percentage discount cannot exceed 100%.');
        }

        Discount::create($data);

        return redirect()->route('tenant.discounts.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Discount created successfully.');
    }

    /**
     * Display the specified discount.
     */
    public function show($tenant, Discount $discount)
    {
        if ($discount->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }
        $tenantModel = Auth::user()->tenant;
        return view('tenant.discounts.show', compact('discount'))
            ->with('tenant', $tenantModel);
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit($tenant, Discount $discount)
    {
        if ($discount->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }
        $tenantModel = Auth::user()->tenant;
        $tenantId = $tenantModel->id;
        $items = Item::where('tenant_id', $tenantId)->active()->get();
        $orderTypes = OrderType::where('tenant_id', $tenantId)->active()->get();

        return view('tenant.discounts.edit', compact('discount', 'items', 'orderTypes'))
            ->with('tenant', $tenantModel);
    }

    /**
     * Update the specified discount.
     */
    public function update(Request $request, $tenant, Discount $discount)
    {
        if ($discount->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $tenantId = Auth::user()->tenant_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('discounts', 'code')->where('tenant_id', $tenantId)->ignore($discount->id),
            ],
            'type' => 'required|in:percentage,fixed_amount,buy_x_get_y',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
            'applicable_items' => 'nullable',
            'applicable_order_types' => 'nullable',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        // Validate buy_x_get_y specific fields
        if ($data['type'] === 'buy_x_get_y') {
            if (!$data['buy_quantity'] || !$data['get_quantity']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Buy quantity and Get quantity are required for Buy X Get Y discount type.');
            }
        }

        // Validate percentage limits
        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Percentage discount cannot exceed 100%.');
        }

        $discount->update($data);

        return redirect()->route('tenant.discounts.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Discount updated successfully.');
    }

    /**
     * Remove the specified discount.
     */
    public function destroy($tenant, Discount $discount)
    {
        if ($discount->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $discount->delete();

        return redirect()->route('tenant.discounts.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Discount deleted successfully.');
    }

    /**
     * Get applicable discounts for an item/order type combination.
     */
    public function getApplicable(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $itemId = $request->get('item_id');
        $orderType = $request->get('order_type');
        $amount = $request->get('amount', 0);

        $discounts = Discount::where('tenant_id', $tenantId)
            ->available()
            ->get()
            ->filter(function ($discount) use ($itemId, $orderType, $amount) {
                return $discount->isApplicableToItem($itemId, $orderType) && 
                       (!$discount->minimum_amount || $amount >= $discount->minimum_amount);
            });

        return response()->json($discounts->values());
    }

    /**
     * Calculate discount for a given amount.
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'discount_code' => 'required|string',
            'item_id' => 'nullable|exists:items,id',
            'order_type' => 'nullable|string',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $discount = Discount::where('tenant_id', $tenantId)
            ->where('code', $request->discount_code)
            ->first();

        if (!$discount) {
            return response()->json(['error' => 'Invalid discount code'], 404);
        }

        $discountAmount = $discount->calculateDiscount(
            $request->amount,
            $request->item_id,
            $request->order_type
        );

        if ($discountAmount <= 0) {
            return response()->json([
                'error' => 'Discount not applicable to this order',
                'discount_amount' => 0,
                'discount' => $discount,
            ]);
        }

        return response()->json([
            'discount_amount' => $discountAmount,
            'discount' => $discount,
        ]);
    }

    /**
     * Toggle discount active status.
     */
    public function toggle($tenant, Discount $discount)
    {
        if ($discount->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        $discount->update(['is_active' => !$discount->is_active]);

        $status = $discount->is_active ? 'activated' : 'deactivated';
        return redirect()->route('tenant.discounts.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', "Discount {$status} successfully.");
    }
}