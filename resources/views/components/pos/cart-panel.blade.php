<div class="h-full flex flex-col">
    <div class="flex-1 overflow-y-auto">
        <div class="space-y-2">
            <template x-for="item in cart" :key="item.id">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-sm" x-text="item.name"></h4>
                        <p class="text-xs text-gray-500">₹<span x-text="item.price.toFixed(2)"></span> each</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button 
                            @click="updateQuantity(item.id, item.qty - 1)"
                            class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-300"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span class="w-8 text-center font-medium" x-text="item.qty"></span>
                        <button 
                            @click="updateQuantity(item.id, item.qty + 1)"
                            class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-300"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
            
            <div x-show="cart.length === 0" class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <p>Cart is empty</p>
            </div>
        </div>
    </div>
    
    <div class="border-t pt-4 mt-4">
        <div class="flex justify-between items-center mb-4">
            <span class="text-lg font-semibold">Total:</span>
            <span class="text-lg font-bold text-green-600">₹<span x-text="cartTotal.toFixed(2)"></span></span>
        </div>
        
        <button 
            @click="createOrder()"
            :disabled="cart.length === 0 || !shift"
            class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
        >
            <span x-show="!shift">Open Shift First</span>
            <span x-show="shift && cart.length === 0">Add Items</span>
            <span x-show="shift && cart.length > 0">Create Order</span>
        </button>
        
        <button 
            @click="openShift()"
            x-show="!shift"
            class="w-full bg-green-600 text-white py-2 rounded-lg font-medium mt-2"
        >
            Open Shift
        </button>
    </div>
</div>
