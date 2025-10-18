<?php

namespace App\Http\Controllers\Tenant\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Services\CatalogService;
use App\Imports\MenuImport;
use App\Exports\MenuTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function __construct(
        private CatalogService $catalogService
    ) {}

    public function index(Request $request)
    {
        $query = Item::where('tenant_id', app('tenant.id'))
            ->with(['category']);
            
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter out inactive items for POS display
        if ($request->has('pos') && $request->pos) {
            $query->where('is_active', true);
        }
        
        $items = $query->get();
        
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_veg' => 'boolean',
            'is_active' => 'boolean',
            'meta' => 'nullable|array',
        ]);
        
        $data['tenant_id'] = app('tenant.id');
        
        $item = $this->catalogService->createItem($data);
        
        return response()->json($item, 201);
    }

    public function show($tenant, Item $item)
    {
        $item->load(['category', 'variants', 'modifierGroups']);
        
        return response()->json($item);
    }

    public function update(Request $request, $tenant, Item $item)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_veg' => 'boolean',
            'is_active' => 'boolean',
            'meta' => 'nullable|array',
        ]);
        
        $item = $this->catalogService->updateItem($item, $data);
        
        return response()->json($item);
    }

    public function destroy($tenant, Item $item)
    {
        try {
            $this->catalogService->deleteItem($item);
            return response()->json(['message' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function setPrice(Request $request, Item $item)
    {
        $data = $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'price' => 'required|numeric|min:0',
            'variant_id' => 'nullable|exists:item_variants,id',
            'tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);
        
        $itemPrice = $this->catalogService->setItemPrice(
            $item,
            $data['outlet_id'],
            $data['price'],
            $data['variant_id'] ?? null,
            $data['tax_rate_id'] ?? null
        );
        
        return response()->json($itemPrice);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            $import = new MenuImport();
            $result = Excel::import($import, $request->file('file'));

            $importedCount = $import->getRowCount();
            $categoriesCreated = $import->getCategoriesCreated();
            $errors = $import->errors();
            $failures = $import->failures();

            if ($importedCount == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No menu items were imported',
                    'details' => [
                        'items_imported' => 0,
                        'categories_created' => 0,
                        'errors' => count($errors),
                        'failures' => count($failures)
                    ],
                    'errors' => $errors,
                    'failures' => $failures,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Import completed successfully!',
                'details' => [
                    'items_imported' => $importedCount,
                    'categories_created' => $categoriesCreated,
                    'errors' => count($errors),
                    'failures' => count($failures)
                ],
                'errors' => $errors,
                'failures' => $failures,
            ]);
        } catch (\Exception $e) {
            \Log::error('Menu import failed', [
                'tenant_id' => app('tenant.id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to import menu items: ' . $e->getMessage()
            ], 400);
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new MenuTemplateExport, 'menu_template.xlsx');
    }
}
