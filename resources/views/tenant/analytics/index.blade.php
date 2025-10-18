@extends('layouts.tenant')

@section('title', 'Analytics')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-dm">Analytics Dashboard</h1>
                <p class="text-gray-600 mt-2">Comprehensive insights into your restaurant's performance</p>
            </div>
            <div class="flex items-center gap-4">
                <select id="periodSelect" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-royal-purple focus:border-royal-purple">
                    <option value="7days">Last 7 Days</option>
                    <option value="30days" selected>Last 30 Days</option>
                    <option value="90days">Last 90 Days</option>
                    <option value="1year">Last Year</option>
                </select>
                <button onclick="refreshData()" class="px-4 py-2 bg-royal-purple text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalSales">₹0</p>
                    <p class="text-sm text-green-600" id="salesGrowth">+0%</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalOrders">0</p>
                    <p class="text-sm text-green-600" id="ordersGrowth">+0%</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Avg Order Value</p>
                    <p class="text-2xl font-bold text-gray-900" id="avgOrderValue">₹0</p>
                    <p class="text-sm text-gray-500">Per order</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Top Category</p>
                    <p class="text-lg font-bold text-gray-900" id="topCategory">-</p>
                    <p class="text-sm text-gray-500">Best performer</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Sales Trend Chart --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 font-dm">Sales Trend</h3>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-royal-purple rounded-full"></div>
                    <span class="text-sm text-gray-600">Daily Sales</span>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>

        {{-- Hourly Sales Chart --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 font-dm">Today's Hourly Sales</h3>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-tiffany-blue rounded-full"></div>
                    <span class="text-sm text-gray-600">Hourly Revenue</span>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="hourlyChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Items and Categories --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Top Items --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 font-dm">Top Selling Items</h3>
                <span class="text-sm text-gray-500" id="topItemsPeriod">Last 30 days</span>
            </div>
            <div class="space-y-4" id="topItemsList">
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-royal-purple"></div>
                </div>
            </div>
        </div>

        {{-- Category Performance --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 font-dm">Category Performance</h3>
                <span class="text-sm text-gray-500" id="categoryPeriod">Last 30 days</span>
            </div>
            <div class="space-y-4" id="categoryList">
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-royal-purple"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Type Analytics --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900 font-dm">Order Type Performance</h3>
            <span class="text-sm text-gray-500" id="orderTypePeriod">Last 30 days</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="orderTypeList">
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-royal-purple"></div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let salesChart, hourlyChart;
let currentPeriod = '30days';

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadAllData();
    
    // Period selector change
    document.getElementById('periodSelect').addEventListener('change', function() {
        currentPeriod = this.value;
        loadAllData();
    });
});

function initializeCharts() {
    // Sales Trend Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Sales',
                data: [],
                borderColor: '#6E46AE',
                backgroundColor: 'rgba(110, 70, 174, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Hourly Sales Chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    hourlyChart = new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Hourly Sales',
                data: [],
                backgroundColor: '#00B6B4',
                borderColor: '#00B6B4',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

async function loadAllData() {
    try {
        await Promise.all([
            loadSummaryStats(),
            loadSalesData(),
            loadTopItems(),
            loadCategoryPerformance(),
            loadOrderTypeAnalytics()
        ]);
    } catch (error) {
        console.error('Error loading analytics data:', error);
    }
}

async function loadSummaryStats() {
    try {
        const response = await fetch(`/{{ $tenant->slug }}/analytics/summary-stats?period=${currentPeriod}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            document.getElementById('totalSales').textContent = '₹' + data.total_sales.toLocaleString();
            document.getElementById('totalOrders').textContent = data.total_orders.toLocaleString();
            document.getElementById('avgOrderValue').textContent = '₹' + data.avg_order_value.toLocaleString();
            
            const salesGrowthEl = document.getElementById('salesGrowth');
            const ordersGrowthEl = document.getElementById('ordersGrowth');
            
            salesGrowthEl.textContent = (data.sales_growth >= 0 ? '+' : '') + data.sales_growth.toFixed(1) + '%';
            salesGrowthEl.className = `text-sm ${data.sales_growth >= 0 ? 'text-green-600' : 'text-red-600'}`;
            
            ordersGrowthEl.textContent = (data.orders_growth >= 0 ? '+' : '') + data.orders_growth.toFixed(1) + '%';
            ordersGrowthEl.className = `text-sm ${data.orders_growth >= 0 ? 'text-green-600' : 'text-red-600'}`;
        }
    } catch (error) {
        console.error('Error loading summary stats:', error);
    }
}

async function loadSalesData() {
    try {
        const response = await fetch(`/{{ $tenant->slug }}/analytics/sales-data?period=${currentPeriod}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            // Update sales chart
            const labels = data.daily_sales.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            });
            const salesData = data.daily_sales.map(item => parseFloat(item.total_sales));
            
            salesChart.data.labels = labels;
            salesChart.data.datasets[0].data = salesData;
            salesChart.update();
            
            // Update hourly chart
            const hourlyLabels = data.hourly_sales.map(item => item.hour + ':00');
            const hourlyData = data.hourly_sales.map(item => parseFloat(item.total_sales));
            
            hourlyChart.data.labels = hourlyLabels;
            hourlyChart.data.datasets[0].data = hourlyData;
            hourlyChart.update();
        }
    } catch (error) {
        console.error('Error loading sales data:', error);
    }
}

async function loadTopItems() {
    try {
        const response = await fetch(`/{{ $tenant->slug }}/analytics/top-items?period=${currentPeriod}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            document.getElementById('topItemsPeriod').textContent = getPeriodText(currentPeriod);
            
            const topItemsList = document.getElementById('topItemsList');
            if (data.top_items.length > 0) {
                topItemsList.innerHTML = data.top_items.map((item, index) => `
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-royal-purple text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                ${index + 1}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">${item.name}</h4>
                                <p class="text-sm text-gray-600">${item.total_quantity} sold</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">₹${parseFloat(item.total_revenue).toLocaleString()}</p>
                            <p class="text-sm text-gray-600">${item.order_count} orders</p>
                        </div>
                    </div>
                `).join('');
            } else {
                topItemsList.innerHTML = '<p class="text-gray-500 text-center py-8">No data available</p>';
            }
        }
    } catch (error) {
        console.error('Error loading top items:', error);
    }
}

async function loadCategoryPerformance() {
    try {
        const response = await fetch(`/{{ $tenant->slug }}/analytics/category-performance?period=${currentPeriod}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            document.getElementById('categoryPeriod').textContent = getPeriodText(currentPeriod);
            
            const categoryList = document.getElementById('categoryList');
            if (data.category_performance.length > 0) {
                categoryList.innerHTML = data.category_performance.map((category, index) => `
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-tiffany-blue text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                ${index + 1}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">${category.name}</h4>
                                <p class="text-sm text-gray-600">${category.total_quantity} items sold</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">₹${parseFloat(category.total_revenue).toLocaleString()}</p>
                            <p class="text-sm text-gray-600">${category.order_count} orders</p>
                        </div>
                    </div>
                `).join('');
                
                // Update top category in summary
                if (data.category_performance.length > 0) {
                    document.getElementById('topCategory').textContent = data.category_performance[0].name;
                }
            } else {
                categoryList.innerHTML = '<p class="text-gray-500 text-center py-8">No data available</p>';
            }
        }
    } catch (error) {
        console.error('Error loading category performance:', error);
    }
}

async function loadOrderTypeAnalytics() {
    try {
        const response = await fetch(`/{{ $tenant->slug }}/analytics/order-type-analytics?period=${currentPeriod}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            document.getElementById('orderTypePeriod').textContent = getPeriodText(currentPeriod);
            
            const orderTypeList = document.getElementById('orderTypeList');
            if (data.order_type_analytics.length > 0) {
                orderTypeList.innerHTML = data.order_type_analytics.map(orderType => `
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">${orderType.name}</h4>
                            <div class="w-3 h-3 bg-royal-purple rounded-full"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Orders:</span>
                                <span class="font-semibold">${orderType.orders_count}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Revenue:</span>
                                <span class="font-semibold">₹${parseFloat(orderType.total_sales).toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Avg Value:</span>
                                <span class="font-semibold">₹${parseFloat(orderType.avg_order_value).toLocaleString()}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                orderTypeList.innerHTML = '<p class="text-gray-500 text-center py-8 col-span-full">No data available</p>';
            }
        }
    } catch (error) {
        console.error('Error loading order type analytics:', error);
    }
}

function getPeriodText(period) {
    const periodMap = {
        '7days': 'Last 7 days',
        '30days': 'Last 30 days',
        '90days': 'Last 90 days',
        '1year': 'Last year'
    };
    return periodMap[period] || 'Last 30 days';
}

function refreshData() {
    loadAllData();
}
</script>
@endsection
