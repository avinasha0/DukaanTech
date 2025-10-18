<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Terminal - DukaanTech</title>
    <script src="https://cdn.tailwindcss.com?v={{ time() }}"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js?v={{ time() }}&bust={{ rand(1000,9999) }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cache-buster" content="{{ time() }}">
    <style>
        [x-cloak] { display: none !important; }
        .modal-debug { 
            background-color: white !important;
            min-height: 200px;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .modal-backdrop {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 9999 !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
    </style>
</head>
<body class="bg-gray-100">
<div x-data="posRegister()" x-init="init()" class="min-h-screen bg-gray-100">
    <!-- Order Details Modal -->
    <div x-show="showOrderDetails" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showOrderDetails = false"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-white">Order Details</h3>
                                <p class="text-blue-100 text-sm">Order #<span x-text="selectedOrderDetails?.id"></span></p>
                            </div>
                        </div>
                        <button @click="showOrderDetails = false" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="bg-white px-6 py-4">
                    <!-- Order Status -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                  :class="selectedOrderDetails?.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                                <span class="w-2 h-2 rounded-full mr-2"
                                      :class="selectedOrderDetails?.status === 'active' ? 'bg-green-400' : 'bg-gray-400'"></span>
                                <span x-text="selectedOrderDetails?.status?.charAt(0).toUpperCase() + selectedOrderDetails?.status?.slice(1)"></span>
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Items Ordered</h4>
                        <div class="space-y-3">
                            <template x-for="item in selectedOrderDetails?.items || []" :key="item.id">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h5 class="font-medium text-gray-900" x-text="item.name"></h5>
                                        <p class="text-sm text-gray-500">Quantity: <span x-text="item.qty"></span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">₹<span x-text="(item.price * item.qty).toFixed(2)"></span></p>
                                        <p class="text-sm text-gray-500">₹<span x-text="item.price.toFixed(2)"></span> each</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-t pt-4">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900">₹<span x-text="selectedOrderDetails?.total?.toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-gray-900">₹<span x-text="selectedOrderDetails?.total?.toFixed(2)"></span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="mt-6 pt-4 border-t">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Table</span>
                                <p class="font-medium text-gray-900" x-text="selectedTable?.name || 'N/A'"></p>
                                <p class="text-xs text-gray-500" x-text="selectedTable?.status === 'occupied' ? 'Occupied (active order present)' : 'Available for orders'"></p>
                            </div>
                            <div>
                                <span class="text-gray-500">Created</span>
                                <p class="font-medium text-gray-900" x-text="selectedOrderDetails?.createdAt ? new Date(selectedOrderDetails.createdAt).toLocaleString() : 'N/A'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button @click="showOrderDetails = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Close
                    </button>
                    <button @click="printOrderBill(selectedOrderDetails)" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Print Bill
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Notification Toast --}}
    <div x-data x-show="$store.notifications.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-2" class="fixed top-4 right-4 z-50 max-w-sm w-full">
        <div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div x-show="$store.notifications.type === 'success'" class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div x-show="$store.notifications.type === 'warning'" class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900" x-text="$store.notifications.title"></h3>
                    <p class="mt-1 text-sm text-gray-600" x-text="$store.notifications.message"></p>
                    <div x-show="$store.notifications.details" class="mt-2 text-xs text-gray-500" x-text="$store.notifications.details"></div>
                </div>
                <button @click="$store.notifications.hide()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Top Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 px-3 sm:px-4 py-2 sm:py-3">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3 lg:gap-4">
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <!-- Mobile Menu Toggle Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg overflow-hidden flex items-center justify-center">
                        <img src="/favicon.png" alt="DukaanTech Favicon" class="w-full h-full object-cover">
                    </div>
                    <span class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 truncate">DukaanTech POS</span>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 lg:gap-4 w-full lg:w-auto">
                <!-- Outlet Selector -->
                <div class="flex items-center gap-2">
                    <label class="text-xs sm:text-sm text-gray-600 font-medium">Outlet:</label>
                    <template x-if="outlets.length === 0">
                        <div class="text-xs sm:text-sm text-gray-500 px-2 py-1">Loading...</div>
                    </template>
                    <template x-if="outlets.length === 1">
                        <div class="text-xs sm:text-sm text-gray-700 px-2 py-1 font-medium" x-text="outlets[0].name"></div>
                    </template>
                    <template x-if="outlets.length > 1">
                        <select x-model="outletId" @change="onOutletChange()" 
                                class="text-xs sm:text-sm border border-gray-300 rounded-md px-2 py-1 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-[120px]">
                            <template x-for="outlet in outlets" :key="outlet.id">
                                <option :value="outlet.id" x-text="outlet.name"></option>
                            </template>
                        </select>
                    </template>
                </div>
                
                <div class="text-xs sm:text-sm text-gray-600 flex items-center">
                    Shift: <span x-text="shift?.id ?? '—'" class="font-semibold ml-1"></span>
                    <span x-show="shift" class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Open</span>
                </div>
                
                @if($isTerminalAuth && $terminalUser)
                <div class="text-xs sm:text-sm text-gray-600 flex items-center">
                    User: <span class="font-semibold ml-1">{{ $terminalUser->name }}</span>
                    <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">{{ ucfirst($terminalUser->role) }}</span>
                </div>
                @endif
                
                <!-- View Orders Button -->
                <template x-if="shift">
                    <button @click="openOrdersModal()" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-3 py-2 text-white text-xs sm:text-sm font-semibold hover:bg-blue-700 transition-colors">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="hidden sm:inline">Recent Orders</span>
                        <span class="sm:hidden">Recent</span>
                    </button>
                </template>
                
                <!-- Product Visibility Button -->
                <template x-if="shift">
                    <button @click="openProductVisibilityModal()" class="inline-flex items-center justify-center rounded-md bg-purple-600 px-3 py-2 text-white text-xs sm:text-sm font-semibold hover:bg-purple-700 transition-colors">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="hidden sm:inline">Show/Hide Products</span>
                        <span class="sm:hidden">Products</span>
                    </button>
                </template>
                
                <template x-if="shift">
                    <button @click="$dispatch('open-checkout')" class="inline-flex items-center justify-center rounded-md bg-red-600 px-3 py-2 text-white text-xs sm:text-sm font-semibold hover:bg-red-700 transition-colors">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="hidden sm:inline">Checkout & Logout</span>
                        <span class="sm:hidden">Checkout</span>
                    </button>
                </template>
                
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="mobileMenuOpen = false" @keydown.escape.window="mobileMenuOpen = false"></div>
    
    <!-- Mobile Sidebar -->
    <div x-show="mobileMenuOpen" x-transition x-trap.noscroll="mobileMenuOpen" class="fixed inset-y-0 left-0 z-50 w-80 bg-white shadow-xl lg:hidden" style="top: 70px;" @keydown.escape="mobileMenuOpen = false">
        <div class="flex flex-col h-full">
            <!-- Mobile Menu Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Menu Categories</h3>
                <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Categories List -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="space-y-2">
                    
                    <template x-for="category in categories" :key="category.id">
                        <button 
                            @click="selectCategory(category)"
                            :class="selectedCategory?.id === category.id ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="w-full text-left px-4 py-3 rounded-lg font-medium transition-colors"
                        >
                            <span x-text="category.name"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>



    <!-- Main POS Layout -->
    <div class="flex flex-col lg:flex-row h-[calc(100vh-70px)] sm:h-[calc(100vh-80px)]">
        <!-- Mobile Cart Panel (Hidden on desktop) -->
        <div class="lg:hidden bg-white border-b border-gray-200 p-3 sm:p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Cart (<span x-text="cartItemCount"></span> items)</h3>
                <div class="text-base sm:text-lg font-bold text-orange-600" x-text="'₹' + cartTotal"></div>
            </div>
            
            <!-- Cart Items List -->
            <div class="mb-3 max-h-40 overflow-y-auto">
                <template x-if="cart.length > 0">
                    <div class="space-y-2">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-2 border border-gray-200">
                                <div class="flex-1 min-w-0 flex items-center gap-2">
                                    <div class="font-medium text-gray-900 truncate text-sm" x-text="item.name"></div>
                                    <div class="text-xs text-gray-500" x-text="'₹' + item.price"></div>
                                    <div class="text-xs text-gray-600 font-semibold" x-text="'×' + item.qty + ' = ₹' + (item.price * item.qty)"></div>
                                </div>
                                <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                                    <button @click="updateQuantity(item.id, item.qty - 1)" class="w-5 h-5 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 text-xs font-bold transition-colors">-</button>
                                    <span class="w-5 text-center font-bold text-xs bg-white rounded border min-w-[1.25rem]" x-text="item.qty"></span>
                                    <button @click="updateQuantity(item.id, item.qty + 1)" class="w-5 h-5 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 text-xs font-bold transition-colors">+</button>
                                    <button @click="removeFromCart(item.id)" class="w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center hover:bg-red-200 ml-1 text-xs font-bold transition-colors">×</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
                
                <template x-if="cart.length === 0">
                    <div class="text-center text-gray-500 py-4">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
                        </svg>
                        <p class="text-sm font-medium">No items in cart</p>
                        <p class="text-xs">Add items to get started</p>
                    </div>
                </template>
            </div>
            
            <!-- Payment Methods -->
            <div class="mb-3">
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <label class="flex items-center justify-center py-2 px-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" :class="{'bg-orange-100 border-orange-500': paymentMethod === 'cash'}">
                        <input type="radio" x-model="paymentMethod" value="cash" class="sr-only">
                        <span class="text-xs sm:text-sm font-medium">Cash</span>
                    </label>
                    <label class="flex items-center justify-center py-2 px-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" :class="{'bg-orange-100 border-orange-500': paymentMethod === 'card'}">
                        <input type="radio" x-model="paymentMethod" value="card" class="sr-only">
                        <span class="text-xs sm:text-sm font-medium">Card</span>
                    </label>
                    <label class="flex items-center justify-center py-2 px-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" :class="{'bg-orange-100 border-orange-500': paymentMethod === 'upi'}">
                        <input type="radio" x-model="paymentMethod" value="upi" class="sr-only">
                        <span class="text-xs sm:text-sm font-medium">UPI</span>
                    </label>
                </div>
                <!-- Payment Method Validation Error -->
                <div x-show="paymentMethod === '' && cart.length > 0" class="text-red-500 text-xs text-center mt-1">
                    Please select a payment method
                </div>
            </div>
            
            <div class="space-y-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <button @click="createOrder()" :disabled="cart.length === 0 || paymentMethod === ''" class="bg-red-500 text-white py-2.5 px-3 rounded-lg font-semibold hover:bg-red-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-xs sm:text-sm transition-colors">
                    Create Order
                </button>
                    <button @click="createOrder(true, true)" :disabled="cart.length === 0 || paymentMethod === ''" class="bg-green-500 text-white py-2.5 px-3 rounded-lg font-semibold hover:bg-green-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-xs sm:text-sm transition-colors">
                        Order with Print & KOT
                    </button>
                </div>
                <button @click="printCurrentOrder()" :disabled="cart.length === 0" class="w-full bg-gray-500 text-white py-2.5 px-3 rounded-lg font-semibold hover:bg-gray-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-xs sm:text-sm transition-colors">
                    Print Bill
                </button>
            </div>
        </div>

        <!-- Left Panel - Menu & Categories (Desktop Only) -->
        <div class="hidden lg:block w-64 bg-white border-r border-gray-200 overflow-y-auto">
            <div class="p-4">
                <!-- Menu Options -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Menu</h3>
                    <div class="space-y-2">
                        <button @click="toggleTables()" 
                                :class="showTables ? 'bg-gradient-to-r from-red-500 to-pink-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                class="w-full text-left px-4 py-3 rounded-lg font-medium transition-all duration-300 flex items-center gap-3 hover:scale-105">
          <div class="flex-shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <!-- Table Top -->
                  <ellipse cx="12" cy="10" rx="8" ry="5" fill="currentColor" fill-opacity="0.1"/>
                  <ellipse cx="12" cy="10" rx="8" ry="5"/>
                  
                  <!-- Table Legs -->
                  <line x1="6" y1="15" x2="6" y2="20"/>
                  <line x1="18" y1="15" x2="18" y2="20"/>
                  <line x1="8" y1="15" x2="8" y2="20"/>
                  <line x1="16" y1="15" x2="16" y2="20"/>
                  
                  <!-- Chairs around table -->
                  <circle cx="4" cy="8" r="1.5" fill="currentColor" fill-opacity="0.3"/>
                  <circle cx="20" cy="8" r="1.5" fill="currentColor" fill-opacity="0.3"/>
                  <circle cx="4" cy="12" r="1.5" fill="currentColor" fill-opacity="0.3"/>
                  <circle cx="20" cy="12" r="1.5" fill="currentColor" fill-opacity="0.3"/>
                  
                  <!-- Center decoration -->
                  <circle cx="12" cy="10" r="1" fill="currentColor"/>
                            </svg>
          </div>
                            <span class="font-semibold">Tables</span>
                        </button>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Categories</h3>
                <div class="space-y-2">
                    <!-- Show All Categories Option -->
                    <button 
                        @click="selectAllCategories()"
                        :class="showAllCategories ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="w-full text-left px-4 py-3 rounded-lg font-medium transition-colors"
                    >
                        <span class="truncate block">All Categories</span>
                    </button>
                    
                    <template x-for="category in categories" :key="category.id">
                        <button 
                            @click="selectCategory(category)"
                            :class="selectedCategory?.id === category.id ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="w-full text-left px-4 py-3 rounded-lg font-medium transition-colors"
                        >
                            <span x-text="category.name" class="truncate block"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Middle Panel - Products -->
        <div class="flex-1 bg-white overflow-y-auto">
            <div class="p-3 sm:p-4 lg:p-6">
                <!-- Tables View -->
                <div x-show="showTables" class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Restaurant Tables</h3>
                        <button @click="refreshTables()" class="px-3 py-1 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition-colors">
                            Refresh Tables
                        </button>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- All Tables with Dynamic Shapes -->
                        <template x-for="table in tables" :key="table.id">
                            <div class="bg-white border-2 p-4 shadow-sm transition-all duration-300 relative group hover:shadow-lg flex flex-col items-center justify-center"
                                 :class="[
                                     table.name === 'T1' ? 'w-32 h-32 rounded-full' : 
                                     table.name === 'T2' ? 'w-40 h-24 rounded-lg' : 
                                     table.name === 'T3' ? 'w-36 h-28 rounded-full' : 
                                     'w-32 h-32 rounded-lg',
                                     table.status === 'free' ? 'border-green-300 bg-gradient-to-br from-green-50 to-emerald-50 cursor-pointer hover:scale-105' : 'border-red-300 bg-gradient-to-br from-red-50 to-pink-50 cursor-not-allowed opacity-75'
                                 ]"
                                 @click="table.status === 'free' ? selectTable(table) : null"
                                 @mouseenter="showTooltip = table.id"
                                 @mouseleave="showTooltip = null">
                                
                                <!-- Table Icon -->
                                <div class="mb-2">
                                    <!-- Round Table Icon (T1) -->
                                    <svg x-show="table.name === 'T1'" class="w-8 h-8 mx-auto" :class="table.status === 'occupied' ? 'text-red-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <circle cx="12" cy="12" r="8" fill="currentColor" fill-opacity="0.1"/>
                                        <circle cx="12" cy="12" r="8"/>
                                        <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                        <circle cx="8" cy="8" r="1" fill="currentColor"/>
                                        <circle cx="16" cy="8" r="1" fill="currentColor"/>
                                        <circle cx="8" cy="16" r="1" fill="currentColor"/>
                                        <circle cx="16" cy="16" r="1" fill="currentColor"/>
                                    </svg>
                                    
                                    <!-- Rectangular Table Icon (T2) -->
                                    <svg x-show="table.name === 'T2'" class="w-6 h-6 mx-auto" :class="table.status === 'occupied' ? 'text-red-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <rect x="4" y="6" width="16" height="12" rx="2" fill="currentColor" fill-opacity="0.1"/>
                                        <rect x="4" y="6" width="16" height="12" rx="2"/>
                                        <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                                        <circle cx="7" cy="9" r="1" fill="currentColor"/>
                                        <circle cx="17" cy="9" r="1" fill="currentColor"/>
                                        <circle cx="7" cy="15" r="1" fill="currentColor"/>
                                        <circle cx="17" cy="15" r="1" fill="currentColor"/>
                                    </svg>
                                    
                                    <!-- Oval Table Icon (T3) -->
                                    <svg x-show="table.name === 'T3'" class="w-7 h-7 mx-auto" :class="table.status === 'occupied' ? 'text-red-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <ellipse cx="12" cy="12" rx="10" ry="6" fill="currentColor" fill-opacity="0.1"/>
                                        <ellipse cx="12" cy="12" rx="10" ry="6"/>
                                        <ellipse cx="12" cy="12" rx="3" ry="2" fill="currentColor"/>
                                        <circle cx="6" cy="8" r="1" fill="currentColor"/>
                                        <circle cx="18" cy="8" r="1" fill="currentColor"/>
                                        <circle cx="6" cy="16" r="1" fill="currentColor"/>
                                        <circle cx="18" cy="16" r="1" fill="currentColor"/>
                                    </svg>
                                    
                                    <!-- Default Square Table Icon (T4+) -->
                                    <svg x-show="table.name !== 'T1' && table.name !== 'T2' && table.name !== 'T3'" class="w-8 h-8 mx-auto" :class="table.status === 'occupied' ? 'text-red-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M4 8h16v8H4z" fill="currentColor" fill-opacity="0.1"/>
                                        <path d="M4 8h16v8H4z"/>
                                        <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                                        <circle cx="6" cy="6" r="1" fill="currentColor"/>
                                        <circle cx="18" cy="6" r="1" fill="currentColor"/>
                                        <circle cx="6" cy="18" r="1" fill="currentColor"/>
                                        <circle cx="18" cy="18" r="1" fill="currentColor"/>
                                    </svg>
                                </div>
                                
                                <!-- Table Name -->
                                <h4 class="text-sm font-semibold text-gray-900 mb-1" x-text="table.name"></h4>
                                
                                <!-- Status Indicator -->
                                <div class="flex items-center justify-center gap-1 mb-1">
                                    <div class="w-2 h-2 rounded-full animate-pulse" 
                                         :class="table.status === 'occupied' ? 'bg-red-500' : 'bg-green-500'"></div>
                                    <span class="text-xs font-medium"
                                          :class="table.status === 'occupied' ? 'text-red-600' : 'text-green-600'"
                                          x-text="table.status === 'occupied' ? 'Occupied' : 'Available'"></span>
                                </div>
                                
                                <!-- Capacity Info -->
                                <div x-show="table.capacity" class="text-xs text-gray-500 mb-1">
                                        <span x-text="table.capacity + ' seats'"></span>
                                </div>
                                
                                <!-- Total Amount -->
                                <div x-show="table.status === 'occupied' && table.totalAmount > 0" class="text-xs font-semibold text-gray-900 mb-1">
                                    <span x-text="'₹' + (table.totalAmount || 0).toFixed(2)"></span>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="mt-1">
                                    <button x-show="table.status === 'free'" 
                                            class="w-full px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-lg transition-all duration-300 flex items-center justify-center gap-1 hover:shadow-lg hover:scale-105">
                                        <span>Available</span>
                                    </button>
                    <button x-show="table.status === 'occupied'" @click.stop="markTableAsPaidFromCard(table)" 
                            class="w-full px-2 py-1 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white text-xs font-medium rounded-lg transition-all duration-300 flex items-center justify-center gap-1 hover:shadow-lg hover:scale-105">
                        <span>Mark Paid</span>
                    </button>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Empty State -->
                        <div x-show="tables.length === 0" class="col-span-2 lg:col-span-4 text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No tables found</h3>
                            <p class="text-gray-600 mb-4">Create tables in the dashboard to see them here</p>
                            <a href="/dukaantech/tables" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Manage Tables
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div x-show="!showTables" class="mb-4 sm:mb-6">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        placeholder="Search items..." 
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm sm:text-base"
                    >
                </div>

                <!-- Products Grid -->
                <div x-show="!showTables" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3 lg:gap-4">
                    <template x-for="item in filteredItems" :key="item.id">
                        <button 
                            @click="addToCart(item)"
                            class="bg-white border border-green-200 rounded-lg p-2.5 sm:p-3 lg:p-4 hover:border-green-400 hover:shadow-md transition-all text-left min-h-[80px] sm:min-h-[90px] flex flex-col justify-between"
                        >
                            <div class="font-medium text-gray-900 mb-1 text-xs sm:text-sm lg:text-base line-clamp-2" x-text="item.name"></div>
                            <div class="text-sm sm:text-base lg:text-lg font-bold text-green-600" x-text="'₹' + (item.price || 0)"></div>
                        </button>
                    </template>
                </div>

                <!-- No Shift Warning -->
            </div>
        </div>

        <!-- Right Panel - Cart & Order (Hidden on mobile) -->
        <div class="hidden lg:flex w-[420px] bg-white border-l border-gray-200 flex-col h-full min-h-0">
            <!-- Cart Items - Main Focus -->
            <div class="flex-1 overflow-y-auto p-3 min-h-0 max-h-[60vh]">
                <!-- Order Type Tabs -->
                <div class="mb-3 sticky top-0 bg-white pb-2">
                    <div class="flex space-x-1 bg-gray-100 rounded-lg p-1">
                        <button @click="handleTabChange('dine-in')" 
                                :class="selectedOrderType === 'dine-in' ? 'bg-red-600 text-white shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900'"
                                class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors">
                            Dine In
                        </button>
                        <button @click="handleTabChange('delivery')" 
                                :class="selectedOrderType === 'delivery' ? 'bg-red-600 text-white shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900'"
                                class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors">
                            Delivery
                        </button>
                        <button @click="handleTabChange('pick-up')" 
                                :class="selectedOrderType === 'pick-up' ? 'bg-red-600 text-white shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900'"
                                class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors">
                            Pickup
                        </button>
                    </div>
                    
                    <!-- Dine In Sub-tabs -->
                    <div x-show="selectedOrderType === 'dine-in'" class="mt-3">
                        <div class="flex space-x-1 bg-gray-50 rounded-lg p-1">
                            <button @click="selectedDineInTab = 'table'" 
                                    :class="selectedDineInTab === 'table' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                                    class="flex-1 py-2 px-3 text-xs font-medium rounded-md transition-colors flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Table
                            </button>
                            <button @click="selectedDineInTab = 'customer'" 
                                    :class="selectedDineInTab === 'customer' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                                    class="flex-1 py-2 px-3 text-xs font-medium rounded-md transition-colors flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Customer
                            </button>
                            <button @click="selectedDineInTab = 'count'" 
                                    :class="selectedDineInTab === 'count' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                                    class="flex-1 py-2 px-3 text-xs font-medium rounded-md transition-colors flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Count
                            </button>
                        </div>
                        
                        <!-- Customer Form -->
                        <div x-show="selectedDineInTab === 'customer'" class="mt-3 bg-white rounded-lg p-3 border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Customer Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Customer Name</label>
                                    <input type="text" x-model="customerInfo.customerName" placeholder="Enter customer name" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" x-model="customerInfo.customerPhone" placeholder="Enter phone number" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                                    <textarea x-model="customerInfo.address" placeholder="Enter customer address" rows="2" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Table Form -->
                        <div x-show="selectedDineInTab === 'table'" class="mt-3 bg-white rounded-lg p-3 border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Table Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Select Table</label>
                                    <select x-model="customerInfo.tableNo" @change="handleTableSelection()" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="">Choose a table...</option>
                                        <template x-for="table in tables" :key="table.id">
                                            <option :value="table.name" 
                                                    :disabled="table.status === 'occupied'"
                                                    :class="table.status === 'occupied' ? 'text-gray-400' : ''">
                                                <span x-text="table.name"></span>
                                                <span x-text="table.status === 'occupied' ? ' (Occupied)' : (table.status === 'free' ? ' (Available)' : ' (Reserved)')"></span>
                                            </option>
                                        </template>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Select from available tables</p>
                                </div>
                                
                                <!-- Selected Table Info -->
                                <div x-show="selectedTable" class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h5 class="text-sm font-medium text-blue-900" x-text="selectedTable?.name"></h5>
                                            <p class="text-xs text-blue-700" x-text="selectedTable?.status === 'occupied' ? 'Currently occupied' : 'Available for orders'"></p>
                                        </div>
                                        <div class="w-3 h-3 rounded-full" 
                                             :class="selectedTable?.status === 'occupied' ? 'bg-red-500' : 'bg-green-500'"></div>
                                    </div>
                                    <div x-show="selectedTable?.totalAmount > 0" class="mt-2">
                                        <p class="text-xs text-blue-600">Current Total: ₹<span x-text="selectedTable?.totalAmount?.toFixed(2)"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Count Form -->
                        <div x-show="selectedDineInTab === 'count'" class="mt-3 bg-white rounded-lg p-3 border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Number of Customers</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. of Customers</label>
                                    <div class="flex items-center gap-3">
                                        <button @click="customerCount = Math.max(1, customerCount - 1)" 
                                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number" x-model="customerCount" min="1" max="20" 
                                               class="w-16 px-3 py-2 border border-gray-300 rounded-md text-sm text-center focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <button @click="customerCount = Math.min(20, customerCount + 1)" 
                                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Range: 1-20 customers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-1 pb-4">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-center justify-between bg-white rounded-lg p-2 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex-1 min-w-0 flex items-center gap-2">
                                <div class="font-medium text-gray-900 truncate text-sm" x-text="item.name"></div>
                                <div class="text-xs text-gray-500" x-text="'₹' + item.price"></div>
                                <div class="text-xs text-gray-600 font-semibold" x-text="'×' + item.qty + ' = ₹' + (item.price * item.qty)"></div>
                            </div>
                            <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                                <button @click="updateQuantity(item.id, item.qty - 1)" class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 text-xs font-bold transition-colors">-</button>
                                <span class="w-6 text-center font-bold text-xs bg-white rounded border min-w-[1.5rem]" x-text="item.qty"></span>
                                <button @click="updateQuantity(item.id, item.qty + 1)" class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 text-xs font-bold transition-colors">+</button>
                                <button @click="removeFromCart(item.id)" class="w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center hover:bg-red-200 ml-1 text-xs font-bold transition-colors">×</button>
                        </div>
                    </div>
                </template>
                </div>

                <template x-if="cart.length === 0">
                    <div class="text-center text-gray-500 py-8 lg:py-12">
                        <svg class="w-16 h-16 lg:w-20 lg:h-20 mx-auto mb-3 lg:mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
                        </svg>
                        <p class="text-base lg:text-lg font-medium">No items in cart</p>
                        <p class="text-xs lg:text-sm">Add items to get started</p>
                            </div>
                        </template>
                    </div>


            <!-- Order Summary & Payment -->
            <div class="border-t border-gray-200 p-2 bg-gray-50 flex-shrink-0">


                <!-- Discount Section -->
                <div class="mb-2">
                    <div class="flex gap-1 mb-1">
                        <input type="text" x-model="discountCode" placeholder="Discount Code" 
                               class="flex-1 px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
                        <button @click="applyDiscount()" :disabled="!discountCode" 
                                class="px-2 py-1.5 bg-green-600 text-white rounded text-xs font-medium hover:bg-green-700 disabled:bg-gray-300 transition-colors">
                            Apply
                        </button>
                </div>
                    <div x-show="appliedDiscount" class="text-xs text-green-600 mb-1 bg-green-50 p-1.5 rounded">
                        <span x-text="appliedDiscount?.name || 'Discount'"></span> - ₹<span x-text="discountAmount"></span> off
                        <button @click="removeDiscount()" class="ml-2 text-red-600 hover:text-red-800 font-bold">×</button>
                            </div>
                            </div>

                <!-- Order Total -->
                <div class="bg-white rounded-lg p-2 mb-2 border border-gray-200">
                    <div x-show="appliedDiscount" class="flex justify-between items-center mb-1">
                        <span class="text-xs text-green-600">Discount</span>
                        <span class="text-xs text-green-600 font-bold">-₹<span x-text="discountAmount"></span></span>
                </div>

                    <div class="flex justify-between items-center border-t border-gray-200 pt-1">
                        <span class="text-base lg:text-lg font-bold text-gray-900">Total</span>
                        <span class="text-lg lg:text-xl font-bold text-orange-600" x-text="'₹' + finalTotal"></span>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="mb-2">
                    <div class="grid grid-cols-3 gap-1 mb-1">
                        <label class="flex items-center flex-1 justify-center py-2 px-2 border border-gray-300 rounded text-xs font-medium cursor-pointer hover:bg-gray-50 transition-colors" :class="{'bg-orange-100 border-orange-500': paymentMethod === 'cash'}">
                            <input type="radio" x-model="paymentMethod" value="cash" class="sr-only">
                            <span>Cash</span>
                        </label>
                        <label class="flex items-center flex-1 justify-center py-2 px-2 border border-gray-300 rounded text-xs font-medium cursor-pointer hover:bg-gray-50 transition-colors" :class="{'bg-orange-100 border-orange-500': paymentMethod === 'card'}">
                            <input type="radio" x-model="paymentMethod" value="card" class="sr-only">
                            <span>Card</span>
                        </label>
                        <label class="flex items-center flex-1 justify-center py-2 px-2 border border-gray-300 rounded text-xs font-medium cursor-pointer hover:bg-gray-50 transition-colors" :class="{'bg-orange-100 border-orange-500': paymentMethod === 'upi'}">
                            <input type="radio" x-model="paymentMethod" value="upi" class="sr-only">
                            <span>UPI</span>
                        </label>
                    </div>
                    <div x-show="paymentMethod === '' && cart.length > 0" class="text-red-500 text-xs text-center">
                        Please select a payment method
                    </div>
                </div>

                <!-- Order Creation Buttons -->
                <div class="mt-2">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-1">
                        <button @click="createOrder(false, false)" 
                                :disabled="cart.length === 0"
                                :class="cart.length === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                                class="text-white font-medium py-2 px-2 rounded text-xs transition-colors">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="hidden lg:inline">Order</span>
                            <span class="lg:hidden">Order</span>
                    </button>
                        <button @click="createOrder(true, false)" 
                                :disabled="cart.length === 0"
                                :class="cart.length === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                                class="text-white font-medium py-2 px-2 rounded text-xs transition-colors">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            <span class="hidden lg:inline">With Print</span>
                            <span class="lg:hidden">Print</span>
                        </button>
                        <button @click="createOrder(false, true)" 
                                :disabled="cart.length === 0"
                                :class="cart.length === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-orange-600 hover:bg-orange-700'"
                                class="text-white font-medium py-2 px-2 rounded text-xs transition-colors">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span class="hidden lg:inline">With KOT</span>
                            <span class="lg:hidden">KOT</span>
                        </button>
                        <button @click="createOrder(true, true)" 
                                :disabled="cart.length === 0"
                                :class="cart.length === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-700'"
                                class="text-white font-medium py-2 px-2 rounded text-xs transition-colors">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="hidden lg:inline">With Both</span>
                            <span class="lg:hidden">Both</span>
                    </button>
                </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Payment Success Modal -->
    <div x-show="showPaymentSuccessModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showPaymentSuccessModal = false"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <!-- Success Icon and Content -->
                <div class="bg-white px-8 py-12 text-center">
                    <!-- Success Icon -->
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                        <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    
                    <!-- Success Message -->
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h3>
                    <p class="text-lg text-gray-600 mb-6">
                        Bill for <span class="font-semibold text-green-600" x-text="paymentSuccessTableName"></span> has been marked as paid.
                    </p>
                    
                    <!-- Table Status Update -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-green-800 font-medium">Table is now available for new orders</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button @click="showPaymentSuccessModal = false" class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                            Continue
                        </button>
                        <button @click="showPaymentSuccessModal = false; showTables = true" class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            View Tables
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="pos-script-{{ time() }}">
// ========================================
// CACHE BUSTER v2.3 - AGGRESSIVE FORCE RELOAD
// Fixed: currentSelectedTableId, $forceUpdate, Alpine null errors
// ========================================
console.log('🚀 POS Script v2.3 loaded - All errors fixed!');
console.log('Cache buster timestamp:', new Date().toISOString());
console.log('Script ID:', 'pos-script-{{ time() }}');
console.log('FORCE RELOAD: If you see v2.2, the cache is still active!');

