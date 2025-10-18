<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use App\Services\CatalogService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Validation\Rule;

class MenuImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $catalogService;
    protected $tenantId;
    protected $categories = [];
    protected $categoriesCreated = 0;
    protected $rowCount = 0;

    public function __construct()
    {
        $this->catalogService = app(CatalogService::class);
        $this->tenantId = app('tenant.id');
        
        // Load existing categories for this tenant
        $this->loadCategories();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['item_name']) || empty($row['price']) || empty($row['category'])) {
            return null;
        }

        // Get or create category
        $categoryId = $this->getOrCreateCategory($row['category']);

        // Parse boolean values
        $isVeg = $this->parseBoolean($row['is_vegetarian'] ?? 'true');
        $isActive = $this->parseBoolean($row['is_active'] ?? 'true');

        // Increment row count for successful imports
        $this->rowCount++;

        return new Item([
            'tenant_id' => $this->tenantId,
            'name' => $row['item_name'],
            'price' => (float) $row['price'],
            'category_id' => $categoryId,
            'sku' => $row['sku'] ?? null,
            'description' => $row['description'] ?? null,
            'is_veg' => $isVeg,
            'is_active' => $isActive,
        ]);
    }

    /**
     * Get validation rules
     */
    public function rules(): array
    {
        return [
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_vegetarian' => 'nullable|string|in:true,false,1,0,yes,no',
            'is_active' => 'nullable|string|in:true,false,1,0,yes,no',
        ];
    }

    /**
     * Load existing categories for this tenant
     */
    protected function loadCategories()
    {
        $this->categories = Category::where('tenant_id', $this->tenantId)
            ->pluck('id', 'name')
            ->toArray();
    }

    /**
     * Get or create category
     */
    protected function getOrCreateCategory($categoryName)
    {
        $categoryName = trim($categoryName);
        
        // Check if category already exists
        if (isset($this->categories[$categoryName])) {
            return $this->categories[$categoryName];
        }

        // Create new category
        try {
            $category = $this->catalogService->createCategory([
                'tenant_id' => $this->tenantId,
                'name' => $categoryName,
                'position' => null
            ]);

            // Add to local cache
            $this->categories[$categoryName] = $category->id;
            
            // Track categories created
            $this->categoriesCreated++;
            
            return $category->id;
        } catch (\Exception $e) {
            \Log::error('Failed to create category during import', [
                'category_name' => $categoryName,
                'tenant_id' => $this->tenantId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Parse boolean values from various formats
     */
    protected function parseBoolean($value)
    {
        if (is_null($value)) {
            return true; // Default to true
        }

        $value = strtolower(trim($value));
        
        return in_array($value, ['true', '1', 'yes', 'y']);
    }

    /**
     * Get the number of categories created during import
     */
    public function getCategoriesCreated()
    {
        return $this->categoriesCreated;
    }

    /**
     * Get the number of rows processed during import
     */
    public function getRowCount()
    {
        return $this->rowCount;
    }
}
