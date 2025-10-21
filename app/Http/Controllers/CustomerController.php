<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Search customers by name or phone
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:1|max:255',
        ]);

        $tenantId = app('tenant.id');
        $query = $request->input('q');

        $customers = Customer::where('tenant_id', $tenantId)
            ->search($query)
            ->limit(10)
            ->get(['id', 'name', 'phone', 'email', 'address']);

        return response()->json([
            'success' => true,
            'customers' => $customers,
        ]);
    }

    /**
     * Store a new customer
     */
    public function store(Request $request): JsonResponse
    {
        \Log::info('=== CUSTOMER STORE DEBUG START ===');
        \Log::info('Request data:', $request->all());
        \Log::info('Request headers:', $request->headers->all());
        
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:1000',
                'date_of_birth' => 'nullable|date',
            ]);
            \Log::info('✅ Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('❌ Validation failed:', $e->errors());
            throw $e;
        }

        $tenantId = app('tenant.id');
        \Log::info('Tenant ID:', ['tenant_id' => $tenantId]);

        // Check if customer with same phone already exists
        if ($request->phone) {
            $existingCustomer = Customer::where('tenant_id', $tenantId)
                ->where('phone', $request->phone)
                ->first();

            if ($existingCustomer) {
                \Log::info('❌ Customer with phone already exists:', ['customer_id' => $existingCustomer->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Customer with this phone number already exists',
                    'customer' => $existingCustomer,
                ], 409);
            }
        }

        $customerData = [
            'tenant_id' => $tenantId,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
        ];
        
        \Log::info('Creating customer with data:', $customerData);

        $customer = Customer::create($customerData);
        
        \Log::info('✅ Customer created successfully:', [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone
        ]);

        $response = [
            'success' => true,
            'customer' => $customer,
            'message' => 'Customer created successfully',
        ];
        
        \Log::info('Response data:', $response);
        \Log::info('=== CUSTOMER STORE DEBUG END ===');

        return response()->json($response);
    }

    /**
     * Find or create customer by phone
     */
    public function findOrCreate(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
        ]);

        $tenantId = app('tenant.id');

        $customer = Customer::findOrCreateByPhone($tenantId, $request->phone, [
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'message' => $customer->wasRecentlyCreated ? 'Customer created successfully' : 'Customer found',
        ]);
    }

    /**
     * Get customer details
     */
    public function show(Customer $customer): JsonResponse
    {
        $tenantId = app('tenant.id');

        // Ensure customer belongs to current tenant
        if ($customer->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'customer' => $customer->load('orders'),
        ]);
    }

    /**
     * Update customer
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'date_of_birth' => 'nullable|date',
        ]);

        $tenantId = app('tenant.id');

        // Ensure customer belongs to current tenant
        if ($customer->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        $customer->update($request->only(['name', 'phone', 'email', 'address', 'date_of_birth']));

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'message' => 'Customer updated successfully',
        ]);
    }

    /**
     * Get customer orders
     */
    public function orders(Customer $customer): JsonResponse
    {
        $tenantId = app('tenant.id');

        // Ensure customer belongs to current tenant
        if ($customer->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        $orders = $customer->orders()
            ->with(['items', 'table'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }
}