// Force immediate cache invalidation
if (typeof window !== 'undefined') {
    window.posScriptVersion = 'v2.3';
    console.log('Window version set:', window.posScriptVersion);
}
// Alpine store for notifications
document.addEventListener('alpine:init', () => {
    Alpine.store('notifications', {
        show: false,
        type: 'success',
        title: '',
        message: '',
        details: '',
        
        showNotification(type, title, message, details = '') {
            this.type = type;
            this.title = title;
            this.message = message;
            this.details = details;
            this.show = true;
            
            // Auto-hide after 20 seconds
            setTimeout(() => {
                this.hide();
            }, 20000);
        },
        
        hide() {
            this.show = false;
        }
    });
});

function notificationToast() {
    return {
        show: false,
        type: 'success',
        title: '',
        message: '',
        details: '',
        
        showNotification(type, title, message, details = '') {
            this.type = type;
            this.title = title;
            this.message = message;
            this.details = details;
            this.show = true;
            
            // Auto-hide after 20 seconds
            setTimeout(() => {
                this.hide();
            }, 20000);
        },
        
        hide() {
            this.show = false;
        }
    }
}

function posRegister() {
    return {
        // State
        apiBase: '',
        outletId: {{ $outletId ?? 1 }},
        outlets: [],
        devices: [],
        deviceId: null,
        deviceKey: null,
        kotRefreshInterval: null,
        categories: [],
        items: [],
        orderTypes: [],
        selectedOrderType: null,
        selectedDineInTab: 'table',
        customerCount: 1,
        showTables: true,
        tables: [],
        selectedTable: null,
        selectedTableOrders: [],
        tableOrders: {},
        showTooltip: null,
        customerInfo: {
            orderType: 'dine-in',
            tableNo: '',
            customerName: '',
            customerPhone: '',
            address: '',
            deliveryAddress: '',
            deliveryFee: 0,
            specialInstructions: ''
        },
        paymentMethod: '',
        cart: [],
        showPaymentSuccessModal: false,
        paymentSuccessTableName: '',
        currentOrder: null,
        shift: @json($activeShift ?? null),
        terminalUser: @json($terminalUser ?? null),
        isTerminalAuth: {{ $isTerminalAuth ? 'true' : 'false' }},
        openingFloat: 0,
        searchQuery: '',
        selectedCategory: null,
        showShiftCheckoutModal: false,
        shiftSummary: null,
        actualCash: 0,
        checkoutLoading: false,
        
        // Discount functionality
        discountCode: '',
        appliedDiscount: null,
        discountAmount: 0,
        
        // UI state
        showCustomerDetails: false,
        mobileMenuOpen: false,
        showOrderDetails: false,
        selectedOrderDetails: null,
        showAllCategories: false,
        
        // Computed
        get filteredItems() {
            let filtered = this.items;
            
            // Filter by active status first (only show active items in POS)
            filtered = filtered.filter(item => item.is_active);
            console.log('Main POS filteredItems - after active filter:', filtered.length, 'items');
            
            if (this.selectedCategory) {
                filtered = filtered.filter(item => item.category_id === this.selectedCategory.id);
                console.log('Main POS filteredItems - after category filter:', filtered.length, 'items');
            }
            
            if (this.searchQuery) {
                filtered = filtered.filter(item => 
                    item.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
                console.log('Main POS filteredItems - after search filter:', filtered.length, 'items');
            }
            
            console.log('Main POS filteredItems - final result:', filtered.length, 'items');
            return filtered;
        },
        
        displayOrderTypeName(orderType) {
            if (!orderType) return '';
            if (orderType.slug === 'pick-up') return 'Take Away';
            return orderType.name;
        },
        
        get cartTotal() {
            return Math.round(this.cart.reduce((total, item) => {
                return total + (item.qty * item.price);
            }, 0) * 100) / 100;
        },
        
        get cartItemCount() {
            return this.cart.reduce((count, item) => count + item.qty, 0);
        },
        
        get finalTotal() {
            return Math.round((this.cartTotal - this.discountAmount) * 100) / 100;
        },
        
        // Methods
        async init() {
            // Build API base depending on path-based vs subdomain routing
            const host = window.location.host; // e.g., localhost:8000 or dukaantech.localhost:8000
            const path = window.location.pathname; // e.g., /dukaantech/pos/register or /pos/register
            const segments = path.replace(/^\/+|\/+$/g, '').split('/');
            const isPathBased = host.indexOf('.') === -1 || segments[0] !== 'pos';
            const tenantSlug = (isPathBased && segments.length > 0) ? segments[0] : null;
            this.apiBase = isPathBased && tenantSlug ? `/${tenantSlug}/pos/api` : `/pos/api`;


            // Alternative approach: Use server-side data as primary, localStorage as fallback
            this.initializeShiftState();
            console.log('POS initialized with shift state:', this.shift ? 'OPEN' : 'CLOSED');
            console.log('POS locked state:', this.posLocked);

            // Load outlets first to set correct outletId before loading tables
            await this.loadOutlets();
            
            // Load tables after outlet is selected
            this.refreshTablesFromStorage(); // Load tables from database
            
            // Load other data in parallel
            this.loadCategories();
            this.loadItems();
            this.loadOrderTypes();
            this.loadDevices();
            
            // Enable periodic refresh immediately to keep tables in sync
            this.enablePeriodicRefresh();
            
            // Make debug functions globally accessible
            this.globalDebugTables();
            
            // Ensure only Tables is selected by default, not any category
            this.selectedCategory = null;
            this.showAllCategories = false;
            
            // Listen for refresh events from product visibility modal
            window.addEventListener('refresh-pos-items', () => {
                console.log('=== MAIN POS REFRESH EVENT RECEIVED ===');
                console.log('Received refresh event, reloading items...');
                console.log('Current items count before refresh:', this.items.length);
                this.loadItems();
                console.log('=== END MAIN POS REFRESH EVENT ===');
            });
            
            // If no server-side shift data, try to restore from localStorage
            if (!this.shift) {
                this.restoreShiftFromStorage();
            }
            
            if (this.shift) {
                this.loadShiftSummary();
            }
            this.ensureCustomerListener();
            
            // Listen for shift-closed event from modal
            window.addEventListener('shift-closed', () => {
                console.log('Shift closed event received, updating shift status...');
                this.shift = null; // Clear shift
                this.shiftSummary = null; // Clear shift summary
                this.lockPOS(); // Lock the POS system
                this.clearShiftFromStorage(); // Clear localStorage
            });
        },
        async loadDevices() {
            try {
                const resp = await fetch(`${this.apiBase}/devices?outlet_id=${this.outletId}`);
                if (resp.ok) {
                    this.devices = await resp.json();
                    if (this.devices.length) {
                        this.deviceId = this.devices[0].id;
                        this.deviceKey = this.devices[0].api_key || null;
                    }
                }
            } catch (e) {
                console.warn('Unable to load devices', e);
            }
        },
        
        async loadOutlets() {
            try {
                console.log('Loading outlets...');
                const resp = await fetch(`${this.apiBase}/outlets`);
                if (resp.ok) {
                    this.outlets = await resp.json();
                    console.log('Loaded outlets:', this.outlets);
                    
                    // Set default outlet if none selected and outlets are available
                    if (this.outlets.length > 0) {
                        // If only 1 outlet exists, always select it
                        if (this.outlets.length === 1) {
                            this.outletId = this.outlets[0].id;
                            console.log('Auto-selected single outlet:', this.outlets[0].name, '(ID:', this.outletId, ')');
                            
                            // Reload data for the selected outlet
                            this.refreshTablesFromStorage();
                            this.loadDevices();
                        } else if (!this.outletId) {
                            // Multiple outlets - select first one if none selected
                            this.outletId = this.outlets[0].id;
                            console.log('Set default outlet:', this.outlets[0].name, '(ID:', this.outletId, ')');
                        }
                    }
                } else {
                    console.warn('Failed to load outlets:', resp.status);
                }
            } catch (e) {
                console.warn('Unable to load outlets', e);
            }
        },
        
        async onOutletChange() {
            console.log('Outlet changed to:', this.outletId);
            
            // Clear current data
            this.tables = [];
            this.selectedTable = null;
            this.cart = [];
            this.cartTotal = 0;
            
            // Reload data for new outlet
            this.refreshTablesFromStorage();
            this.loadDevices();
            
            // Save outlet selection to localStorage
            this.saveShiftToStorage();
        },
        
        initializeShiftState() {
            // Use server-side data if available, otherwise check localStorage
            if (this.shift) {
                this.saveShiftToStorage();
            } else {
                // Try to load shift from localStorage (from terminal login)
                this.loadShiftFromStorage();
            }
        },
        
        saveShiftToStorage() {
            if (this.shift) {
                localStorage.setItem('pos_shift_data', JSON.stringify({
                    shift: this.shift,
                    timestamp: Date.now(),
                    outletId: this.outletId
                }));
            }
        },
        
        loadShiftFromStorage() {
            try {
                const storedData = localStorage.getItem('pos_shift_data');
                if (storedData) {
                    const data = JSON.parse(storedData);
                    // Check if data is not too old (within 1 hour)
                    if (data.timestamp && (Date.now() - data.timestamp) < 3600000) {
                        this.shift = data.shift;
                        if (data.outletId) {
                            this.outletId = data.outletId;
                        }
                        console.log('✅ Loaded shift from localStorage:', this.shift);
                        return true;
                    } else {
                        // Data is too old, clear it
                        localStorage.removeItem('pos_shift_data');
                    }
                }
            } catch (e) {
                console.warn('Error loading shift from localStorage:', e);
                localStorage.removeItem('pos_shift_data');
            }
            return false;
        },
        
        restoreShiftFromStorage() {
            try {
                const stored = localStorage.getItem('pos_shift_data');
                if (stored) {
                    const data = JSON.parse(stored);
                    // Check if data is not too old (within 24 hours)
                    if (Date.now() - data.timestamp < 24 * 60 * 60 * 1000) {
                        this.shift = data.shift;
                        this.posLocked = false;
                        console.log('Restored shift from localStorage');
                    } else {
                        this.clearShiftFromStorage();
                    }
                }
            } catch (error) {
                console.error('Error restoring shift from storage:', error);
                this.clearShiftFromStorage();
            }
        },
        
        clearShiftFromStorage() {
            localStorage.removeItem('pos_shift_data');
            this.shiftSummary = null; // Clear shift summary
        },
        
        // Notification methods
        showNotification(type, title, message, details = '') {
            if (window.Alpine && Alpine.store('notifications')) {
                Alpine.store('notifications').showNotification(type, title, message, details);
            } else {
                // Fallback to alert if notification system not available
                alert(`${title}\n${message}`);
            }
        },
        
        async loadCategories() {
            try {
                const response = await fetch(`${this.apiBase}/categories`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                this.categories = await response.json();
                console.log('Categories loaded:', this.categories);
                // Don't auto-select first category - let user choose
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        },
        
        async loadItems() {
            console.log('=== MAIN POS LOAD ITEMS DEBUG ===');
            console.log('Loading items from:', `${this.apiBase}/items?pos=true`);
            
            try {
                const response = await fetch(`${this.apiBase}/items?pos=true`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                console.log('Main POS API response status:', response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Main POS loaded items:', data.length, 'items');
                    console.log('Items data:', data);
                    console.log('Active items:', data.filter(item => item.is_active).length);
                    console.log('Inactive items:', data.filter(item => !item.is_active).length);
                    
                    // Force Alpine to detect the change by creating a new array
                    this.items = [...data];
                    console.log('Main POS items array updated with new reference');
                    
                    // Force Alpine to re-render by triggering a reactive update
                    this.$nextTick(() => {
                        console.log('Alpine nextTick completed, items should be re-rendered');
                    });
                    
                    // Alternative: Force update the filteredItems computed property
                    setTimeout(() => {
                        console.log('Forcing reactive update after timeout');
                        this.items = [...this.items];
                    }, 100);
                } else {
                    console.error('Main POS API error:', response.status, response.statusText);
                }
            } catch (error) {
                console.error('Error loading items in main POS:', error);
            }
            
            console.log('=== END MAIN POS LOAD ITEMS DEBUG ===');
        },
        
        async loadOrderTypes() {
            try {
                const response = await fetch(`${this.apiBase}/order-types`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                this.orderTypes = await response.json();
                // Select Dine In by default
                const dineInType = this.orderTypes.find(type => type.slug === 'dine-in');
                if (dineInType) {
                    this.selectedOrderType = 'dine-in';
                    this.customerInfo.orderType = 'dine-in';
                } else if (this.orderTypes.length > 0) {
                    // Fallback to first order type if Dine In not found
                    this.selectedOrderType = this.orderTypes[0].slug;
                    this.customerInfo.orderType = this.orderTypes[0].slug;
                }
            } catch (error) {
                console.error('Error loading order types:', error);
            }
        },
        
        openKotTerminal() {
            // Get the tenant slug from the URL
            const pathSegments = window.location.pathname.split('/');
            const tenantSlug = pathSegments[1]; // Assumes /{tenant}/pos/register format
            
            // Open KOT terminal in a new window
            const kotUrl = `/${tenantSlug}/kot`;
            window.open(kotUrl, 'KOT Terminal', 'width=1200,height=800,menubar=no,toolbar=no,location=no,status=no');
        },
        
        async checkShift() {
            try {
                console.log('=== CHECKING SHIFT ===');
                console.log('API Base:', this.apiBase);
                console.log('Outlet ID:', this.outletId);
                console.log('URL:', `${this.apiBase}/shifts/current?outlet_id=${this.outletId}`);
                
                const response = await fetch(`${this.apiBase}/shifts/current?outlet_id=${this.outletId}`);
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Shift data received:', data);
                    this.shift = data.shift;
                    this.posLocked = !data.has_shift; // Lock based on has_shift flag
                    
                    if (data.has_shift) {
                        console.log('✅ Active shift found');
                        this.loadShiftSummary();
                        this.saveShiftToStorage(); // Save to localStorage
                    } else {
                        console.log('❌ No active shift found');
                        this.clearShiftFromStorage(); // Clear localStorage
                    }
                } else {
                    console.log('❌ Error fetching shift');
                }
            } catch (error) {
                console.error('❌ Error checking shift:', error);
            }
        },
        
        
        async loadShiftSummary() {
            if (!this.shift) return;
            
            try {
                const response = await fetch(`${this.apiBase}/dashboard/shift/current?t=${Date.now()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.shiftSummary = data.summary;
                    console.log('Shift summary loaded during initialization:', this.shiftSummary);
                }
            } catch (error) {
                console.error('Error loading shift summary:', error);
            }
        },
        
        selectCategory(category) {
            console.log('=== SELECT CATEGORY DEBUG ===');
            console.log('Category clicked:', category);
            console.log('showTables before:', this.showTables);
            console.log('selectedCategory before:', this.selectedCategory);
            console.log('showAllCategories before:', this.showAllCategories);
            
            this.selectedCategory = category;
            this.showTables = false; // Ensure tables is deselected
            this.showAllCategories = false; // Ensure all categories is deselected
            
            console.log('showTables after:', this.showTables);
            console.log('selectedCategory after:', this.selectedCategory);
            console.log('showAllCategories after:', this.showAllCategories);
            console.log('=== END SELECT CATEGORY DEBUG ===');
            
            // Close mobile menu after selection
            this.mobileMenuOpen = false;
        },

        toggleTables() {
            console.log('=== TOGGLE TABLES DEBUG ===');
            console.log('showTables before:', this.showTables);
            console.log('selectedCategory before:', this.selectedCategory);
            console.log('showAllCategories before:', this.showAllCategories);
            
            // If tables is currently shown, hide it but don't select anything else
            if (this.showTables) {
                this.showTables = false;
                this.selectedCategory = null;
                this.showAllCategories = false;
            } else {
                // If tables is hidden, show it and deselect categories
                this.showTables = true;
                this.selectedCategory = null;
                this.showAllCategories = false;
            }
            
            console.log('showTables after:', this.showTables);
            console.log('selectedCategory after:', this.selectedCategory);
            console.log('showAllCategories after:', this.showAllCategories);
            console.log('=== END TOGGLE TABLES DEBUG ===');
        },

        selectAllCategories() {
            console.log('=== SELECT ALL CATEGORIES DEBUG ===');
            console.log('showTables before:', this.showTables);
            console.log('selectedCategory before:', this.selectedCategory);
            console.log('showAllCategories before:', this.showAllCategories);
            
            this.selectedCategory = null;
            this.showTables = false;
            this.showAllCategories = true;
            
            console.log('showTables after:', this.showTables);
            console.log('selectedCategory after:', this.selectedCategory);
            console.log('showAllCategories after:', this.showAllCategories);
            console.log('=== END SELECT ALL CATEGORIES DEBUG ===');
        },
        
        selectOrderType(orderType) {
            this.selectedOrderType = orderType;
            // Clear customer info when switching order types
            this.customerInfo = {
                orderType: orderType,
                tableNo: '',
                customerName: '',
                customerPhone: '',
                address: '',
                deliveryAddress: '',
                deliveryFee: 0,
                specialInstructions: ''
            };
        },
        
        handleOrderTypeChange() {
            // If delivery is selected, automatically open customer details modal
            if (this.customerInfo.orderType === 'delivery') {
                // Pre-fill the modal with current customer info
                const prefillData = {
                    customerName: this.customerInfo.customerName,
                    customerPhone: this.customerInfo.customerPhone,
                    address: this.customerInfo.address,
                    deliveryAddress: this.customerInfo.deliveryAddress,
                    deliveryFee: this.customerInfo.deliveryFee
                };
                
                // Open the customer modal
                window.dispatchEvent(new CustomEvent('open-customer-modal', {
                    detail: { prefill: prefillData }
                }));
            }
        },

        handleTabChange(orderType) {
            this.selectedOrderType = orderType;
            this.customerInfo.orderType = orderType;
            // Reset dine-in sub-tab when switching order types
            if (orderType === 'dine-in') {
                this.selectedDineInTab = 'table';
            }
            this.handleOrderTypeChange();
        },

        async handleTableSelection() {
            try {
                console.log('=== HANDLE TABLE SELECTION START ===');
                console.log('Selected table number:', this.customerInfo.tableNo);
                console.log('Available tables:', this.tables);
                
                if (this.customerInfo.tableNo) {
                    // Find the table by name
                    const table = this.tables.find(t => t.name === this.customerInfo.tableNo);
                    console.log('Found table:', table);
                    
                    if (table) {
                        // Set the selected table
                        this.selectedTable = table;
                        console.log('Table set as selected:', this.selectedTable);
                        console.log('Selected table ID:', this.selectedTable.id);
                        console.log('Selected table status:', this.selectedTable.status);
                        
                        // Load table orders (but don't change status yet)
                        await this.loadTableOrders(table.id);
                    } else {
                        console.error('Table not found with name:', this.customerInfo.tableNo);
                        this.selectedTable = null;
                    }
                } else {
                    console.log('No table selected, clearing selectedTable');
                    this.selectedTable = null;
                }
                
                console.log('=== HANDLE TABLE SELECTION END ===');
            } catch (error) {
                console.error('ERROR in handleTableSelection:', error);
                console.error('Error stack:', error.stack);
            }
        },


        async toggleTableStatus(table) {
            const newStatus = table.status === 'occupied' ? 'free' : 'occupied';
            let newTotalAmount = table.totalAmount;
            let newOrders = table.orders;
            
            if (newStatus === 'free') {
                newTotalAmount = 0;
                newOrders = [];
            } else {
                // Recalculate total when table is occupied again
                newTotalAmount = table.orders ? table.orders.reduce((sum, order) => sum + (order.total || 0), 0) : 0;
            }
            
            // Update in database
            const updatedTable = await this.updateTableStatusInDatabase(table.id, newStatus, newTotalAmount, newOrders);
            
            if (updatedTable) {
                // Update local state with database response
                Object.assign(table, updatedTable);
                console.log('Table status updated successfully:', table);
                
                // Sync selected table if it's the same table
                this.syncSelectedTable();
            } else {
                console.error('Failed to update table status, reverting changes');
                // Revert the change if database update failed
                table.status = table.status === 'occupied' ? 'free' : 'occupied';
                
                // Sync selected table if it's the same table
                this.syncSelectedTable();
            }
        },

        deleteTable(tableId) {
            this.tables = this.tables.filter(table => table.id !== tableId);
        },

        async loadTablesFromStorage() {
            try {
                console.log('=== LOAD TABLES FROM SERVER START ===');
                
                const response = await fetch(`${this.apiBase}/tables?outlet_id=${this.outletId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    this.tables = data.tables;
                    console.log('✅ Tables loaded from server:', this.tables.length);
                    console.log('Tables data:', this.tables);
                    
                    // Sync selected table with updated data
                    this.syncSelectedTable();
                } else {
                    throw new Error(data.error || 'Failed to load tables');
                }
                
                console.log('=== LOAD TABLES FROM SERVER END ===');
            } catch (error) {
                console.error('❌ Error loading tables from server:', error);
                this.tables = [];
            }
        },

        clearAndResetTables() {
            console.log('=== CLEARING TABLE STORAGE ===');
            // Refresh tables from server
            this.refreshTablesFromDatabase();
            console.log('Tables refreshed from server.');
        },

        // Server-driven order management
        async placeOrder(tableId) {
            try {
                console.log('🚀 Placing order for table:', tableId);
                
                const response = await fetch(`${this.apiBase}/orders/place`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ table_id: tableId })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    console.log('✅ Order placed successfully:', data);
                    // Refresh tables to show updated status
                    await this.refreshTablesFromDatabase();
                    return data;
                } else {
                    throw new Error(data.error || 'Failed to place order');
                }
                
            } catch (error) {
                console.error('❌ Failed to place order:', error);
                throw error;
            }
        },

        async closeOrder(orderId, status = 'CLOSED') {
            try {
                console.log('🚀 CLOSING ORDER START');
                console.log('Order ID:', orderId);
                console.log('Status:', status);
                console.log('API Base:', this.apiBase);
                console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
                
                const requestBody = { order_id: orderId, status: status };
                console.log('Request body:', requestBody);
                
                const response = await fetch(`${this.apiBase}/orders/close`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(requestBody)
                });
                
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                console.log('Response headers:', Object.fromEntries(response.headers.entries()));
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('❌ HTTP Error Response:', errorText);
                    throw new Error(`HTTP ${response.status}: ${response.statusText} - ${errorText}`);
                }
                
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success) {
                    console.log('✅ Order closed successfully via API');
                    console.log('✅ Close result:', data);
                    
                    return data;
                } else {
                    console.error('❌ API returned success=false:', data);
                    throw new Error(data.error || data.message || 'Failed to close order - API returned success=false');
                }
                
            } catch (error) {
                console.error('❌ ERROR in closeOrder:', error);
                console.error('❌ Error message:', error.message);
                console.error('❌ Error stack:', error.stack);
                console.error('❌ Order ID that failed:', orderId);
                throw error;
            }
        },

        async updateTableStatusInDatabase(tableId, status, totalAmount = null, orders = null) {
            try {
                console.log('=== UPDATE TABLE STATUS IN DATABASE START ===');
                console.log('Table ID:', tableId);
                console.log('Status:', status);
                console.log('Total Amount:', totalAmount);
                console.log('Orders:', orders);
                
                const updateData = { status };
                if (totalAmount !== null) updateData.total_amount = totalAmount;
                if (orders !== null) updateData.orders = orders;

                console.log('Update data:', updateData);
                console.log('API URL:', `${this.apiBase}/tables/${tableId}/status`);

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('CSRF Token:', csrfToken);
                
                const response = await fetch(`${this.apiBase}/tables/${tableId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(updateData)
                });

                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);

                if (response.ok) {
                    const updatedTable = await response.json();
                    console.log('Table status updated in database:', updatedTable);
                    console.log('=== UPDATE TABLE STATUS IN DATABASE SUCCESS ===');
                    return updatedTable;
                } else {
                    const errorText = await response.text();
                    console.error('Failed to update table status in database');
                    console.error('Response status:', response.status);
                    console.error('Response text:', errorText);
                    console.log('=== UPDATE TABLE STATUS IN DATABASE FAILED ===');
                    return null;
                }
            } catch (error) {
                console.error('Error updating table status in database:', error);
                console.log('=== UPDATE TABLE STATUS IN DATABASE ERROR ===');
                return null;
            }
        },

        async refreshTablesFromDatabase(forceRefresh = false) {
            try {
                if (forceRefresh) {
                    console.log('=== FORCE REFRESH TABLES ===');
                } else {
                    console.log('=== PERIODIC REFRESH START ===');
                }
                
                // Check if we have recent local updates (within last 60 seconds) - but allow force refresh
                const now = Date.now();
                const hasRecentUpdates = !forceRefresh && this.lastTableUpdate && (now - this.lastTableUpdate) < 60000; // 60 seconds
                
                if (hasRecentUpdates) {
                    console.log('Skipping refresh - recent local updates detected (within 30 seconds)');
                    return;
                }
                
                // Store current selected table ID before updating
                const currentSelectedTableId = this.selectedTable ? this.selectedTable.id : null;
                const currentSelectedTableStatus = this.selectedTable ? this.selectedTable.status : null;
                
                const response = await fetch(`${this.apiBase}/tables?outlet_id=${this.outletId}`);
                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success) {
                        const serverTables = data.tables;
                        if (forceRefresh) {
                            console.log('✅ Tables loaded:', serverTables.length, 'tables');
                        }
                    
                    // Use server data as source of truth - no local overrides
                    console.log('Using server data as source of truth for table status');
                    
                        // Update local tables with server data (potentially merged with local changes)
                        this.tables = serverTables;
                        
                        // Force reactivity update
                        this.tables = [...this.tables];
                    } else {
                        console.error('API returned success=false:', data);
                        throw new Error(data.error || 'Failed to load tables from server');
                    }
                    
                    // Update selectedTable reference to point to the updated table
                    if (currentSelectedTableId) {
                        const updatedSelectedTable = this.tables.find(t => t.id === currentSelectedTableId);
                        if (updatedSelectedTable) {
                            // Only update if there are actual changes
                            const hasChanges = !this.selectedTable || 
                                this.selectedTable.status !== updatedSelectedTable.status ||
                                this.selectedTable.name !== updatedSelectedTable.name ||
                                this.selectedTable.totalAmount !== updatedSelectedTable.totalAmount;
                            
                            if (hasChanges) {
                                this.selectedTable = updatedSelectedTable;
                                console.log('Selected table synchronized with database (changes detected):', this.selectedTable);
                                
                                // Check if status changed
                                if (currentSelectedTableStatus && currentSelectedTableStatus !== updatedSelectedTable.status) {
                                    console.warn(`Selected table status changed from ${currentSelectedTableStatus} to ${updatedSelectedTable.status}`);
                                }
                            }
                        }
                    }
                    
                        if (forceRefresh) {
                            console.log('✅ Tables refresh complete');
                        }
                    } else {
                        console.error('Failed to refresh tables from database');
                    }
            } catch (error) {
                console.error('Error refreshing tables from database:', error);
            }
        },

        debugTables() {
            console.log('=== TABLE DEBUG ===');
            console.log('Current this.tables array:', this.tables);
            console.log('Tables length:', this.tables.length);
            console.log('Selected table:', this.selectedTable);
            console.log('Tables from database (refreshed every 5 seconds)');
            alert(`Current tables: ${this.tables.length}\nSelected table: ${this.selectedTable ? this.selectedTable.name + ' (' + this.selectedTable.status + ')' : 'None'}\nTable data is now synchronized via database API`);
        },

        syncSelectedTable() {
            // Ensure selectedTable is always in sync with the tables array
            if (this.selectedTable) {
                const updatedTable = this.tables.find(t => t.id === this.selectedTable.id);
                if (updatedTable) {
                    console.log('🔄 Syncing selected table with updated data');
                    console.log('Old selectedTable status:', this.selectedTable.status);
                    console.log('New table status:', updatedTable.status);
                    
                    
                    this.selectedTable = updatedTable;
                    console.log('✅ Selected table synchronized:', this.selectedTable);
                    
                    // Also update customerInfo.tableNo to match the current selection
                    if (this.customerInfo.tableNo === updatedTable.name) {
                        console.log('✅ Customer info table number already matches');
                    } else {
                        console.log('⚠️ Customer info table number mismatch, updating...');
                        this.customerInfo.tableNo = updatedTable.name;
                    }
                } else {
                    console.warn('⚠️ Selected table not found in updated tables array, clearing selection');
                    this.selectedTable = null;
                    this.customerInfo.tableNo = '';
                }
            }
        },

        async selectTable(table) {
            try {
                console.log('=== TABLE SELECTION START ===');
                console.log('Table clicked:', table);
                console.log('Table ID:', table.id);
                console.log('Table Name:', table.name);
                console.log('Table Status:', table.status);
                console.log('Current tables array:', this.tables);
                console.log('Current selectedTable before:', this.selectedTable);
                
                // Get fresh table status from database
                const tableStatus = await this.getTableStatus(table.id);
                console.log('Fresh table status from database:', tableStatus);
                
                // Update the table in our local array with fresh data
                const tableIndex = this.tables.findIndex(t => t.id === table.id);
                if (tableIndex !== -1) {
                    this.tables[tableIndex] = { ...this.tables[tableIndex], ...tableStatus };
                    table = this.tables[tableIndex]; // Use updated table data
                }
                
                this.selectedTable = table;
                this.customerInfo.tableNo = table.name;
                
                console.log('Table set as selected:', this.selectedTable);
                console.log('Customer info updated:', this.customerInfo);
                console.log('Selected Order Type:', this.selectedOrderType);
                
                // Load table orders if table has active order
                if (table.has_active_order && table.active_order) {
                    console.log('Loading table orders...');
                    await this.loadTableOrders(table.id);
                } else {
                    console.log('No active order for this table');
                    this.tableOrders = [];
                }
                
                console.log('=== TABLE SELECTION END ===');
            } catch (error) {
                console.error('ERROR in selectTable:', error);
                console.error('Error stack:', error.stack);
                console.error('Table clicked:', table);
                console.error('Current tables:', this.tables);
            }
        },

        updateTableStatus(tableId, status) {
            try {
                console.log('=== UPDATE TABLE STATUS START ===');
                console.log('Table ID:', tableId);
                console.log('New status:', status);
                console.log('Tables array:', this.tables);
                
                const table = this.tables.find(t => t.id === tableId);
                console.log('Found table:', table);
                
                if (table) {
                    console.log('Table status before:', table.status);
                    table.status = status;
                    console.log('Table status after:', table.status);
                    
                    // Table status is now managed by database API
                    console.log('Table status updated via database API');
                } else {
                    console.error('Table not found with ID:', tableId);
                }
                
                console.log('=== UPDATE TABLE STATUS END ===');
            } catch (error) {
                console.error('Error in updateTableStatus:', error);
                console.error('Error stack:', error.stack);
            }
        },

        async updateTableOrder(tableId, orderTotal) {
            try {
                console.log('=== UPDATE TABLE ORDER START ===');
                console.log('Table ID:', tableId);
                console.log('Order Total:', orderTotal);
                console.log('Current Order:', this.currentOrder);
                console.log('Cart Length:', this.cart.length);
                console.log('Tables Array:', this.tables);
                
            const table = this.tables.find(t => t.id === tableId);
                console.log('Found Table:', table);
                
            if (table) {
                    console.log('Table Status Before:', table.status);
                
                // Prepare order data
                let newOrders = table.orders || [];
                    if (this.currentOrder) {
                        console.log('Adding order to table...');
                    const orderData = {
                        id: this.currentOrder.id || Date.now(),
                        total: orderTotal,
                        status: 'active',
                            items: this.cart.length > 0 ? this.cart.map(item => ({
                            id: item.id,
                            name: item.name,
                            qty: item.qty,
                            price: item.price
                            })) : [],
                        createdAt: new Date().toISOString()
                    };
                    
                    newOrders.push(orderData);
                        console.log('Order Data to Add:', orderData);
                    console.log('Order added to table. Orders count:', newOrders.length);
                }
                    
                    // Calculate total of all orders for this table
                const newTotalAmount = newOrders.reduce((sum, order) => sum + (order.total || 0), 0);
                console.log('Table total updated to:', newTotalAmount);
                
                // Always update local state first to ensure immediate UI update
                table.status = 'occupied';
                table.totalAmount = newTotalAmount;
                table.orders = newOrders;
                this.lastTableUpdate = Date.now();
                this.tables = [...this.tables];
                this.syncSelectedTable();
                console.log('Table updated locally immediately - status:', table.status);
                
                // Set a flag to prevent any refresh from overriding this change
                table._localUpdate = true;
                table._localUpdateTime = Date.now();
                
                // Then try to update in database (but don't let it override local changes)
                try {
                    const updatedTable = await this.updateTableStatusInDatabase(
                        tableId, 
                        'occupied', 
                        newTotalAmount, 
                        newOrders
                    );
                    
                    if (updatedTable) {
                        console.log('Table order updated successfully in database');
                        // Only update non-critical fields from server response
                        if (updatedTable.id) table.id = updatedTable.id;
                        if (updatedTable.name) table.name = updatedTable.name;
                        // Keep our local status and orders
                        console.log('Local changes preserved, server data merged');
                    } else {
                        console.error('Failed to update table order in database, keeping local changes');
                    }
                } catch (error) {
                    console.error('Database update failed, keeping local changes:', error);
                }
                
                // Force final reactivity update
                this.tables = [...this.tables];
                this.syncSelectedTable();
                } else {
                    console.error('ERROR: Table not found with ID:', tableId);
                }
                
                console.log('=== UPDATE TABLE ORDER END ===');
            } catch (error) {
                console.error('ERROR in updateTableOrder:', error);
                console.error('Error stack:', error.stack);
                console.error('Table ID:', tableId);
                console.error('Order Total:', orderTotal);
                console.error('Current Order:', this.currentOrder);
            }
        },

        async loadTableOrders(tableId) {
            try {
                console.log('=== LOAD TABLE ORDERS START ===');
                console.log('Table ID:', tableId);
                console.log('API Base:', this.apiBase);
                console.log('API URL:', `${this.apiBase}/tables/orders?table_id=${tableId}`);
                    
                // Always clear orders first
                this.selectedTableOrders = [];
                console.log('Cleared selectedTableOrders array');
                    
                // Fetch orders from API
                const response = await fetch(`${this.apiBase}/tables/orders?table_id=${tableId}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    console.error('API Error:', response.status, response.statusText);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('Raw API Response:', JSON.stringify(data, null, 2));
                
                if (data.success) {
                    this.selectedTableOrders = data.orders || [];
                    console.log('✅ Orders loaded successfully:', this.selectedTableOrders.length, 'OPEN orders');
                    console.log('Orders data:', JSON.stringify(this.selectedTableOrders, null, 2));
                    
                    if (this.selectedTableOrders.length === 0) {
                        console.log('ℹ️ No OPEN orders found for this table - table is free');
                    }
                        
                        // Force Alpine.js reactivity update
                        this.$nextTick(() => {
                            console.log('Alpine.js nextTick - selectedTableOrders should be reactive now:', this.selectedTableOrders);
                        });
                    } else {
                    console.warn('⚠️ API returned success=false:', data.error);
                    this.selectedTableOrders = [];
                }
                
                console.log('Final selectedTableOrders:', this.selectedTableOrders);
                console.log('=== LOAD TABLE ORDERS END ===');
            } catch (error) {
                console.error('❌ Error in loadTableOrders:', error);
                console.error('Error stack:', error.stack);
                this.selectedTableOrders = [];
            }
        },

        viewOrderDetails(order) {
            // Show order details in a modal or alert
            const itemsList = order.items ? order.items.map(item => 
                `${item.name} x${item.qty} - ₹${(item.price * item.qty).toFixed(2)}`
            ).join('\n') : 'No items';
            
            alert(`Order #${order.id}\n\nItems:\n${itemsList}\n\nTotal: ₹${order.total || 0}\nStatus: ${order.status || 'Active'}`);
        },

        addTestOrder() {
            try {
                console.log('=== ADD TEST ORDER START ===');
                console.log('Selected table:', this.selectedTable);
                console.log('Current tables:', this.tables);
                
                if (this.selectedTable) {
                    const testOrder = {
                        id: Date.now(),
                        total: 150.00,
                        status: 'active',
                        items: [
                            { id: 1, name: 'Test Item 1', qty: 2, price: 50.00 },
                            { id: 2, name: 'Test Item 2', qty: 1, price: 50.00 }
                        ],
                        createdAt: new Date().toISOString()
                    };
                    
                    console.log('Test order created:', testOrder);
                    
                    // Add to table's orders
                    const table = this.tables.find(t => t.id === this.selectedTable.id);
                    console.log('Found table for test order:', table);
                    
                    if (table) {
                        if (!table.orders) {
                            table.orders = [];
                            console.log('Initialized empty orders array for table');
                        }
                        
                        console.log('Table orders before adding:', table.orders);
                        table.orders.push(testOrder);
                        console.log('Table orders after adding:', table.orders);
                        
                        table.totalAmount = testOrder.total;
                        table.status = 'occupied';
                        
                        console.log('Table updated - totalAmount:', table.totalAmount, 'status:', table.status);
                        
                        // Table data is now managed by database API
                        console.log('Table data updated via database API');
                        
                        // Reload orders for current table
                        console.log('Reloading orders for table...');
                        this.loadTableOrders(this.selectedTable.id);
                        
                        console.log('Test order added successfully');
                    } else {
                        console.error('Table not found for test order');
                    }
                } else {
                    console.error('No table selected for test order');
                }
                
                console.log('=== ADD TEST ORDER END ===');
            } catch (error) {
                console.error('Error in addTestOrder:', error);
                console.error('Error stack:', error.stack);
            }
        },

        clearTableOrders() {
            try {
                console.log('=== CLEAR TABLE ORDERS START ===');
                console.log('Clearing orders for table:', this.selectedTable.name);
                
                if (this.selectedTable) {
                    const table = this.tables.find(t => t.id === this.selectedTable.id);
                    if (table) {
                        console.log('Table orders before clear:', table.orders);
                        table.orders = [];
                        table.totalAmount = 0;
                        table.status = 'free';
                        
                        console.log('Table orders after clear:', table.orders);
                        console.log('Table status set to:', table.status);
                        
                        // Table data is now managed by database API
                        console.log('Table data updated via database API');
                        
                        // Reload orders for current table
                        this.loadTableOrders(this.selectedTable.id);
                        
                        console.log('Table orders cleared successfully');
                    }
                }
                
                console.log('=== CLEAR TABLE ORDERS END ===');
            } catch (error) {
                console.error('Error in clearTableOrders:', error);
                console.error('Error stack:', error.stack);
            }
        },

        async markTableAsPaidFromCard(table) {
            try {
                console.log('=== MARK TABLE AS PAID FROM CARD START ===');
                console.log('Table ID:', table.id);
                console.log('Table Name:', table.name);
                console.log('Table Status:', table.status);
                console.log('Table Is Occupied:', table.is_occupied);
                console.log('Table Orders:', table.orders);
                console.log('Current Order ID:', table.current_order_id);
                
                // Check if table has an active order locally first
                if (!table.current_order_id) {
                    console.warn('⚠️ Table has no active order, cannot mark as paid');
                    alert('No active order found for this table. Please create an order first.');
                    return;
                }
                
                console.log('🚀 Attempting to close order:', table.current_order_id);
                
                // Close the order using the server API with CLOSED status
                const closeResult = await this.closeOrder(table.current_order_id, 'CLOSED');
                
                if (closeResult && closeResult.success) {
                    console.log('✅ Order closed successfully via API');
                    console.log('✅ Close result:', closeResult);
                    
                    // Update table status locally immediately for better UX
                    const tableIndex = this.tables.findIndex(t => t.id === table.id);
                    if (tableIndex !== -1) {
                        this.tables[tableIndex].status = 'free';
                        this.tables[tableIndex].current_order_id = null;
                        this.tables[tableIndex].total_amount = 0;
                        console.log('✅ Table status updated locally');
                    }
                    
                    // Clear selected table if it's the same
                    if (this.selectedTable && this.selectedTable.id === table.id) {
                        this.selectedTable = null;
                        this.selectedTableOrders = [];
                        this.customerInfo.tableNo = '';
                        console.log('✅ Selected table cleared and customer info reset');
                    }
                
                    // Show success message immediately
                    console.log('✅ Table marked as paid successfully');
                    this.displayPaymentSuccessModal(table.name);
                    
                    // Refresh tables in background (non-blocking)
                    setTimeout(() => {
                        this.refreshTablesFromStorage();
                    }, 100);
                    
                } else {
                    console.error('❌ Failed to close order via API:', closeResult);
                    console.error('❌ Close result details:', {
                        success: closeResult?.success,
                        error: closeResult?.error,
                        message: closeResult?.message
                    });
                    alert('Failed to mark table as paid. Please try again.');
                }
                
                console.log('=== MARK TABLE AS PAID FROM CARD END ===');
                
            } catch (error) {
                console.error('❌ ERROR in markTableAsPaidFromCard:', error);
                console.error('❌ Error message:', error.message);
                console.error('❌ Error stack:', error.stack);
                console.error('❌ Table data at error:', {
                    id: table.id,
                    name: table.name,
                    status: table.status,
                    current_order_id: table.current_order_id,
                    orders: table.orders
                });
                
                alert('Error marking table as paid: ' + error.message);
            }
        },

        displayPaymentSuccessModal(tableName) {
            this.paymentSuccessTableName = tableName;
            this.showPaymentSuccessModal = true;
        },

        async refreshTablesFromStorage() {
            try {
                console.log('=== REFRESH TABLES FROM DATABASE ===');
                
                const response = await fetch(`${this.apiBase}/tables?outlet_id=${this.outletId}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('Tables API response:', data);
                
                if (data.success) {
                    console.log('Tables refreshed from database:', data.tables);
                    console.log('Table statuses:', data.tables.map(t => ({id: t.id, name: t.name, status: t.status, current_order_id: t.current_order_id})));
                    
                    this.tables = data.tables;
                    
                    // Sync selected table with updated data
                    this.syncSelectedTable();
                    
                    // Note: Orders are only loaded when user explicitly selects a table
                    // or clicks refresh - not automatically on every table refresh
                    
                    console.log('=== REFRESH COMPLETE ===');
                } else {
                    throw new Error(data.error || 'Failed to refresh tables');
                }
            } catch (error) {
                console.error('❌ Error refreshing tables from database:', error);
            }
        },

        async getTableStatus(tableId) {
            try {
                console.log('=== GET TABLE STATUS ===');
                console.log('Table ID:', tableId);
                
                const response = await fetch(`${this.apiBase}/tables/status?table_id=${tableId}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('Table status response:', data);
                
                if (data.success) {
                    return data.table;
                } else {
                    throw new Error(data.error || 'Failed to get table status');
                }
            } catch (error) {
                console.error('❌ Error getting table status:', error);
                throw error;
            }
        },

        async refreshTableOrders() {
            try {
                console.log('=== REFRESH TABLE ORDERS ===');
                
                if (this.selectedTable) {
                    console.log('Refreshing orders for table:', this.selectedTable.id);
                    await this.loadTableOrders(this.selectedTable.id);
                    console.log('✅ Table orders refreshed successfully');
                } else {
                    console.warn('⚠️ No selected table to refresh orders for');
                }
            } catch (error) {
                console.error('❌ Error refreshing table orders:', error);
            }
        },

        enablePeriodicRefresh() {
            if (!this.refreshInterval) {
                this.refreshInterval = setInterval(() => {
                    // Only refresh if we have a selected table or if it's been a while since last refresh
                    const timeSinceLastRefresh = this.lastTableUpdate ? Date.now() - this.lastTableUpdate : 0;
                    const shouldRefresh = this.selectedTable || timeSinceLastRefresh > 30000; // 30 seconds
                    
                    if (shouldRefresh) {
                        this.refreshTablesFromDatabase();
                    } else {
                        console.log('Skipping periodic refresh - no selected table and recent refresh');
                    }
                }, 10000); // Increased to 10 seconds
                console.log('Periodic refresh enabled (10 second interval)');
            }
        },

        // Simple function to clear orders - just clear the display
        clearOrders() {
            console.log('=== CLEARING ORDERS DISPLAY ===');
            console.log('Before clear - selectedTableOrders:', this.selectedTableOrders);
            
            this.selectedTableOrders = [];
            
            console.log('After clear - selectedTableOrders:', this.selectedTableOrders);
            console.log('✅ Orders display cleared');
            alert('Orders display cleared!');
        },

        // Debug function to show current orders
        showOrders() {
            console.log('=== CURRENT ORDERS DEBUG ===');
            console.log('Selected table:', this.selectedTable);
            console.log('Selected table orders:', this.selectedTableOrders);
            console.log('Orders count:', this.selectedTableOrders.length);
            
            if (this.selectedTableOrders.length > 0) {
                this.selectedTableOrders.forEach((order, index) => {
                    console.log(`Order ${index + 1}:`, {
                        id: order.id,
                        status: order.status,
                        total: order.total,
                        items: order.items
                    });
                });
            } else {
                console.log('No orders found');
            }
        },

        // Close Order #118 specifically
        closeOrder118() {
            console.log('=== CLOSING ORDER #118 ===');
            this.closeOrder(118)
                .then(() => {
                    console.log('✅ Order #118 closed successfully');
                    alert('Order #118 has been closed!');
                    // Refresh the table data
                    this.refreshTablesFromDatabase();
                })
                .catch(error => {
                    console.error('❌ Failed to close Order #118:', error);
                    alert('Failed to close Order #118: ' + error.message);
                });
        },

        testMarkAsPaid() {
            console.log('=== TEST MARK AS PAID ===');
            console.log('Selected table:', this.selectedTable);
            console.log('Selected table orders:', this.selectedTableOrders);
            alert('Test button clicked! Check console for details.');
        },

        async markTableAsPaid() {
            try {
                console.log('=== MARK TABLE AS PAID START ===');
                console.log('Marking table as paid:', this.selectedTable.name);
                
                if (this.selectedTable) {
                    const table = this.tables.find(t => t.id === this.selectedTable.id);
                    if (table) {
                        // Check if table has an active order
                        if (!table.current_order_id) {
                            console.warn('⚠️ Table has no active order, cannot mark as paid');
                            alert('No active order found for this table. Please create an order first.');
                            return;
                        }
                        
                        console.log('🚀 Attempting to close order:', table.current_order_id);
                        
                        // Close the order using the server API with CLOSED status
                        const closeResult = await this.closeOrder(table.current_order_id, 'CLOSED');
                        
                        if (closeResult && closeResult.success) {
                            console.log('✅ Order closed successfully via API');
                            console.log('✅ Close result:', closeResult);
                            
                            // Update table status locally immediately for better UX
                            const tableIndex = this.tables.findIndex(t => t.id === table.id);
                            if (tableIndex !== -1) {
                                this.tables[tableIndex].status = 'free';
                                this.tables[tableIndex].current_order_id = null;
                                this.tables[tableIndex].total_amount = 0;
                                console.log('✅ Table status updated locally');
                            }
                            
                            // Clear selected table if it's the same
                            if (this.selectedTable && this.selectedTable.id === table.id) {
                                this.selectedTable = null;
                                this.selectedTableOrders = [];
                                this.customerInfo.tableNo = '';
                                console.log('✅ Selected table cleared and customer info reset');
                            }
                        
                            // Show success message immediately
                            console.log('✅ Table marked as paid successfully');
                            this.displayPaymentSuccessModal(table.name);
                            
                            // Refresh tables in background (non-blocking)
                            setTimeout(() => {
                                this.refreshTablesFromStorage();
                            }, 100);
                            
                        } else {
                            console.error('❌ Failed to close order via API');
                            alert('Failed to mark table as paid. Please try again.');
                            return;
                        }
                    }
                }
                
                console.log('=== MARK TABLE AS PAID END ===');
            } catch (error) {
                console.error('Error in markTableAsPaid:', error);
                console.error('Error stack:', error.stack);
            }
        },

        debugTableState() {
            console.log('=== TABLE STATE DEBUG ===');
            console.log('showTables:', this.showTables);
            console.log('tables array:', this.tables);
            console.log('tables length:', this.tables.length);
            console.log('selectedTable:', this.selectedTable);
            console.log('selectedTableOrders:', this.selectedTableOrders);
            console.log('selectedTableOrders length:', this.selectedTableOrders.length);
            console.log('Tables are now managed by database API');
            console.log('=== END TABLE STATE DEBUG ===');
        },

        debugTableActivation() {
            console.log('=== DEBUG TABLE ACTIVATION ===');
            console.log('Current Tables:', this.tables);
            console.log('Selected Table:', this.selectedTable);
            console.log('Selected Order Type:', this.selectedOrderType);
            console.log('Customer Info:', this.customerInfo);
            console.log('Current Order:', this.currentOrder);
            console.log('Cart:', this.cart);
            
            // Test table activation manually
            if (this.selectedTable) {
                console.log('Manually activating table:', this.selectedTable.name);
                const table = this.tables.find(t => t.id === this.selectedTable.id);
                if (table) {
                    console.log('Table found, changing status to occupied...');
                    table.status = 'occupied';
                    table.totalAmount = 100; // Test amount
                    table.orders = [{
                        id: 'test-' + Date.now(),
                        total: 100,
                        status: 'active',
                        items: [],
                        createdAt: new Date().toISOString()
                    }];
                    
                    // Table data is now managed by database API
                    this.tables = [...this.tables]; // Force reactivity
                    
                    console.log('Table activated manually. New status:', table.status);
                    console.log('Updated tables:', this.tables);
                } else {
                    console.error('Table not found in tables array');
                }
            } else {
                console.log('No table selected');
            }
            
            console.log('=== DEBUG COMPLETE ===');
        },

        // Global debug function for easy access
        globalDebugTables() {
            console.log('=== GLOBAL TABLE DEBUG ===');
            console.log('Tables:', this.tables);
            console.log('Selected Table:', this.selectedTable);
            console.log('Order Type:', this.selectedOrderType);
            
            // Make function globally accessible
            window.debugTables = () => this.globalDebugTables();
            window.clearOrders = () => this.clearOrders();
            window.showOrders = () => this.showOrders();
            window.closeOrder118 = () => this.closeOrder118();
            window.activateTable = () => {
                if (this.selectedTable) {
                    const table = this.tables.find(t => t.id === this.selectedTable.id);
                    if (table) {
                        // Use the database API to update table status
                        this.updateTableStatusInDatabase(table.id, 'occupied', 100, [{id: 'test', total: 100, status: 'active', items: [], createdAt: new Date().toISOString()}])
                            .then(updatedTable => {
                                if (updatedTable) {
                                    Object.assign(table, updatedTable);
                        this.tables = [...this.tables];
                                    console.log('Table activated via database API!');
                                } else {
                                    console.error('Failed to activate table via database API');
                                }
                            });
                    }
                }
            };
            
            // Test method to manually update table status
            window.testTableUpdate = async (tableId, status) => {
                console.log('Testing table update:', tableId, status);
                const result = await this.updateTableStatusInDatabase(tableId, status);
                console.log('Test result:', result);
                if (result) {
                    // Refresh tables to see the change
                    this.refreshTablesFromDatabase();
                }
            };
            
            // Method to force sync selected table
            window.syncSelectedTable = () => {
                this.syncSelectedTable();
                console.log('Selected table synced:', this.selectedTable);
            };
            
            // Debug function for troubleshooting
            window.debugTableStatus = (tableId = null) => {
                console.log('=== DEBUG TABLE STATUS ===');
                console.log('All tables:', this.tables);
                console.log('Selected table:', this.selectedTable);
                console.log('Selected table orders:', this.selectedTableOrders);
                
                if (tableId) {
                    const table = this.tables.find(t => t.id === tableId);
                    if (table) {
                        console.log('Specific table (ID: ' + tableId + '):', table);
                        console.log('Table current_order_id:', table.current_order_id);
                        console.log('Table status:', table.status);
                        console.log('Table orders:', table.orders);
                    } else {
                        console.log('Table with ID ' + tableId + ' not found');
                    }
                }
                
                console.log('API Base:', this.apiBase);
                console.log('Outlet ID:', this.outletId);
                console.log('=== END DEBUG ===');
            };
            
            // Test function for mark paid
            window.testMarkPaid = (tableId) => {
                const table = this.tables.find(t => t.id === tableId);
                if (table) {
                    console.log('Testing mark paid for table:', table);
                    this.markTableAsPaidFromCard(table);
                } else {
                    console.log('Table not found with ID:', tableId);
                }
            };
            
            // Debug function for right pane status
            window.debugRightPaneStatus = () => {
                console.log('=== DEBUG RIGHT PANE STATUS ===');
                console.log('Selected table:', this.selectedTable);
                console.log('Customer info table number:', this.customerInfo.tableNo);
                console.log('All tables:', this.tables);
                
                if (this.selectedTable) {
                    const tableInArray = this.tables.find(t => t.id === this.selectedTable.id);
                    console.log('Selected table in main array:', tableInArray);
                    console.log('Status match:', this.selectedTable.status === tableInArray?.status);
                    console.log('Selected table status:', this.selectedTable.status);
                    console.log('Array table status:', tableInArray?.status);
                }
                console.log('=== END DEBUG ===');
            };
            
            // Debug function for table orders
            window.debugTableOrders = async (tableId) => {
                console.log('=== DEBUG TABLE ORDERS ===');
                console.log('Table ID:', tableId);
                console.log('API Base:', this.apiBase);
                
                try {
                    const response = await fetch(`${this.apiBase}/tables/orders?table_id=${tableId}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    console.log('Response status:', response.status);
                    const data = await response.json();
                    console.log('API Response:', data);
                } catch (error) {
                    console.error('Error testing table orders API:', error);
                }
                console.log('=== END DEBUG ===');
            };
            
            // Debug function for refresh functionality
            window.debugRefresh = () => {
                console.log('=== DEBUG REFRESH FUNCTIONALITY ===');
                console.log('Selected table:', this.selectedTable);
                console.log('Selected table orders:', this.selectedTableOrders);
                console.log('Tables array length:', this.tables.length);
                console.log('=== END DEBUG ===');
            };
            
            // Method to force refresh all tables
            window.refreshTables = () => {
                this.lastTableUpdate = null; // Reset timestamp to force refresh
                this.refreshTablesFromDatabase();
                console.log('Tables refreshed from database');
            };
            
            // Method to disable periodic refresh temporarily
            window.disablePeriodicRefresh = () => {
                if (this.refreshInterval) {
                    clearInterval(this.refreshInterval);
                    this.refreshInterval = null;
                    console.log('Periodic refresh disabled');
                }
            };
            
            // Method to enable periodic refresh
            window.enablePeriodicRefresh = () => {
                if (!this.refreshInterval) {
                    this.refreshInterval = setInterval(() => {
                        this.refreshTablesFromDatabase();
                    }, 10000); // Increased to 10 seconds
                    console.log('Periodic refresh enabled (10 second interval)');
                }
            };
            
            // Method to check table sync status
            window.checkTableSync = () => {
                console.log('=== TABLE SYNC STATUS ===');
                console.log('Last table update:', this.lastTableUpdate ? new Date(this.lastTableUpdate).toLocaleString() : 'Never');
                console.log('Time since last update:', this.lastTableUpdate ? Date.now() - this.lastTableUpdate + 'ms' : 'N/A');
                console.log('Current tables:', this.tables.map(t => ({id: t.id, name: t.name, status: t.status})));
                console.log('Selected table:', this.selectedTable ? {id: this.selectedTable.id, name: this.selectedTable.name, status: this.selectedTable.status} : 'None');
            };
            
            // Method to test table status update directly
            window.testTableStatusUpdate = async (tableId, status) => {
                console.log('=== TESTING TABLE STATUS UPDATE ===');
                console.log('Table ID:', tableId);
                console.log('Status:', status);
                
                const result = await this.updateTableStatusInDatabase(tableId, status);
                console.log('API Result:', result);
                
                if (result) {
                    // Find and update the table in the array
                    const table = this.tables.find(t => t.id === tableId);
                    if (table) {
                        Object.assign(table, result);
                        this.tables = [...this.tables];
                        this.syncSelectedTable();
                        console.log('Table updated successfully');
                    } else {
                        console.error('Table not found in array');
                    }
                } else {
                    console.error('API call failed');
                }
            };
            
            // Method to compare local vs server data
            window.compareLocalVsServer = async () => {
                console.log('=== COMPARING LOCAL VS SERVER DATA ===');
                console.log('Local tables:', this.tables.map(t => ({id: t.id, name: t.name, status: t.status})));
                
                try {
                    const response = await fetch(`${this.apiBase}/tables?outlet_id=${this.outletId}`);
                    if (response.ok) {
                        const serverTables = await response.json();
                        console.log('Server tables:', serverTables.map(t => ({id: t.id, name: t.name, status: t.status})));
                        
                        // Compare each table
                        this.tables.forEach(localTable => {
                            const serverTable = serverTables.find(s => s.id === localTable.id);
                            if (serverTable) {
                                if (localTable.status !== serverTable.status) {
                                    console.warn(`Table ${localTable.name} status mismatch:`, {
                                        local: localTable.status,
                                        server: serverTable.status
                                    });
                                } else {
                                    console.log(`Table ${localTable.name} status matches:`, localTable.status);
                                }
                            } else {
                                console.error(`Table ${localTable.name} not found on server`);
                            }
                        });
                    } else {
                        console.error('Failed to fetch server data');
                    }
                } catch (error) {
                    console.error('Error comparing data:', error);
                }
            };
            
            // Method to clear all local update flags
            window.clearLocalUpdateFlags = () => {
                this.tables.forEach(table => {
                    delete table._localUpdate;
                    delete table._localUpdateTime;
                });
                console.log('Local update flags cleared');
            };
            
            // Method to show tables with local updates
            window.showLocalUpdates = () => {
                const tablesWithLocalUpdates = this.tables.filter(t => t._localUpdate);
                console.log('Tables with local updates:', tablesWithLocalUpdates.map(t => ({
                    id: t.id,
                    name: t.name,
                    status: t.status,
                    updateTime: t._localUpdateTime ? new Date(t._localUpdateTime).toLocaleString() : 'Unknown'
                })));
            };
            
            console.log('Global functions created: debugTables(), activateTable()');
            console.log('=== GLOBAL DEBUG COMPLETE ===');
        },

        printOrderBill(order) {
            // Print bill for the order
            if (order && order.id) {
                this.printBill(order);
            } else {
                alert('Cannot print bill - order data incomplete');
            }
        },


        viewOrderDetails(order) {
            console.log('=== VIEW ORDER DETAILS ===');
            console.log('Order:', order);
            
            this.selectedOrderDetails = order;
            this.showOrderDetails = true;
            
            console.log('Modal opened for order:', order.id);
        },
        
        
        // Listen to customer saved events
        initCustomerListenerBound: false,
        ensureCustomerListener() {
            if (this.initCustomerListenerBound) return;
            window.addEventListener('customer-saved', (e) => {
                const d = e.detail || {};
                this.customerInfo.customerName = d.customerName || '';
                this.customerInfo.customerPhone = d.customerPhone || '';
                this.customerInfo.address = d.address || '';
                this.customerInfo.deliveryAddress = d.deliveryAddress || '';
                this.customerInfo.deliveryFee = d.deliveryFee || 0;
                this.customerInfo.specialInstructions = d.specialInstructions || '';
                
                // Show customer details in the main order cart when customer is selected
                this.showCustomerDetails = true;
            });
            this.initCustomerListenerBound = true;
        },
        
        addToCart(item) {
            if (this.posLocked) {
                console.log('POS is locked, cannot add items to cart');
                return;
            }
            
            const existingItem = this.cart.find(cartItem => cartItem.id === item.id);
            
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                this.cart.push({
                    id: item.id,
                    name: item.name,
                    price: item.price || 0,
                    qty: 1,
                    modifiers: []
                });
            }
        },
        
        removeFromCart(itemId) {
            this.cart = this.cart.filter(item => item.id !== itemId);
        },
        
        updateQuantity(itemId, qty) {
            const item = this.cart.find(cartItem => cartItem.id === itemId);
            if (item) {
                if (qty <= 0) {
                    this.removeFromCart(itemId);
                } else {
                    item.qty = qty;
                }
            }
        },
        
        async applyDiscount() {
            if (!this.discountCode || this.cart.length === 0) return;
            
            try {
                // Get tenant slug for discount API
                const path = window.location.pathname;
                const segments = path.replace(/^\/+|\/+$/g, '').split('/');
                const tenantSlug = segments[0];
                
                const response = await fetch(`/${tenantSlug}/discounts-api/calculate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        amount: this.cartTotal,
                        discount_code: this.discountCode,
                        order_type: this.selectedOrderType?.slug || 'dine-in'
                    })
                });
                
                const data = await response.json();
                
                if (data.error) {
                    alert(data.error);
                    return;
                }
                
                this.appliedDiscount = data.discount;
                this.discountAmount = data.discount_amount;
                this.discountCode = '';
                
            } catch (error) {
                console.error('Error applying discount:', error);
                alert('Error applying discount');
            }
        },
        
        removeDiscount() {
            this.appliedDiscount = null;
            this.discountAmount = 0;
            this.discountCode = '';
        },
        
        async createOrder(shouldPrint = false, shouldCreateKot = false) {
            try {
                console.log('=== CREATE ORDER START ===');
                console.log('POS Locked:', this.posLocked);
                console.log('Cart Length:', this.cart.length);
                console.log('Payment Method:', this.paymentMethod);
                console.log('Selected Order Type:', this.selectedOrderType);
                console.log('Selected Table:', this.selectedTable);
                console.log('Customer Info:', this.customerInfo);
                
            if (this.posLocked) {
                console.log('POS is locked, cannot create orders');
                alert('POS is locked. Please open a new shift to continue.');
                return;
            }
            
                if (this.cart.length === 0) {
                    console.log('Cart is empty, cannot create order');
                    return;
                }
                
            if (!this.paymentMethod) {
                    console.log('No payment method selected');
                alert('Please select a payment method');
                return;
            }
                // Prepare order data based on order type
                const orderData = {
                    outlet_id: this.outletId,
                    order_type_id: this.orderTypes.length > 0 ? this.orderTypes[0].id : null,
                    payment_method: this.paymentMethod,
                    special_instructions: this.customerInfo.specialInstructions,
                    items: this.cart.map(cartItem => ({
                        item_id: cartItem.id,
                        qty: cartItem.qty
                    })),
                    discount_code: this.appliedDiscount?.code || null,
                    discount_amount: this.discountAmount
                };
                if (this.deviceId) orderData.device_id = this.deviceId;
                
                // Add order type specific data
                let placeOrderData = null; // Declare placeOrderData in the correct scope
                if (this.customerInfo.orderType === 'dine-in') {
                    // For dine-in orders, use the placeOrder API which handles table management
                    const tableId = this.selectedTable ? this.selectedTable.id : null;
                    if (!tableId) {
                        alert('Please select a table for dine-in orders');
                        return;
                    }
                    
                    // First place the order using the placeOrder API
                    console.log('=== PLACE ORDER API CALL ===');
                    console.log('API Base:', this.apiBase);
                    console.log('Table ID:', tableId);
                    console.log('Full URL:', `${this.apiBase}/orders/place`);
                    
                    const placeOrderResponse = await fetch(`${this.apiBase}/orders/place`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ table_id: tableId })
                    });
                    
                    if (!placeOrderResponse.ok) {
                        const errorData = await placeOrderResponse.json();
                        console.error('PlaceOrder API error:', errorData);
                        throw new Error(errorData.error || 'Failed to place order for table');
                    }
                    
                    placeOrderData = await placeOrderResponse.json();
                    console.log('PlaceOrder API response:', placeOrderData);
                    console.log('Order ID from response:', placeOrderData.order_id);
                    console.log('Type of order_id:', typeof placeOrderData.order_id);
                    
                    if (!placeOrderData.success) {
                        console.error('PlaceOrder API returned success=false:', placeOrderData);
                        throw new Error(placeOrderData.error || 'Failed to place order for table');
                    }
                    
                    // Validate that we have a valid order ID
                    if (!placeOrderData.order_id || isNaN(placeOrderData.order_id)) {
                        console.error('Invalid order_id in placeOrder response:', placeOrderData);
                        throw new Error('Invalid order_id in placeOrder response: ' + placeOrderData.order_id);
                    }
                    
                    // Now update the order with the cart items and customer info
                    orderData.table_id = tableId;
                    orderData.table_no = this.customerInfo.tableNo || '1';
                    orderData.customer_name = this.customerInfo.customerName;
                    orderData.customer_phone = this.customerInfo.customerPhone;
                    orderData.customer_address = this.customerInfo.address;
                    orderData.mode = 'DINE_IN';
                } else if (this.customerInfo.orderType === 'delivery') {
                    orderData.customer_name = this.customerInfo.customerName;
                    orderData.customer_phone = this.customerInfo.customerPhone;
                    orderData.customer_address = this.customerInfo.address;
                    orderData.delivery_address = this.customerInfo.deliveryAddress;
                    orderData.delivery_fee = this.customerInfo.deliveryFee || 0;
                    orderData.mode = 'DELIVERY';
                } else if (this.customerInfo.orderType === 'pick-up') {
                    orderData.customer_name = this.customerInfo.customerName;
                    orderData.customer_phone = this.customerInfo.customerPhone;
                    orderData.customer_address = this.customerInfo.address;
                    orderData.mode = 'PICKUP';
                }
                
                // Create or update order
                let order;
                if (this.customerInfo.orderType === 'dine-in' && placeOrderData) {
                    // For dine-in orders, we already created the order via placeOrder API
                    order = { id: placeOrderData.order_id };
                    console.log('Using order from placeOrder API:', order);
                    console.log('Order ID being used:', order.id);
                    console.log('Type of order.id:', typeof order.id);
                } else {
                    // For other order types, create the order normally
                    const orderResponse = await fetch(`${this.apiBase}/orders`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            ...(this.deviceKey ? { 'X-Device-Key': this.deviceKey } : {})
                        },
                        body: JSON.stringify(orderData)
                    });
                    
                    order = await orderResponse.json();
                    console.log('Order creation response:', order);
                }
                this.currentOrder = order;
                
                // Check if order was created successfully
                if (!order || !order.id) {
                    throw new Error('Order creation failed - no ID returned');
                }
                
                // For dine-in orders, update the order with cart items and customer info
                if (this.customerInfo.orderType === 'dine-in') {
                    // Update the order with cart items and customer info
                    console.log('=== UPDATE ORDER API CALL ===');
                    console.log('API Base:', this.apiBase);
                    console.log('Order ID:', order.id);
                    console.log('Order ID type:', typeof order.id);
                    console.log('Order ID value:', JSON.stringify(order.id));
                    console.log('Full URL:', `${this.apiBase}/orders/${order.id}`);
                    
                    // Validate that order ID is a number
                    if (!order.id || isNaN(order.id)) {
                        console.error('Invalid order ID:', order.id);
                        console.error('Order ID type:', typeof order.id);
                        console.error('Order ID value:', JSON.stringify(order.id));
                        throw new Error('Invalid order ID: ' + order.id);
                    }
                    
                    const updateOrderResponse = await fetch(`${this.apiBase}/orders/${order.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            ...(this.deviceKey ? { 'X-Device-Key': this.deviceKey } : {})
                        },
                        body: JSON.stringify({
                            customer_name: this.customerInfo.customerName,
                            customer_phone: this.customerInfo.customerPhone,
                            customer_address: this.customerInfo.address,
                            special_instructions: this.customerInfo.specialInstructions,
                            items: this.cart.map(cartItem => ({
                                item_id: cartItem.id,
                                qty: cartItem.qty
                            })),
                            discount_code: this.appliedDiscount?.code || null,
                            discount_amount: this.discountAmount
                        })
                    });
                    
                    if (!updateOrderResponse.ok) {
                        const updateError = await updateOrderResponse.json();
                        console.error('UpdateOrder API error:', updateError);
                        throw new Error(updateError.error || 'Failed to update order with cart items');
                    }
                    
                    const updateData = await updateOrderResponse.json();
                    if (!updateData.success) {
                        console.error('UpdateOrder API returned success=false:', updateData);
                        throw new Error(updateData.error || 'Failed to update order with cart items');
                    }
                }
                
                // Create KOT if requested
                if (shouldCreateKot) {
                    try {
                        const kotResponse = await fetch(`${this.apiBase}/orders/${order.id}/kot`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                ...(this.deviceKey ? { 'X-Device-Key': this.deviceKey } : {})
                            },
                            body: JSON.stringify({
                                station: 'hot-kitchen' // Default station
                            })
                        });
                        
                        if (kotResponse.status >= 200 && kotResponse.status < 300) {
                            const kot = await kotResponse.json();
                            if (kot && kot.id) {
                                if (shouldPrint) {
                                    this.showNotification('success', 'Order Created Successfully!', `Order #${order.id} has been created, KOT #${kot.id} sent to kitchen, and bill printed.`, 'The kitchen will start preparing your order and bill has been printed.');
                                } else {
                                this.showNotification('success', 'Order Created Successfully!', `Order #${order.id} has been created and KOT #${kot.id} sent to kitchen.`, 'The kitchen will start preparing your order.');
                                }
                            } else {
                                this.showNotification('warning', 'Order Created Successfully!', `Order #${order.id} has been created.`, 'Note: KOT response format unexpected.');
                            }
                        } else {
                            this.showNotification('warning', 'Order Created Successfully!', `Order #${order.id} has been created.`, 'Note: KOT could not be created.');
                        }
                    } catch (kotError) {
                        console.error('Error creating KOT:', kotError);
                        this.showNotification('warning', 'Order Created Successfully!', `Order #${order.id} has been created.`, 'Note: KOT could not be created.');
                    }
                } else {
                    if (shouldPrint) {
                        this.showNotification('success', 'Order Created Successfully!', `Order #${order.id} has been created and bill printed.`, 'Your order has been saved and bill has been printed.');
                } else {
                    this.showNotification('success', 'Order Created Successfully!', `Order #${order.id} has been created.`, 'Your order has been saved successfully.');
                    }
                }
                
                // Print order if requested
                if (shouldPrint) {
                    this.printBill(order);
                }
                
                // Update table order if dine-in (before clearing cart)
                console.log('=== CHECKING TABLE UPDATE CONDITIONS ===');
                console.log('Selected Order Type:', this.selectedOrderType);
                console.log('Selected Table:', this.selectedTable);
                console.log('Customer Info:', this.customerInfo);
                
                if (this.selectedOrderType === 'dine-in' && this.selectedTable) {
                    const orderTotal = this.finalTotal;
                    console.log('CONDITION MET: Updating table order');
                    console.log('Table Name:', this.selectedTable.name);
                    console.log('Table ID:', this.selectedTable.id);
                    console.log('Order Total:', orderTotal);
                    console.log('Current Order:', this.currentOrder);
                    console.log('Cart Items:', this.cart);
                    await this.updateTableOrder(this.selectedTable.id, orderTotal);
                } else {
                    console.log('CONDITION NOT MET: Not updating table order');
                    console.log('Reason - Order Type:', this.selectedOrderType, 'Expected: dine-in');
                    console.log('Reason - Selected Table:', this.selectedTable, 'Expected: truthy');
                }
                
                // Clear cart after table update
                this.cart = [];
                this.paymentMethod = '';
                
                // Clear customer details
                this.customerInfo = {
                    orderType: 'dine-in',
                    tableNo: '',
                    customerName: '',
                    customerPhone: '',
                    address: '',
                    deliveryAddress: '',
                    deliveryFee: 0,
                    specialInstructions: ''
                };
                this.showCustomerDetails = false;
                
                // Refresh shift summary after creating order
                this.loadShiftSummary();
                
                console.log('=== CREATE ORDER END - SUCCESS ===');
                console.log('Final Tables State:', this.tables);
                console.log('Selected Table After:', this.selectedTable);
                
            } catch (error) {
                console.error('=== CREATE ORDER ERROR ===');
                console.error('Error creating order:', error);
                console.error('Error stack:', error.stack);
                console.error('Cart:', this.cart);
                console.error('Payment Method:', this.paymentMethod);
                console.error('Selected Table:', this.selectedTable);
                console.error('Customer Info:', this.customerInfo);
                this.showNotification('error', 'Order Creation Failed', 'Failed to create order. Please try again.', error.message || 'Unknown error occurred');
            }
        },
        
        clearCustomerDetails() {
            this.customerInfo = {
                orderType: 'dine-in',
                tableNo: '',
                customerName: '',
                customerPhone: '',
                address: '',
                deliveryAddress: '',
                deliveryFee: 0,
                specialInstructions: ''
            };
            this.showCustomerDetails = false;
        },
        
        printCurrentOrder() {
            if (this.cart.length === 0) {
                alert('Cart is empty');
                return;
            }
            
            // Create a temporary order object for printing
            const tempOrder = {
                id: 'TEMP-' + Date.now(),
                order_type: this.selectedOrderType,
                payment_method: this.paymentMethod,
                customer_name: this.customerInfo.customerName,
                customer_phone: this.customerInfo.customerPhone,
                table_no: this.customerInfo.tableNo,
                special_instructions: this.customerInfo.specialInstructions,
                items: this.cart.map(cartItem => ({
                    item: cartItem,
                    qty: cartItem.qty,
                    price: cartItem.price
                }))
            };
            
            // Use the same print function as the orders page
            this.printBill(tempOrder);
        },
        
        printBill(order) {
            // Create a new window for printing
            const printWindow = window.open('', '_blank', 'width=800,height=600');
            
            // Get current date and time
            const now = new Date();
            const dateTime = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
            
            // Calculate total - handle different order structures
            let subtotal = 0;
            let discount = order.discount_amount || 0;
            let itemsToPrint = [];
            
            if (order.items && order.items.length > 0) {
                // For API order objects
                itemsToPrint = order.items;
                subtotal = order.items.reduce((sum, item) => {
                    const itemPrice = item.item ? item.item.price : item.price || 0;
                    const itemQty = item.qty || 0;
                    return sum + (itemPrice * itemQty);
                }, 0);
            } else if (this.cart && this.cart.length > 0) {
                // Fallback to current cart if order.items is not available
                itemsToPrint = this.cart.map(cartItem => ({
                    item: { name: cartItem.name, price: cartItem.price },
                    qty: cartItem.qty,
                    price: cartItem.price
                }));
                subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                discount = this.discountAmount || 0;
            }
            
            const total = subtotal - discount;
            
            // Create receipt HTML
            const receiptHTML = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Receipt - Order #${order.id}</title>
                    <style>
                        body {
                            font-family: 'Courier New', monospace;
                            font-size: 12px;
                            line-height: 1.4;
                            margin: 0;
                            padding: 20px;
                            background: white;
                        }
                        .receipt {
                            max-width: 300px;
                            margin: 0 auto;
                            border: 1px solid #000;
                            padding: 15px;
                        }
                        .header {
                            text-align: center;
                            border-bottom: 1px dashed #000;
                            padding-bottom: 10px;
                            margin-bottom: 15px;
                        }
                        .restaurant-name {
                            font-size: 18px;
                            font-weight: bold;
                            margin-bottom: 5px;
                        }
                        .restaurant-address {
                            font-size: 10px;
                            margin-bottom: 5px;
                        }
                        .order-info {
                            margin-bottom: 15px;
                        }
                        .order-info div {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 3px;
                        }
                        .items {
                            border-bottom: 1px dashed #000;
                            padding-bottom: 10px;
                            margin-bottom: 10px;
                        }
                        .item {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 5px;
                        }
                        .item-name {
                            flex: 1;
                        }
                        .item-qty {
                            margin: 0 10px;
                        }
                        .item-price {
                            text-align: right;
                        }
                        .total {
                            border-top: 1px dashed #000;
                            padding-top: 10px;
                            font-weight: bold;
                            font-size: 14px;
                        }
                        .total div {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 5px;
                        }
                        .footer {
                            text-align: center;
                            margin-top: 15px;
                            font-size: 10px;
                        }
                        @media print {
                            body { margin: 0; padding: 0; }
                            .receipt { border: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="receipt">
                        <div class="header">
                            <div class="restaurant-name">Dukaantech</div>
                            <div class="restaurant-address">Restaurant Address</div>
                            <div>Phone: 123-456-7890</div>
                        </div>
                        
                        <div class="order-info">
                            <div><span>Order #:</span><span>${order.id}</span></div>
                            <div><span>Date:</span><span>${dateTime}</span></div>
                            <div><span>Order Type:</span><span>${order.order_type ? (order.order_type.slug === 'pick-up' ? 'Take Away' : order.order_type.name) : (order.mode === 'DELIVERY' ? 'Delivery' : (order.mode === 'DINE_IN' ? 'Dine In' : (order.mode === 'PICKUP' ? 'Take Away' : 'Dine In')))}</span></div>
                            <div><span>Payment:</span><span>${order.payment_method ? order.payment_method.toUpperCase() : 'CASH'}</span></div>
                            ${order.table_no ? `<div><span>Table:</span><span>${order.table_no}</span></div>` : ''}
                        </div>
                        
                        <div class="customer-info" style="border-top: 1px solid #000; padding-top: 10px; margin-bottom: 15px;">
                            <div><span>Name:</span><span>${(order.order_type && order.order_type.slug === 'delivery') || order.mode === 'DELIVERY' ? (order.customer_name || '') : ''}</span></div>
                            <div><span>Address:</span><span>${(order.order_type && order.order_type.slug === 'delivery') || order.mode === 'DELIVERY' ? (order.delivery_address || order.customer_address || '') : ''}</span></div>
                        </div>
                        
                        <div class="items">
                            ${itemsToPrint.map(item => {
                                const itemName = item.item ? item.item.name : item.name || 'Unknown Item';
                                const itemPrice = item.item ? item.item.price : item.price || 0;
                                const itemQty = item.qty || 0;
                                return `
                                <div class="item">
                                        <span class="item-name">${itemName}</span>
                                        <span class="item-qty">x${itemQty}</span>
                                        <span class="item-price">₹${(itemPrice * itemQty).toFixed(2)}</span>
                                </div>
                                `;
                            }).join('')}
                        </div>
                        
                        <div class="total">
                            <div><span>Subtotal:</span><span>₹${subtotal.toFixed(2)}</span></div>
                            ${discount > 0 ? `<div class="discount"><span>Discount:</span><span>-₹${discount.toFixed(2)}</span></div>` : ''}
                            <div><span>Tax:</span><span>₹0.00</span></div>
                            <div><span>Total:</span><span>₹${total.toFixed(2)}</span></div>
                        </div>
                        
                        ${order.special_instructions ? `
                            <div style="margin-top: 10px; font-size: 10px;">
                                <strong>Special Instructions:</strong><br>
                                ${order.special_instructions}
                            </div>
                        ` : ''}
                        
                        <div class="footer">
                            <div>Thank you for your order!</div>
                            <div>Visit us again soon</div>
                        </div>
                    </div>
                </body>
                </html>
            `;
            
            // Write the HTML to the new window
            printWindow.document.write(receiptHTML);
            printWindow.document.close();
            
            // Wait for the content to load, then print
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        },
        
        async openShift() {
            try {
                const response = await fetch(`${this.apiBase}/shifts/open`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        outlet_id: this.outletId,
                        opening_float: this.openingFloat || 0
                    })
                });
                
                if (response.ok) {
                    this.shift = await response.json();
                    this.posLocked = false; // Unlock POS when shift opens
                    this.shiftSummary = null; // Clear any previous shift summary
                    this.saveShiftToStorage(); // Save to localStorage
                    console.log('Shift opened successfully, unlocking POS');
                    alert('Shift opened successfully!');
                } else {
                    const error = await response.json();
                    alert('Error opening shift: ' + (error.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error opening shift:', error);
                alert('Error opening shift');
            }
        },
        
        async openShiftCheckout() {
            console.log('=== SHIFT CHECKOUT DEBUG ===');
            console.log('Current shift:', this.shift);
            console.log('Current shiftSummary:', this.shiftSummary);
            console.log('API Base:', this.apiBase);
            
            this.showShiftCheckoutModal = true;
            
            // Calculate summary directly from current shift data
            if (this.shift && this.shift.id) {
                try {
                    // Get orders for this shift
                    const ordersResponse = await fetch(`${this.apiBase}/orders?shift_id=${this.shift.id}&outlet_id=${this.outletId}&t=${Date.now()}`);
                    if (ordersResponse.ok) {
                        const orders = await ordersResponse.json();
                        console.log('Orders for shift:', orders);
                        
                        // Calculate summary
                        let totalSales = 0;
                        let totalOrders = 0;
                        let cashSales = 0;
                        let cardSales = 0;
                        let upiSales = 0;
                        
                        orders.forEach(order => {
                            if (order.status !== 'CANCELLED') {
                                totalOrders++;
                                
                                // Calculate order total
                                let orderTotal = 0;
                                if (order.items && order.items.length > 0) {
                                    order.items.forEach(item => {
                                        const subtotal = item.qty * item.price;
                                        const modifierTotal = item.modifiers ? item.modifiers.reduce((sum, mod) => sum + (mod.price || 0), 0) : 0;
                                        const tax = (subtotal + modifierTotal) * (item.tax_rate / 100);
                                        orderTotal += subtotal + modifierTotal + tax - (item.discount || 0);
                                    });
                                }
                                orderTotal = Math.round(orderTotal * 100) / 100;
                                
                                totalSales += orderTotal;
                                
                                // Categorize by payment method
                                if (order.payment_method === 'cash') {
                                    cashSales += orderTotal;
                                } else if (order.payment_method === 'card') {
                                    cardSales += orderTotal;
                                } else if (order.payment_method === 'upi') {
                                    upiSales += orderTotal;
                                }
                            }
                        });
                        
                        this.shiftSummary = {
                            total_sales: totalSales,
                            total_orders: totalOrders,
                            cash_sales: cashSales,
                            card_sales: cardSales,
                            upi_sales: upiSales,
                            opening_float: this.shift?.opening_float || 0
                        };
                        
                        console.log('Calculated summary:', this.shiftSummary);
                    } else {
                        throw new Error('Failed to fetch orders');
                    }
                } catch (error) {
                    console.error('Error calculating summary:', error);
                    this.shiftSummary = {
                        total_sales: 0,
                        total_orders: 0,
                        cash_sales: 0,
                        card_sales: 0,
                        upi_sales: 0,
                        opening_float: this.shift?.opening_float || 0
                    };
                }
            } else {
                // No shift available
                this.shiftSummary = {
                    total_sales: 0,
                    total_orders: 0,
                    cash_sales: 0,
                    card_sales: 0,
                    upi_sales: 0,
                    opening_float: 0
                };
            }
        },
        
        closeShiftCheckout() {
            console.log('Closing shift checkout modal...');
            this.showShiftCheckoutModal = false;
            this.shiftSummary = null;
            this.actualCash = 0;
        },
        
        async checkoutShift() {
            console.log('Checkout shift clicked!');
            if (!this.actualCash && this.actualCash !== 0) {
                alert('Please enter the actual cash amount');
                return;
            }
            
            try {
                this.checkoutLoading = true;
                const response = await fetch(`${this.apiBase}/dashboard/shift/checkout`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        actual_cash: parseFloat(this.actualCash)
                    })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    alert('Shift closed successfully!');
                    this.closeShiftCheckout();
                    this.shift = null; // Clear shift
                } else {
                    const error = await response.json();
                    alert(error.error || 'Error closing shift');
                }
            } catch (error) {
                console.error('Error closing shift:', error);
                alert('Error closing shift');
            } finally {
                this.checkoutLoading = false;
            }
        },
        
        getVariance() {
            if (!this.shiftSummary) return 0;
            const expectedCash = (this.shiftSummary.opening_float || 0) + (this.shiftSummary.cash_sales || 0);
            return (this.actualCash || 0) - expectedCash;
        },
        
        getVarianceClass() {
            const variance = this.getVariance();
            if (variance > 0) return 'text-green-600';
            if (variance < 0) return 'text-red-600';
            return 'text-gray-600';
        },
        
        lockPOS() {
            console.log('Locking POS system...');
            this.posLocked = true;
            this.cart = []; // Clear cart
            this.paymentMethod = ''; // Clear payment method
            this.customerInfo = {
                tableNo: '',
                customerName: '',
                customerPhone: '',
                deliveryAddress: '',
                deliveryFee: 0,
                specialInstructions: ''
            };
        },
        
        async openOrdersModal() {
            if (!this.shift) {
                alert('No active shift found');
                return;
            }
            
            // Dispatch event to open orders modal
            window.dispatchEvent(new CustomEvent('open-orders-modal', {
                detail: { shiftId: this.shift.id }
            }));
        },
        
        openProductVisibilityModal() {
            console.log('Product visibility button clicked');
            // Dispatch event to open product visibility modal
            window.dispatchEvent(new CustomEvent('open-product-visibility-modal'));
        },
        
        
    }
}

// Clean Alpine.js modal component
function checkoutModal() {
    return {
        isOpen: false,
        actualCash: 0,
        shiftSummary: null,
        loading: false,
        shiftInfo: null,
        apiBase: null,
        
        init() {
            // Set up API base URL
            const host = window.location.hostname;
            const pathSegments = window.location.pathname.split('/').filter(segment => segment);
            const isPathBased = host.indexOf('.') === -1 || pathSegments[0] !== 'pos';
            const tenantSlug = (isPathBased && pathSegments.length > 0) ? pathSegments[0] : null;
            this.apiBase = isPathBased && tenantSlug ? `/${tenantSlug}/pos/api` : `/pos/api`;
        },
        
        async open() {
            console.log('=== OPENING CHECKOUT MODAL ===');
            console.log('API Base:', this.apiBase);
            this.isOpen = true;
            this.loading = true;
            
            // Get current shift and calculate summary directly
            try {
                // First get current shift
                const shiftResponse = await fetch(`${this.apiBase}/dashboard/shift/current?t=${Date.now()}`);
                if (shiftResponse.ok) {
                    const shiftData = await shiftResponse.json();
                    console.log('Shift data received:', shiftData);
                    
                    if (shiftData.shift && shiftData.shift.id) {
                        // Get orders for this shift
                        const ordersResponse = await fetch(`${this.apiBase}/orders?shift_id=${shiftData.shift.id}&outlet_id=1&t=${Date.now()}`);
                        if (ordersResponse.ok) {
                            const orders = await ordersResponse.json();
                            console.log('Orders for shift:', orders);
                            
                            // Calculate summary
                            let totalSales = 0;
                            let totalOrders = 0;
                            let cashSales = 0;
                            let cardSales = 0;
                            let upiSales = 0;
                            
                            orders.forEach(order => {
                                if (order.status !== 'CANCELLED') {
                                    totalOrders++;
                                    
                                    // Calculate order total
                                    let orderTotal = 0;
                                    if (order.items && order.items.length > 0) {
                                        order.items.forEach(item => {
                                            const subtotal = item.qty * item.price;
                                            const modifierTotal = item.modifiers ? item.modifiers.reduce((sum, mod) => sum + (mod.price || 0), 0) : 0;
                                            const tax = (subtotal + modifierTotal) * (item.tax_rate / 100);
                                            orderTotal += subtotal + modifierTotal + tax - (item.discount || 0);
                                        });
                                    }
                                    orderTotal = Math.round(orderTotal * 100) / 100;
                                    
                                    totalSales += orderTotal;
                                    
                                    // Categorize by payment method
                                    if (order.payment_method === 'cash') {
                                        cashSales += orderTotal;
                                    } else if (order.payment_method === 'card') {
                                        cardSales += orderTotal;
                                    } else if (order.payment_method === 'upi') {
                                        upiSales += orderTotal;
                                    }
                                }
                            });
                            
                            this.shiftSummary = {
                                total_sales: totalSales,
                                total_orders: totalOrders,
                                cash_sales: cashSales,
                                card_sales: cardSales,
                                upi_sales: upiSales,
                                opening_float: shiftData.shift?.opening_float || 0
                            };
                            
                            // Set shift information based on current time
                            this.shiftInfo = this.getCurrentShiftInfo();
                            
                            console.log('Calculated summary:', this.shiftSummary);
                            console.log('Shift info:', this.shiftInfo);
                        } else {
                            throw new Error('Failed to fetch orders');
                        }
                    } else {
                        // No shift available
                        this.shiftSummary = {
                            total_sales: 0,
                            total_orders: 0,
                            cash_sales: 0,
                            card_sales: 0,
                            upi_sales: 0,
                            opening_float: 0
                        };
                    }
                } else {
                    throw new Error('Failed to fetch shift');
                }
            } catch (error) {
                console.error('Error calculating summary:', error);
                this.shiftSummary = {
                    total_sales: 0,
                    total_orders: 0,
                    cash_sales: 0,
                    card_sales: 0,
                    upi_sales: 0,
                    opening_float: 0
                };
            } finally {
                this.loading = false;
            }
            
            // Optional: focus the input field after next tick
            this.$nextTick(() => {
                const input = this.$el.querySelector('input[type="number"]');
                if (input) input.focus();
            });
        },
        
        close() {
            console.log('Closing checkout modal...');
            this.isOpen = false;
            this.actualCash = 0;
            this.shiftSummary = null;
            this.shiftInfo = null;
            this.loading = false;
        },
        
        getCurrentShiftInfo() {
            const currentTime = new Date().toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            // Night shift (10 PM to 6 AM) - spans midnight
            if (currentTime >= '22:00' || currentTime < '06:00') {
                return {
                    name: 'Night Shift',
                    description: '10 PM to 6 AM'
                };
            }
            // Morning shift (6 AM to 3 PM)
            else if (currentTime >= '06:00' && currentTime < '15:00') {
                return {
                    name: 'Morning Shift',
                    description: '6 AM to 3 PM'
                };
            }
            // Evening shift (2 PM to 10 PM) - overlaps with morning shift
            else if (currentTime >= '14:00' && currentTime < '22:00') {
                return {
                    name: 'Evening Shift',
                    description: '2 PM to 10 PM'
                };
            }
            
            // Default to morning shift
            return {
                name: 'Morning Shift',
                description: '6 AM to 3 PM'
            };
        },
        
        async performLogout() {
            try {
                // Get session token from cookie or localStorage
                const sessionToken = this.getCookie('terminal_session_token') || localStorage.getItem('terminal_session_token');
                
                const response = await fetch(`/{{ $tenant->slug }}/terminal/logout`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Terminal-Session-Token': sessionToken || ''
                    }
                });
                
                if (response.ok) {
                    // Clear session data
                    this.clearSessionData();
                    
                    // Redirect to terminal login page
                    window.location.href = `/{{ $tenant->slug }}/terminal/login`;
                } else {
                    console.error('Logout failed, redirecting anyway');
                    // Even if logout fails, redirect to terminal login
                    window.location.href = `/{{ $tenant->slug }}/terminal/login`;
                }
            } catch (error) {
                console.error('Error during logout:', error);
                // Even if logout fails, redirect to terminal login
                window.location.href = `/{{ $tenant->slug }}/terminal/login`;
            }
        },
        
        getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        },
        
        clearSessionData() {
            // Clear cookies
            document.cookie = 'terminal_session_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            
            // Clear localStorage
            localStorage.removeItem('terminal_session_token');
            localStorage.removeItem('pos_data');
        },
        
        async submit() {
            console.log('=== SUBMITTING SHIFT CHECKOUT ===');
            console.log('Actual cash:', this.actualCash);
            console.log('API Base:', this.apiBase);
            console.log('URL:', `${this.apiBase}/dashboard/shift/checkout`);
            
            if (!this.actualCash && this.actualCash !== 0) {
                alert('Please enter the actual cash amount');
                return;
            }
            
            try {
                // Make API call to close the shift
                const response = await fetch(`${this.apiBase}/dashboard/shift/checkout`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        actual_cash: parseFloat(this.actualCash)
                    })
                });
                
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (response.ok) {
                    const result = await response.json();
                    console.log('✅ Shift checkout successful:', result);
                    alert('Shift closed successfully! Logging out...');
                    this.close();
                    
                    // Dispatch event to update main POS component
                    window.dispatchEvent(new CustomEvent('shift-closed'));
                    
                    // Automatically logout after successful shift checkout
                    setTimeout(() => {
                        this.performLogout();
                    }, 1000);
                } else {
                    const error = await response.json();
                    console.log('❌ Shift checkout failed:', error);
                    alert('Error closing shift: ' + (error.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error closing shift:', error);
                alert('Error closing shift: ' + error.message);
            }
        }
    }
}


// Customer Modal component
function customerModal() {
    return {
        isOpen: false,
        showCustomerListModal: false,
        allCustomers: [],
        selectedCustomerId: null,
        form: {
            orderType: 'dine-in',
            customerName: '',
            customerPhone: '',
            address: '',
            deliveryAddress: '',
            deliveryFee: 0
        },
        init() {
            // Listen for open-customer-modal event
            window.addEventListener('open-customer-modal', (e) => {
                const detail = e.detail || {};
                this.open(detail);
            });
        },
        open(detail) {
            const prefill = detail && detail.prefill ? detail.prefill : null;
            this.form = {
                customerName: prefill?.customerName || '',
                customerPhone: prefill?.customerPhone || '',
                address: prefill?.address || '',
                deliveryAddress: prefill?.deliveryAddress || '',
                deliveryFee: prefill?.deliveryFee || 0
            };
            this.isOpen = true;
            this.$nextTick(() => {
                const input = this.$el.querySelector('input[type="text"]');
                if (input) input.focus();
            });
        },
        close() {
            this.isOpen = false;
        },
        save() {
            // Basic required: name
            if (!this.form.customerName) {
                alert('Please enter customer name');
                return;
            }
            window.dispatchEvent(new CustomEvent('customer-saved', {
                detail: {
                    customerName: this.form.customerName,
                    customerPhone: this.form.customerPhone,
                    address: this.form.address,
                    deliveryAddress: this.form.deliveryAddress,
                    deliveryFee: this.form.deliveryFee,
                    specialInstructions: ''
                }
            }));
            this.close();
        },
        
        async openCustomerList() {
            try {
                // Get the tenant slug from the current URL
                const pathParts = window.location.pathname.split('/');
                const tenantSlug = pathParts[1]; // Should be 'dukaantech'
                const url = `/${tenantSlug}/api/public/customers`;
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.allCustomers = data;
                    this.showCustomerListModal = true;
                    this.isOpen = false; // Close the customer modal
                } else {
                    const errorText = await response.text();
                    alert('Failed to load customers: ' + response.status);
                }
            } catch (error) {
                console.error('Error loading customers:', error);
                alert('Error loading customers: ' + error.message);
            }
        },
        
        selectCustomer(customerId) {
            this.selectedCustomerId = customerId;
        },
        
        confirmCustomerSelection() {
            if (!this.selectedCustomerId) {
                alert('Please select a customer');
                return;
            }
            
            const selectedCustomer = this.allCustomers.find(c => c.id === this.selectedCustomerId);
            if (selectedCustomer) {
                // Prefill the form with selected customer data
                this.form.customerName = selectedCustomer.name || '';
                this.form.customerPhone = selectedCustomer.phone || '';
                this.form.address = selectedCustomer.address || '';
                this.form.deliveryAddress = selectedCustomer.delivery_address || '';
                
                // Note: customerInfo is not accessible from this component
                // We'll use the event dispatch to update the main order cart
                
                // Dispatch event to update the main order cart
                window.dispatchEvent(new CustomEvent('customer-saved', {
                    detail: {
                        customerName: selectedCustomer.name || '',
                        customerPhone: selectedCustomer.phone || '',
                        address: selectedCustomer.address || '',
                        deliveryAddress: selectedCustomer.delivery_address || '',
                        deliveryFee: 0, // Default delivery fee
                        specialInstructions: '' // Default special instructions
                    }
                }));
                
                // Close customer list modal and customer modal
                this.showCustomerListModal = false;
                this.isOpen = false;
            }
        },
        
        closeCustomerList() {
            this.showCustomerListModal = false;
            this.selectedCustomerId = null;
        }
    }
}

// Order Details Modal component
function orderDetailsModal() {
    return {
        isOpen: false,
        order: null,
        
        init() {
            // Listen for open-order-details event
            window.addEventListener('open-order-details', (e) => {
                const detail = e.detail || {};
                this.open(detail);
            });
        },
        
        open(detail) {
            this.order = detail.order;
            this.isOpen = true;
        },
        
        close() {
            this.isOpen = false;
            this.order = null;
        },
        
        formatDetailedOrderTime(timestamp) {
            return new Date(timestamp).toLocaleString();
        },
        
        getOrderStatusColor(status) {
            const colors = {
                'NEW': 'bg-blue-100 text-blue-800',
                'CONFIRMED': 'bg-yellow-100 text-yellow-800',
                'PREPARING': 'bg-orange-100 text-orange-800',
                'READY': 'bg-green-100 text-green-800',
                'DELIVERED': 'bg-gray-100 text-gray-800',
                'CANCELLED': 'bg-red-100 text-red-800'
            };
            return colors[status] || 'bg-gray-100 text-gray-800';
        },
        
        calculateOrderTotal(order) {
            if (!order.items || !Array.isArray(order.items)) return 0;
            return Math.round(order.items.reduce((total, item) => {
                return total + (item.price * item.qty);
            }, 0) * 100) / 100;
        }
    }
}

// Orders Modal component
function ordersModal() {
    return {
        isOpen: false,
        loading: false,
        orders: [],
        apiBase: '',
        outletId: {{ $outletId ?? 1 }},
        shiftId: null,
        
        init() {
            // Build API base depending on path-based vs subdomain routing
            const host = window.location.host;
            const path = window.location.pathname;
            const segments = path.replace(/^\/+|\/+$/g, '').split('/');
            const isPathBased = host.indexOf('.') === -1 || segments[0] !== 'pos';
            const tenantSlug = (isPathBased && segments.length > 0) ? segments[0] : null;
            this.apiBase = isPathBased && tenantSlug ? `/${tenantSlug}/pos/api` : `/pos/api`;
            
            // Listen for open-orders-modal event
            window.addEventListener('open-orders-modal', (e) => {
                const detail = e.detail || {};
                this.open(detail);
            });
        },
        
        open(detail) {
            this.shiftId = detail.shiftId;
            this.isOpen = true;
            this.loadOrders();
        },
        
        close() {
            this.isOpen = false;
            this.orders = [];
            this.shiftId = null;
        },
        
        async loadOrders() {
            if (!this.shiftId) return;
            
            this.loading = true;
            try {
                const response = await fetch(`${this.apiBase}/orders/current-shift?outlet_id=${this.outletId}&shift_id=${this.shiftId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.orders = data || [];
                } else {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.error || `HTTP ${response.status}: Failed to load orders`);
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                this.orders = [];
                alert('Error loading orders: ' + error.message);
            } finally {
                this.loading = false;
            }
        },
        
        async refreshOrders() {
            await this.loadOrders();
        },
        
        formatOrderTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));
            
            if (diffInMinutes < 1) {
                return 'Just now';
            } else if (diffInMinutes < 60) {
                return `${diffInMinutes}m ago`;
            } else if (diffInMinutes < 1440) { // Less than 24 hours
                const hours = Math.floor(diffInMinutes / 60);
                return `${hours}h ago`;
            } else {
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
        },
        
        getOrderStatusColor(status) {
            const colors = {
                'NEW': 'bg-blue-100 text-blue-800',
                'CONFIRMED': 'bg-yellow-100 text-yellow-800',
                'PREPARING': 'bg-orange-100 text-orange-800',
                'READY': 'bg-green-100 text-green-800',
                'DELIVERED': 'bg-gray-100 text-gray-800',
                'CANCELLED': 'bg-red-100 text-red-800'
            };
            return colors[status] || 'bg-gray-100 text-gray-800';
        },
        
        calculateOrderTotal(order) {
            if (!order.items || !Array.isArray(order.items)) return 0;
            return Math.round(order.items.reduce((total, item) => {
                return total + (item.price * item.qty);
            }, 0) * 100) / 100;
        },
        
        viewOrderDetails(order) {
            // Dispatch event to open order details modal
            window.dispatchEvent(new CustomEvent('open-order-details', {
                detail: { order: order }
            }));
        },
        
    }
}

