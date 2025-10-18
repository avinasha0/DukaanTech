<?php

namespace App\Http\Controllers\Tenant\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Modifier;
use App\Models\ModifierGroup;
use App\Services\CatalogService;
use Illuminate\Http\Request;

class ModifierController extends Controller
{
    public function __construct(
        private CatalogService $catalogService
    ) {}

    public function index()
    {
        $modifierGroups = ModifierGroup::where('tenant_id', app('tenant.id'))
            ->with('modifiers')
            ->get();
            
        return response()->json($modifierGroups);
    }

    public function storeGroup(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_required' => 'boolean',
            'min' => 'integer|min:0',
            'max' => 'integer|min:1',
        ]);
        
        $data['tenant_id'] = app('tenant.id');
        
        $modifierGroup = $this->catalogService->createModifierGroup($data);
        
        return response()->json($modifierGroup, 201);
    }

    public function storeModifier(Request $request)
    {
        $data = $request->validate([
            'modifier_group_id' => 'required|exists:modifier_groups,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        
        $data['tenant_id'] = app('tenant.id');
        
        $modifier = $this->catalogService->createModifier($data);
        
        return response()->json($modifier, 201);
    }

    public function attachToItem(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'modifier_group_id' => 'required|exists:modifier_groups,id',
        ]);
        
        $item = \App\Models\Item::find($data['item_id']);
        $modifierGroup = ModifierGroup::find($data['modifier_group_id']);
        
        $this->catalogService->attachModifierGroupToItem($item, $modifierGroup);
        
        return response()->json(['message' => 'Modifier group attached to item']);
    }

    public function detachFromItem(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'modifier_group_id' => 'required|exists:modifier_groups,id',
        ]);
        
        $item = \App\Models\Item::find($data['item_id']);
        $modifierGroup = ModifierGroup::find($data['modifier_group_id']);
        
        $this->catalogService->detachModifierGroupFromItem($item, $modifierGroup);
        
        return response()->json(['message' => 'Modifier group detached from item']);
    }

    public function updateModifier(Request $request, Modifier $modifier)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        
        $modifier->update($data);
        
        return response()->json($modifier);
    }

    public function destroyModifier(Modifier $modifier)
    {
        $modifier->delete();
        
        return response()->json(['message' => 'Modifier deleted successfully']);
    }
}
