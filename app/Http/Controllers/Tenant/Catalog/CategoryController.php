<?php

namespace App\Http\Controllers\Tenant\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CatalogService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CatalogService $catalogService
    ) {}

    public function index()
    {
        $categories = Category::where('tenant_id', app('tenant.id'))
            ->orderBy('position')
            ->get();
            
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        \Log::info('=== CATEGORY CONTROLLER STORE DEBUG ===');
        \Log::info('CategoryController store method called', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'request_data' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Check tenant context availability
        \Log::info('Tenant context check', [
            'app_bound_tenant_id' => app()->bound('tenant.id'),
            'app_bound_tenant_model' => app()->bound('tenant.model'),
            'app_bound_tenant' => app()->bound('tenant'),
            'tenant_id_value' => app()->bound('tenant.id') ? app('tenant.id') : 'NOT_BOUND',
            'tenant_model_value' => app()->bound('tenant.model') ? app('tenant.model') : 'NOT_BOUND',
            'tenant_value' => app()->bound('tenant') ? app('tenant') : 'NOT_BOUND'
        ]);

        // Fallback: manually resolve tenant if not set
        if (!app()->bound('tenant.id')) {
            \Log::warning('Tenant context not bound, attempting manual resolution');
            $pathSegments = explode('/', trim($request->getPathInfo(), '/'));
            if (count($pathSegments) >= 1 && $pathSegments[0] !== '') {
                $tenantSlug = $pathSegments[0];
                $tenant = \App\Models\Account::where('slug', $tenantSlug)->first();
                if ($tenant) {
                    app()->instance('tenant.id', $tenant->id);
                    app()->instance('tenant.model', $tenant);
                    app()->instance('tenant', $tenant);
                    \Log::info('Tenant context manually set', ['tenant_id' => $tenant->id]);
                } else {
                    \Log::error('Tenant not found for manual resolution', ['tenant_slug' => $tenantSlug]);
                }
            }
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|integer|min:0',
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);

        $data['tenant_id'] = app('tenant.id');

        \Log::info('Category data before creation', [
            'validated_data' => $data,
            'tenant_id_source' => app('tenant.id'),
            'tenant_model_source' => app('tenant.model')
        ]);

        try {
            $category = $this->catalogService->createCategory($data);
            \Log::info('Category created successfully', ['category_id' => $category->id]);
            return response()->json($category, 201);
        } catch (\Exception $e) {
            \Log::error('Category creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function show($tenant, Category $category)
    {
        \Log::info('CategoryController show method called', [
            'tenant_param' => $tenant,
            'category_id' => $category->id
        ]);
        
        return response()->json($category);
    }

    public function update(Request $request, $tenant, Category $category)
    {
        \Log::info('CategoryController update method called', [
            'tenant_param' => $tenant,
            'category_id' => $category->id,
            'request_data' => $request->all()
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|integer|min:0',
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);
        
        $category = $this->catalogService->updateCategory($category, $data);
        
        return response()->json($category);
    }

    public function destroy($tenant, Category $category)
    {
        \Log::info('CategoryController destroy method called', [
            'tenant_param' => $tenant,
            'category_id' => $category->id,
            'category_name' => $category->name
        ]);

        try {
            $this->catalogService->deleteCategory($category);
            \Log::info('Category deleted successfully', ['category_id' => $category->id]);
            return response()->json(['message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Category deletion failed', [
                'error' => $e->getMessage(),
                'category_id' => $category->id
            ]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
