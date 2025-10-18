<div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg transform transition-transform duration-300 ease-in-out"
     :class="cart.length > 0 ? 'translate-y-0' : 'translate-y-full'"
     x-data="{ show: false }">
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Cart (<span x-text="cartItemCount"></span> items)</h3>
            <button @click="show = !show" class="text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        
        <div x-show="show" class="space-y-2 max-h-60 overflow-y-auto">
            <template x-for="item in cart" :key="item.id">
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                    <div class="flex-1">
                        <h4 class="font-medium text-sm" x-text="item.name"></h4>
                        <p class="text-xs text-gray-500">₹<span x-text="item.price.toFixed(2)"></span> × <span x-text="item.qty"></span></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button 
                            @click="updateQuantity(item.id, item.qty - 1)"
                            class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span class="w-8 text-center font-medium" x-text="item.qty"></span>
                        <button 
                            @click="updateQuantity(item.id, item.qty + 1)"
                            class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
        
        <div class="flex justify-between items-center mt-4 pt-4 border-t">
            <span class="text-lg font-semibold">Total: ₹<span x-text="cartTotal.toFixed(2)"></span></span>
            <button 
                @click="createOrder()"
                :disabled="!shift"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium disabled:bg-gray-300"
            >
                Order
            </button>
        </div>
    </div>
</div>
