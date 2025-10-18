<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
    <template x-for="item in filteredItems" :key="item.id">
        <div class="bg-white rounded-lg shadow-sm border p-3 hover:shadow-md transition-shadow cursor-pointer"
             @click="addToCart(item)">
            <div class="aspect-square bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 class="font-medium text-sm text-gray-900 mb-1" x-text="item.name"></h3>
            <p class="text-xs text-gray-500 mb-2" x-text="item.sku || 'No SKU'"></p>
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-green-600">
                    ₹<span x-text="(item.prices?.[0]?.price || 0).toFixed(2)"></span>
                </span>
                <div class="flex items-center gap-1">
                    <span x-show="item.is_veg" class="text-green-500 text-xs">🟢</span>
                    <span x-show="!item.is_veg" class="text-red-500 text-xs">🔴</span>
                </div>
            </div>
        </div>
    </template>
    
    <div x-show="filteredItems.length === 0" class="col-span-full text-center py-8 text-gray-500">
        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
        <p>No items found</p>
    </div>
</div>
