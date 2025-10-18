@extends('layouts.tenant')

@section('title', 'Orders')

@section('content')
<div class="space-y-6" x-data="ordersManager()">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-dm">Orders</h1>
                <p class="text-gray-600 mt-2">View and manage all your restaurant orders</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Type</label>
                <select x-model="filters.order_type" @change="loadOrders()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Types</option>
                    @foreach($orderTypes as $orderType)
                        <option value="{{ $orderType->id }}">{{ $orderType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="filters.status" @change="loadOrders()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="NEW">New</option>
                    <option value="IN_KITCHEN">In Kitchen</option>
                    <option value="READY">Ready</option>
                    <option value="SERVED">Served</option>
                    <option value="BILLED">Billed</option>
                    <option value="CLOSED">Closed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" x-model="filters.date_from" @change="loadOrders()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" x-model="filters.date_to" @change="loadOrders()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    {{-- Orders Content --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Orders List</h2>
            <div class="flex items-center space-x-2">
                <button @click="loadOrders()" :disabled="loading" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                    <svg class="w-4 h-4 mr-2" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="text-center py-8">
            <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-blue-500 bg-white transition ease-in-out duration-150 cursor-not-allowed">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading orders...
            </div>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && orders.length === 0" class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Orders Found</h3>
            <p class="text-gray-600 mb-6">There are no orders matching your current filters.</p>
        </div>



        <!-- Simple Inline Modal (Fallback) -->
        <div x-show="showOrderModal" class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Order Details</h3>
                    <button @click="showOrderModal = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                
                <div x-show="selectedOrder" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <strong>Order ID:</strong> <span x-text="selectedOrder?.id"></span>
                        </div>
                        <div>
                            <strong>Status:</strong> <span x-text="selectedOrder?.state"></span>
                        </div>
                        <div>
                            <strong>Customer:</strong> <span x-text="selectedOrder?.customer_name || 'Walk-in'"></span>
                        </div>
                        <div>
                            <strong>Mode:</strong> <span x-text="selectedOrder?.mode || 'N/A'"></span>
                        </div>
                        <div>
                            <strong>Order Type:</strong> <span x-text="selectedOrder?.orderType?.name || 'N/A'"></span>
                        </div>
                        <div>
                            <strong>Table:</strong> <span x-text="selectedOrder?.table ? selectedOrder.table.name : (selectedOrder?.table_no || 'N/A')"></span>
                        </div>
                        <div>
                            <strong>Total:</strong> ₹<span x-text="parseFloat(selectedOrder?.total || 0).toFixed(2)"></span>
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <div class="mt-4" x-show="selectedOrder?.items && selectedOrder.items.length > 0">
                        <h4 class="font-semibold text-gray-900 mb-2">Order Items:</h4>
                        <div class="space-y-2">
                            <template x-for="item in selectedOrder?.items || []" :key="item.id">
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <div>
                                        <span x-text="item.item?.name || 'Unknown Item'"></span>
                                        <span class="text-sm text-gray-500"> x<span x-text="item.qty"></span></span>
                                    </div>
                                    <div class="font-medium">
                                        ₹<span x-text="(item.qty * item.price).toFixed(2)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <strong>Created:</strong> <span x-text="formatDate(selectedOrder?.created_at)"></span>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button @click="showOrderModal = false" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div x-show="!loading && orders.length > 0" class="space-y-4">
            <template x-for="order in orders" :key="order.id">
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900" x-text="'Order #' + order.id"></h3>
                                    <p class="text-sm text-gray-600" x-text="order.order_type?.name || 'Unknown Type'"></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          :class="getStatusClass(order.state)" 
                                          x-text="order.state"></span>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center space-x-6 text-sm text-gray-600">
                                <span x-text="'₹' + parseFloat(order.total || 0).toFixed(2)"></span>
                                <span x-text="formatDate(order.created_at)"></span>
                                <span x-text="order.customer_name || 'Walk-in Customer'"></span>
                                <span x-text="order.table_no ? 'Table: ' + order.table_no : ''"></span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="viewOrder(order)" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                                View
                            </button>
                            <button @click="updateOrderStatus(order)" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <div x-show="!loading && orders.length > 0" class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span x-text="pagination.from || 0"></span> to <span x-text="pagination.to || 0"></span> of <span x-text="pagination.total || 0"></span> results
            </div>
            <div class="flex items-center space-x-2">
                <button @click="previousPage()" :disabled="!pagination.prev_page_url" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <button @click="nextPage()" :disabled="!pagination.next_page_url" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function ordersManager() {
    return {
        orders: [],
        loading: false,
        pagination: {},
        showOrderModal: false,
        selectedOrder: null,
        filters: {
            order_type: '',
            status: '',
            date_from: '',
            date_to: ''
        },

        init() {
            console.log('OrdersManager initialized');
            console.log('Initial modal state:', this.showOrderModal);
            this.loadOrders();
        },

        async loadOrders() {
            console.log('Loading orders...');
            this.loading = true;
            try {
                const params = new URLSearchParams();
                Object.keys(this.filters).forEach(key => {
                    if (this.filters[key]) {
                        params.append(key, this.filters[key]);
                    }
                });

                const url = `/{{ $tenant->slug }}/api/orders?${params.toString()}`;
                console.log('Fetching from URL:', url);

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('Orders loaded:', data);
                    console.log('Data structure:', {
                        hasData: !!data.data,
                        dataLength: data.data ? data.data.length : 0,
                        isArray: Array.isArray(data.data),
                        fullData: data
                    });
                    this.orders = data.data || data;
                    this.pagination = data;
                } else {
                    console.error('Failed to load orders:', response.status, response.statusText);
                }
            } catch (error) {
                console.error('Error loading orders:', error);
            } finally {
                this.loading = false;
            }
        },

        getStatusClass(status) {
            const classes = {
                'NEW': 'bg-yellow-100 text-yellow-800',
                'IN_KITCHEN': 'bg-orange-100 text-orange-800',
                'READY': 'bg-green-100 text-green-800',
                'SERVED': 'bg-blue-100 text-blue-800',
                'BILLED': 'bg-purple-100 text-purple-800',
                'CLOSED': 'bg-gray-100 text-gray-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleString();
        },

        async viewOrder(order) {
            // If order doesn't have full data, fetch it
            if (!order.table && order.id) {
                try {
                    const response = await fetch(`/{{ $tenant->slug }}/api/orders/${order.id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        const fullOrder = await response.json();
                        this.selectedOrder = fullOrder;
                    } else {
                        this.selectedOrder = order;
                    }
                } catch (error) {
                    this.selectedOrder = order;
                }
            } else {
                this.selectedOrder = order;
            }
            
            this.showOrderModal = true;
        },

        updateOrderStatus(order) {
            // Implement update status functionality
        },

        previousPage() {
            if (this.pagination.prev_page_url) {
                this.loadPage(this.pagination.prev_page_url);
            }
        },

        nextPage() {
            if (this.pagination.next_page_url) {
                this.loadPage(this.pagination.next_page_url);
            }
        },

        async loadPage(url) {
            this.loading = true;
            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.orders = data.data || data;
                    this.pagination = data;
                }
            } catch (error) {
                console.error('Error loading page:', error);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

@endsection