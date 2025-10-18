<div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
     x-data="{ show: false, bill: null }"
     x-show="show"
     style="display: none;">
    <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Payment</h3>
                <button @click="show = false" class="text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div x-show="bill" class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹<span x-text="bill?.sub_total?.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Tax:</span>
                        <span>₹<span x-text="bill?.tax_total?.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total:</span>
                        <span>₹<span x-text="bill?.net_total?.toFixed(2)"></span></span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <button class="w-full bg-green-600 text-white py-3 rounded-lg font-medium">
                        Cash Payment
                    </button>
                    <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium">
                        Card Payment
                    </button>
                    <button class="w-full bg-purple-600 text-white py-3 rounded-lg font-medium">
                        UPI Payment
                    </button>
                    <button class="w-full bg-gray-600 text-white py-3 rounded-lg font-medium">
                        Other Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
