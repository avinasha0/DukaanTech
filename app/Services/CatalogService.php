<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\ModifierGroup;
use App\Models\Modifier;
use App\Models\TaxRate;

class CatalogService
{
    public function createCategory(array $data): Category
    {
        \Log::info('CatalogService createCategory called', [
            'data' => $data,
            'tenant_id_from_app' => app()->bound('tenant.id') ? app('tenant.id') : 'NOT_BOUND'
        ]);

        try {
            $category = Category::create($data);
            \Log::info('Category created in CatalogService', [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'category_tenant_id' => $category->tenant_id
            ]);
            return $category;
        } catch (\Exception $e) {
            \Log::error('Category creation failed in CatalogService', [
                'error' => $e->getMessage(),
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateCategory(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }

    public function deleteCategory(Category $category): bool
    {
        // Check if category has items
        if ($category->items()->count() > 0) {
            throw new \Exception('Cannot delete category with items');
        }
        
        return $category->delete();
    }

    public function createItem(array $data): Item
    {
        return Item::create($data);
    }

    public function updateItem(Item $item, array $data): Item
    {
        $item->update($data);
        return $item;
    }

    public function deleteItem(Item $item): bool
    {
        return $item->delete();
    }

    public function setItemPrice(Item $item, int $outletId, float $price, ?int $variantId = null, ?int $taxRateId = null): ItemPrice
    {
        return ItemPrice::updateOrCreate(
            [
                'tenant_id' => app('tenant.id'),
                'outlet_id' => $outletId,
                'item_id' => $item->id,
                'item_variant_id' => $variantId,
            ],
            [
                'tax_rate_id' => $taxRateId,
                'price' => $price,
                'is_available' => true,
            ]
        );
    }

    public function createModifierGroup(array $data): ModifierGroup
    {
        return ModifierGroup::create($data);
    }

    public function createModifier(array $data): Modifier
    {
        return Modifier::create($data);
    }

    public function attachModifierGroupToItem(Item $item, ModifierGroup $modifierGroup): void
    {
        $item->modifierGroups()->attach($modifierGroup->id, [
            'tenant_id' => app('tenant.id')
        ]);
    }

    public function detachModifierGroupFromItem(Item $item, ModifierGroup $modifierGroup): void
    {
        $item->modifierGroups()->detach($modifierGroup->id);
    }

    public function createTaxRate(array $data): TaxRate
    {
        return TaxRate::create($data);
    }

    public function getAvailableItems(int $outletId): \Illuminate\Database\Eloquent\Collection
    {
        return Item::where('tenant_id', app('tenant.id'))
            ->where('is_active', true)
            ->whereHas('prices', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId)
                    ->where('is_available', true);
            })
            ->with(['prices' => function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            }])
            ->get();
    }

    public function getItemsByCategory(int $categoryId, int $outletId): \Illuminate\Database\Eloquent\Collection
    {
        return Item::where('tenant_id', app('tenant.id'))
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->whereHas('prices', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId)
                    ->where('is_available', true);
            })
            ->with(['prices' => function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            }])
            ->get();
    }
}