// Product Visibility Modal component
function productVisibilityModal() {
    return {
        isOpen: false,
        allProducts: [],
        searchQuery: '',
        showInactive: false,
        apiBase: '',
        
        init() {
            // Use the same API base construction as the main POS component
            const host = window.location.host;
            const path = window.location.pathname;
            const segments = path.replace(/^\/+|\/+$/g, '').split('/');
            const isPathBased = host.indexOf('.') === -1 || segments[0] !== 'pos';
            const tenantSlug = (isPathBased && segments.length > 0) ? segments[0] : null;
            this.apiBase = isPathBased && tenantSlug ? `/${tenantSlug}/pos/api` : `/pos/api`;
            
            console.log('Product visibility modal init:');
            console.log('- Host:', host);
            console.log('- Path:', path);
            console.log('- Segments:', segments);
            console.log('- Is path based:', isPathBased);
            console.log('- Tenant slug:', tenantSlug);
            console.log('- API base:', this.apiBase);
            
            // Listen for open-product-visibility-modal event
            window.addEventListener('open-product-visibility-modal', (e) => {
                console.log('Product visibility modal event received');
                this.open();
            });
        },
        
        open() {
            console.log('Opening product visibility modal...');
            this.isOpen = true;
            
            // Try to get products from main POS component first
            const posElement = document.querySelector('[x-data*="posData"]');
            if (posElement && posElement._x_dataStack) {
                const posData = posElement._x_dataStack[0];
                if (posData && posData.items && posData.items.length > 0) {
                    console.log('Found products in main POS component:', posData.items.length);
                    this.allProducts = posData.items;
                    return;
                }
            }
            
            // If no products in main POS, load from API
            this.loadAllProducts();
        },
        
        close() {
            this.isOpen = false;
            this.allProducts = [];
            this.searchQuery = '';
            this.showInactive = false;
        },
        
        async loadAllProducts() {
            try {
                // Use the correct tenant endpoint (without /api)
                const tenantBase = this.apiBase.replace('/pos/api', '');
                const url = `${tenantBase}/items`;
                console.log('Loading products from URL:', url);
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                console.log('API Response status:', response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Raw API response:', data);
                    this.allProducts = data;
                    console.log('Loaded products count:', this.allProducts.length);
                    console.log('Products data:', this.allProducts);
                } else {
                    const errorText = await response.text();
                    console.error('Failed to load products:', response.status, response.statusText);
                    console.error('Error response body:', errorText);
                    this.allProducts = [];
                }
            } catch (error) {
                console.error('Error loading products:', error);
                this.allProducts = [];
            }
        },
        
        get visibleProducts() {
            return this.allProducts.filter(p => p.is_active);
        },
        
        get filteredProducts() {
            let filtered = this.allProducts;
            console.log('Filtering products. Total:', this.allProducts.length, 'Search:', this.searchQuery, 'Show inactive:', this.showInactive);
            console.log('All products array:', this.allProducts);
            
            // Filter by search query
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(p => 
                    p.name.toLowerCase().includes(query) ||
                    (p.sku && p.sku.toLowerCase().includes(query))
                );
                console.log('After search filter:', filtered.length);
            }
            
            // Filter by active status
            if (!this.showInactive) {
                filtered = filtered.filter(p => p.is_active);
                console.log('After active filter:', filtered.length);
            }
            
            console.log('Final filtered products count:', filtered.length);
            console.log('Final filtered products:', filtered);
            return filtered;
        },
        
        async toggleProductVisibility(productId, isActive) {
            console.log('=== TOGGLE PRODUCT VISIBILITY DEBUG ===');
            console.log('Product ID:', productId);
            console.log('New active status:', isActive);
            
            try {
                const product = this.allProducts.find(p => p.id === productId);
                if (!product) {
                    console.error('Product not found in allProducts array');
                    console.log('Available products:', this.allProducts.map(p => ({ id: p.id, name: p.name })));
                    return;
                }
                
                console.log('Found product:', product.name, 'Current active status:', product.is_active);
                
                // Use the correct tenant endpoint (without /api)
                const tenantBase = this.apiBase.replace('/pos/api', '');
                const url = `${tenantBase}/items/${productId}`;
                console.log('API Base:', this.apiBase);
                console.log('Tenant Base:', tenantBase);
                console.log('Update URL:', url);
                
                const requestBody = {
                    ...product,
                    is_active: isActive
                };
                console.log('Request body:', requestBody);
                
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(requestBody)
                });
                
                console.log('Update response status:', response.status);
                console.log('Update response headers:', response.headers);
                
                if (response.ok) {
                    const updatedProduct = await response.json();
                    console.log('Updated product from server:', updatedProduct);
                    
                    // Update local product
                    const index = this.allProducts.findIndex(p => p.id === productId);
                    if (index !== -1) {
                        this.allProducts[index].is_active = isActive;
                        console.log('Updated local product array');
                    } else {
                        console.error('Could not find product in local array to update');
                    }
                    
                    // Refresh main POS items
                    console.log('Calling refreshMainPOSItems...');
                    this.refreshMainPOSItems();
                    console.log('Product updated successfully');
                } else {
                    const errorText = await response.text();
                    console.error('Update failed - Status:', response.status);
                    console.error('Update failed - Response:', errorText);
                    alert('Error updating product: ' + response.status + ' - ' + errorText);
                }
            } catch (error) {
                console.error('Exception in toggleProductVisibility:', error);
                console.error('Error stack:', error.stack);
                alert('Error updating product: ' + error.message);
            }
            
            console.log('=== END TOGGLE DEBUG ===');
        },
        
        refreshMainPOSItems() {
            console.log('=== REFRESH MAIN POS ITEMS DEBUG ===');
            console.log('Attempting to refresh main POS items...');
            
            // Method 1: Try to find the main POS component by Alpine data
            console.log('Method 1: Looking for [x-data*="posRegister"]');
            const posElement = document.querySelector('[x-data*="posRegister"]');
            console.log('Found posElement:', posElement);
            
            if (posElement) {
                console.log('posElement._x_dataStack:', posElement._x_dataStack);
                if (posElement._x_dataStack && posElement._x_dataStack.length > 0) {
                    const posData = posElement._x_dataStack[0];
                    console.log('posData:', posData);
                    console.log('posData.loadItems type:', typeof posData.loadItems);
                    
                    if (posData && typeof posData.loadItems === 'function') {
                        console.log('SUCCESS: Calling loadItems on main POS component');
                        try {
                            posData.loadItems();
                            console.log('loadItems called successfully');
                            return;
                        } catch (e) {
                            console.error('Error calling loadItems:', e);
                        }
                    } else {
                        console.log('loadItems function not found or not a function');
                    }
                } else {
                    console.log('_x_dataStack is empty or undefined');
                }
            } else {
                console.log('posElement not found');
            }
            
            // Method 2: Try alternative selector
            console.log('Method 2: Looking for [x-data*="posData"]');
            const posElement2 = document.querySelector('[x-data*="posData"]');
            console.log('Found posElement2:', posElement2);
            
            if (posElement2 && posElement2._x_dataStack) {
                const posData = posElement2._x_dataStack[0];
                if (posData && typeof posData.loadItems === 'function') {
                    console.log('SUCCESS: Calling loadItems on main POS component (alt selector)');
                    try {
                        posData.loadItems();
                        console.log('loadItems called successfully (alt)');
                        return;
                    } catch (e) {
                        console.error('Error calling loadItems (alt):', e);
                    }
                }
            }
            
            // Method 3: Try to find by Alpine store
            console.log('Method 3: Checking Alpine store');
            if (window.Alpine) {
                console.log('Alpine is available');
                if (window.Alpine.store) {
                    console.log('Alpine.store is available');
                    try {
                        const posStore = window.Alpine.store('pos');
                        console.log('posStore:', posStore);
                        if (posStore && typeof posStore.loadItems === 'function') {
                            console.log('SUCCESS: Calling loadItems on Alpine store');
                            posStore.loadItems();
                            console.log('loadItems called successfully (store)');
                            return;
                        } else {
                            console.log('posStore not found or loadItems not a function');
                        }
                    } catch (e) {
                        console.log('Error accessing Alpine store:', e);
                    }
                } else {
                    console.log('Alpine.store not available');
                }
            } else {
                console.log('Alpine not available');
            }
            
            // Method 4: Dispatch a custom event for the main POS to listen to
            console.log('Method 4: Dispatching refresh event');
            try {
                window.dispatchEvent(new CustomEvent('refresh-pos-items'));
                console.log('Refresh event dispatched successfully');
            } catch (e) {
                console.error('Error dispatching refresh event:', e);
            }
            
            // Method 5: Try to access global posRegister function
            console.log('Method 5: Checking global posRegister function');
            if (typeof window.posRegister === 'function') {
                console.log('Global posRegister function found');
            } else {
                console.log('Global posRegister function not found');
            }
            
            // Method 6: Try to find any element with x-data
            console.log('Method 6: Looking for any x-data elements');
            const allXDataElements = document.querySelectorAll('[x-data]');
            console.log('Found x-data elements:', allXDataElements.length);
            allXDataElements.forEach((el, index) => {
                console.log(`Element ${index}:`, el, 'x-data:', el.getAttribute('x-data'));
            });
            
            console.log('=== END REFRESH DEBUG ===');
        }
    }
}


// Helper function to get cookie value
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}
</script>

    <!-- Shift Checkout Modal - Clean Alpine.js Pattern -->
    <div
        x-data="checkoutModal()"
        x-init="init()"
        x-on:open-checkout.window="open()"
        x-on:keydown.escape.window="close()"
        class="relative z-50"
    >
        <!-- Backdrop -->
        <div
            x-show="isOpen"
            x-transition.opacity
            class="fixed inset-0 bg-black/50"
            aria-hidden="true"
        ></div>

        <!-- Panel -->
        <div
            x-show="isOpen"
            x-transition
            x-trap.noscroll="isOpen"
            class="fixed inset-0 flex items-center justify-center p-3 sm:p-4"
            role="dialog"
            aria-modal="true"
        >
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-3 sm:p-4 border-b">
                    <h2 class="text-base sm:text-lg font-semibold">Shift Checkout & Logout</h2>
                    <button class="p-2 text-lg" @click="close()" aria-label="Close">
                        ✕
                    </button>
                </div>

                <div class="p-3 sm:p-4 space-y-4">
                    <!-- Shift Information -->
                    <div class="bg-blue-50 rounded-lg p-3 sm:p-4 mb-4">
                        <h4 class="font-semibold text-blue-900 mb-2 text-sm sm:text-base">Current Shift</h4>
                        <div class="text-xs sm:text-sm text-blue-700">
                            <div x-show="shiftInfo" class="space-y-1">
                                <div class="flex justify-between">
                                    <span>Shift:</span>
                                    <span class="font-medium" x-text="shiftInfo?.name || 'Unknown'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Time:</span>
                                    <span class="font-medium" x-text="shiftInfo?.description || 'Unknown'"></span>
                                </div>
                            </div>
                            <div x-show="!shiftInfo" class="text-gray-500">
                                No shift information available
                            </div>
                        </div>
                    </div>

                    <!-- Shift Summary -->
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                        <h4 class="font-semibold text-gray-900 mb-3 text-sm sm:text-base">Shift Summary</h4>
                        
                        <!-- Loading State -->
                        <div x-show="loading" class="flex items-center justify-center py-4">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900"></div>
                            <span class="ml-2 text-sm text-gray-600">Loading shift data...</span>
                        </div>
                        
                        <!-- Summary Data -->
                        <div x-show="!loading" class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Sales:</span>
                                <span class="font-semibold" x-text="'₹' + (shiftSummary?.total_sales || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Orders:</span>
                                <span class="font-semibold" x-text="shiftSummary?.total_orders || 0"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Cash Sales:</span>
                                <span class="font-semibold" x-text="'₹' + (shiftSummary?.cash_sales || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Card Sales:</span>
                                <span class="font-semibold" x-text="'₹' + (shiftSummary?.card_sales || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">UPI Sales:</span>
                                <span class="font-semibold" x-text="'₹' + (shiftSummary?.upi_sales || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Opening Float:</span>
                                <span class="font-semibold" x-text="'₹' + (shiftSummary?.opening_float || 0)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Actual Cash Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Actual Cash in Drawer</label>
                        <input type="number" x-model="actualCash" step="0.01" min="0" 
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base"
                               placeholder="0.00">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-2 p-3 sm:p-4 border-t">
                    <button class="w-full sm:w-auto px-4 py-2.5 rounded border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm sm:text-base transition-colors" @click="close()">Cancel</button>
                    <button class="w-full sm:w-auto px-4 py-2.5 rounded bg-red-600 text-white hover:bg-red-700 text-sm sm:text-base transition-colors" @click="submit()">Close Shift & Logout</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Modal -->
    <div
        x-data="customerModal()"
        x-on:open-customer.window="open($event.detail)"
        x-on:keydown.escape.window="close()"
        class="relative z-50"
    >
        <!-- Backdrop -->
        <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-black/50" aria-hidden="true"></div>

        <!-- Panel -->
        <div x-show="isOpen" x-transition x-trap.noscroll="isOpen" class="fixed inset-0 flex items-center justify-center p-3 sm:p-4" role="dialog" aria-modal="true">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-3 sm:p-4 border-b">
                    <h2 class="text-base sm:text-lg font-semibold">Delivery Details</h2>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <button @click="openCustomerList()" 
                                class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 underline">
                            View Customers
                        </button>
                        <button class="p-2 text-lg" @click="close()" aria-label="Close">✕</button>
                    </div>
                </div>

                <div class="p-3 sm:p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Customer Name</label>
                        <input type="text" x-model="form.customerName" placeholder="Name" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" x-model="form.customerPhone" 
                               placeholder="+91..." 
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                        <textarea x-model="form.address" rows="3" placeholder="Enter customer address" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Delivery Address</label>
                        <textarea x-model="form.deliveryAddress" rows="3" placeholder="Enter delivery address" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Delivery Fee</label>
                        <input type="number" x-model.number="form.deliveryFee" step="0.01" min="0" placeholder="0.00" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-2 p-3 sm:p-4 border-t">
                    <button class="w-full sm:w-auto px-4 py-2.5 rounded border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm transition-colors" @click="close()">Cancel</button>
                    <button class="w-full sm:w-auto px-4 py-2.5 rounded bg-blue-600 text-white hover:bg-blue-700 text-sm transition-colors" @click="save()">Save</button>
                </div>
            </div>
        </div>

        <!-- Customer List Modal -->
        <div x-show="showCustomerListModal" x-transition.opacity class="fixed inset-0 bg-black/50" aria-hidden="true"></div>
        
        <div x-show="showCustomerListModal" x-transition x-trap.noscroll="showCustomerListModal" class="fixed inset-0 flex items-center justify-center p-3 sm:p-4" role="dialog" aria-modal="true">
            <div class="w-full max-w-2xl rounded-2xl bg-white shadow-xl max-h-[90vh] overflow-hidden flex flex-col">
                <div class="flex items-center justify-between p-3 sm:p-4 border-b">
                    <h2 class="text-base sm:text-lg font-semibold">Select Customer</h2>
                    <button class="p-2 text-lg" @click="closeCustomerList()" aria-label="Close">✕</button>
    </div>

                <div class="p-3 sm:p-4 flex-1 overflow-hidden">
                    <div class="max-h-80 sm:max-h-96 overflow-y-auto">
                        <template x-for="customer in allCustomers" :key="customer.id">
                            <div class="flex items-center p-2 sm:p-3 border border-gray-200 rounded-lg mb-2 hover:bg-gray-50 cursor-pointer"
                                 @click="selectCustomer(customer.id)"
                                 :class="selectedCustomerId === customer.id ? 'bg-blue-50 border-blue-300' : ''">
                                <input type="radio" 
                                       :id="'customer-' + customer.id"
                                       :value="customer.id"
                                       x-model="selectedCustomerId"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label :for="'customer-' + customer.id" class="ml-3 flex-1 cursor-pointer">
                                    <div class="font-medium text-xs sm:text-sm" x-text="customer.name || 'No Name'"></div>
                                    <div class="text-xs text-gray-600" x-text="customer.phone"></div>
                                    <div class="text-xs text-gray-500" x-text="customer.address || 'No Address'"></div>
                                </label>
                            </div>
                        </template>
                        
                        <div x-show="allCustomers.length === 0" class="text-center py-8 text-gray-500 text-sm">
                            No customers found
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-4">
                        <button @click="closeCustomerList()" 
                                class="w-full sm:flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button @click="confirmCustomerSelection()" 
                                class="w-full sm:flex-1 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Modal -->
    <div
        x-data="ordersModal()"
        x-on:keydown.escape.window="close()"
        class="relative z-50"
    >
        <!-- Backdrop -->
        <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-black/50" aria-hidden="true"></div>

        <!-- Panel -->
        <div x-show="isOpen" x-transition x-trap.noscroll="isOpen" class="fixed inset-0 flex items-center justify-center p-3 sm:p-4" role="dialog" aria-modal="true">
            <div class="w-full max-w-6xl rounded-2xl bg-white shadow-xl max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between p-3 sm:p-4 border-b">
                    <h2 class="text-base sm:text-lg font-semibold">Recent Orders</h2>
                    <button class="p-2 text-lg" @click="close()" aria-label="Close">✕</button>
                </div>

                <div class="flex-1 overflow-y-auto p-3 sm:p-4">
                    <!-- Loading State -->
                    <div x-show="loading" class="flex items-center justify-center py-8">
                        <div class="animate-spin rounded-full h-6 w-6 sm:h-8 sm:w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600 text-sm sm:text-base">Loading orders...</span>
                    </div>

                    <!-- Orders List -->
                    <div x-show="!loading" class="space-y-2">
                        <!-- Table Header - Hidden on mobile, shown on larger screens -->
                        <div class="hidden lg:grid bg-gray-50 rounded-lg p-3 grid-cols-12 gap-4 text-sm font-medium text-gray-700">
                            <div class="col-span-2">Order #</div>
                            <div class="col-span-2">Time</div>
                            <div class="col-span-2">Status</div>
                            <div class="col-span-2">Customer/Table</div>
                            <div class="col-span-2">Items</div>
                            <div class="col-span-1 text-right">Total</div>
                            <div class="col-span-1 text-center">Actions</div>
                        </div>
                        
                        <!-- Orders Rows -->
                        <template x-for="order in orders" :key="order.id">
                            <!-- Desktop Layout -->
                            <div class="hidden lg:grid border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors grid-cols-12 gap-4 items-center text-sm">
                                <!-- Order ID -->
                                <div class="col-span-2 font-semibold text-gray-900">
                                    #<span x-text="order.id"></span>
                                </div>
                                
                                <!-- Time -->
                                <div class="col-span-2 text-gray-600">
                                    <span x-text="formatOrderTime(order.created_at)"></span>
                                </div>
                                
                                <!-- Status -->
                                <div class="col-span-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                          :class="getOrderStatusColor(order.state)" 
                                          x-text="order.state"></span>
                                </div>
                                
                                <!-- Customer/Table -->
                                <div class="col-span-2 text-gray-600">
                                    <div x-show="order.customer_name" class="font-medium" x-text="order.customer_name"></div>
                                    <div x-show="order.table_no" class="text-xs" x-text="'Table: ' + order.table_no"></div>
                                    <div x-show="!order.customer_name && !order.table_no" class="text-gray-400">Walk-in</div>
                                    <div x-show="order.source === 'mobile_qr'" class="inline-flex items-center mt-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Mobile QR
                                    </div>
                                </div>
                                
                                <!-- Items Summary -->
                                <div class="col-span-2 text-gray-600">
                                    <div x-text="(order.items?.length || 0) + ' items'"></div>
                                    <div class="text-xs text-gray-500" x-text="order.payment_method?.toUpperCase() || 'CASH'"></div>
                                </div>
                                
                                <!-- Total -->
                                <div class="col-span-1 text-right">
                                    <div class="font-bold text-green-600" x-text="'₹' + calculateOrderTotal(order).toFixed(2)"></div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="col-span-1 text-center">
                                    <button @click="viewOrderDetails(order)" 
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium underline">
                                        View
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Mobile Layout -->
                            <div class="lg:hidden border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors space-y-2">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 text-sm">
                                            #<span x-text="order.id"></span>
                                        </div>
                                        <div class="text-xs text-gray-600" x-text="formatOrderTime(order.created_at)"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-green-600 text-sm" x-text="'₹' + calculateOrderTotal(order).toFixed(2)"></div>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                              :class="getOrderStatusColor(order.state)" 
                                              x-text="order.state"></span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center text-xs text-gray-600">
                                    <div>
                                        <div x-show="order.customer_name" class="font-medium" x-text="order.customer_name"></div>
                                        <div x-show="order.table_no" x-text="'Table: ' + order.table_no"></div>
                                        <div x-show="!order.customer_name && !order.table_no" class="text-gray-400">Walk-in</div>
                                        <div x-show="order.source === 'mobile_qr'" class="inline-flex items-center mt-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            Mobile QR
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div x-text="(order.items?.length || 0) + ' items'"></div>
                                        <div class="text-gray-500" x-text="order.payment_method?.toUpperCase() || 'CASH'"></div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button @click="viewOrderDetails(order)" 
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium underline">
                                        View
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- No Orders State -->
                        <div x-show="!loading && orders.length === 0" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <p class="text-base sm:text-lg font-medium">No orders found</p>
                            <p class="text-xs sm:text-sm">No orders have been created in this shift yet.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-2 p-3 sm:p-4 border-t">
                    <button class="w-full sm:w-auto px-4 py-2.5 rounded border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm transition-colors" @click="close()">Close</button>
                    <button class="w-full sm:w-auto px-4 py-2.5 rounded bg-blue-600 text-white hover:bg-blue-700 text-sm transition-colors" @click="refreshOrders()" :disabled="loading">
                        <span x-show="!loading">Refresh</span>
                        <span x-show="loading">Refreshing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div
        x-data="orderDetailsModal()"
        x-on:keydown.escape.window="close()"
        class="relative z-50"
    >
        <!-- Backdrop -->
        <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-black/50" aria-hidden="true"></div>

        <!-- Panel -->
        <div x-show="isOpen" x-transition x-trap.noscroll="isOpen" class="fixed inset-0 flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div class="w-full max-w-2xl rounded-2xl bg-white shadow-xl max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between p-4 border-b">
                    <h2 class="text-lg font-semibold">Order Details #<span x-text="order?.id"></span></h2>
                    <button class="p-2" @click="close()" aria-label="Close">✕</button>
                </div>

                <div class="flex-1 overflow-y-auto p-4">
                    <template x-if="order">
                        <div class="space-y-6">
                            <!-- Order Header -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-2">Order Information</h3>
                                        <div class="space-y-1 text-sm">
                                            <div><span class="font-medium">Order #:</span> <span x-text="order.id"></span></div>
                                            <div><span class="font-medium">Created:</span> <span x-text="formatDetailedOrderTime(order.created_at)"></span></div>
                                            <div><span class="font-medium">Status:</span> 
                                                <span class="px-2 py-1 rounded-full text-xs font-medium ml-2" 
                                                      :class="getOrderStatusColor(order.state)" 
                                                      x-text="order.state"></span>
                                            </div>
                                            <div><span class="font-medium">Payment:</span> <span x-text="order.payment_method?.toUpperCase() || 'CASH'"></span></div>
                                            <div x-show="order.source === 'mobile_qr'">
                                                <span class="font-medium">Source:</span> 
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                    Mobile QR
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-2">Customer Information</h3>
                                        <div class="space-y-1 text-sm">
                                            <div x-show="order.customer_name">
                                                <span class="font-medium">Name:</span> <span x-text="order.customer_name"></span>
                                            </div>
                                            <div x-show="order.customer_phone">
                                                <span class="font-medium">Phone:</span> <span x-text="order.customer_phone"></span>
                                            </div>
                                            <div x-show="order.table || order.table_no">
                                                <span class="font-medium">Table:</span> 
                                                <span x-text="order.table ? order.table.name : order.table_no"></span>
                                            </div>
                                            <div x-show="order.customer_address">
                                                <span class="font-medium">Address:</span> <span x-text="order.customer_address"></span>
                                            </div>
                                            <div x-show="order.delivery_address">
                                                <span class="font-medium">Delivery Address:</span> <span x-text="order.delivery_address"></span>
                                            </div>
                                            <div x-show="!order.customer_name && !order.table && !order.table_no">
                                                <span class="text-gray-500 italic">Walk-in Customer</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Special Instructions -->
                            <div x-show="order.special_instructions" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <h4 class="font-semibold text-yellow-800 mb-2">Special Instructions</h4>
                                <p class="text-sm text-yellow-700" x-text="order.special_instructions"></p>
                            </div>

                            <!-- Order Items -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-4">Order Items</h4>
                                <div class="space-y-3">
                                    <template x-for="item in order.items" :key="item.id">
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900" x-text="item.item?.name || 'Unknown Item'"></div>
                                                <div class="text-sm text-gray-500" x-text="'Price: ₹' + item.price"></div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-medium" x-text="'Qty: ' + item.qty"></div>
                                                <div class="text-sm text-gray-600" x-text="'₹' + (item.qty * item.price).toFixed(2)"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Order Total -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                                        <span class="text-xl font-bold text-green-600" x-text="'₹' + calculateOrderTotal(order).toFixed(2)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Type Information -->
                            <div x-show="order.orderType" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-800 mb-2">Order Type</h4>
                                <div class="text-sm text-blue-700">
                                    <div><span class="font-medium">Type:</span> <span x-text="order.orderType?.name || 'N/A'"></span></div>
                                    <div x-show="order.mode"><span class="font-medium">Mode:</span> <span x-text="order.mode"></span></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end gap-2 p-4 border-t">
                    <button class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50" @click="close()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Visibility Modal -->
    <div
        x-data="productVisibilityModal()"
        x-on:keydown.escape.window="close()"
        class="relative z-50"
    >
        <!-- Backdrop -->
        <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-black/50" aria-hidden="true"></div>

        <!-- Panel -->
        <div x-show="isOpen" x-transition x-trap.noscroll="isOpen" class="fixed inset-0 flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div class="w-full max-w-4xl rounded-2xl bg-white shadow-xl max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between p-4 border-b">
                    <h2 class="text-lg font-semibold">Product Visibility Control</h2>
                    <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" @click="close()" aria-label="Close">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4">
                    <!-- Search and Filter -->
                    <div class="mb-4 space-y-3">
                        <div>
                            <input 
                                type="text" 
                                x-model="searchQuery"
                                placeholder="Search products..." 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            >
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="showInactive" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-sm text-gray-700">Show inactive products</span>
                            </label>
                            <div class="text-sm text-gray-600">
                                <span x-text="visibleProducts.length"></span> products visible in POS
                            </div>
                        </div>
                    </div>

                    <!-- Products List -->
                    <div class="space-y-2">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-600 font-semibold text-sm" x-text="product.is_veg ? 'V' : 'N'"></span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900" x-text="product.name"></h3>
                                        <p class="text-sm text-gray-500" x-text="'₹' + (product.price || 0)"></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                          x-text="product.is_active ? 'Visible' : 'Hidden'">
                                    </span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               :checked="product.is_active"
                                               @change="toggleProductVisibility(product.id, $event.target.checked)"
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredProducts.length === 0" class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p>No products found</p>
                    </div>
                </div>

                <div class="flex justify-end gap-2 p-4 border-t">
                    <button class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50" @click="close()">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

