@extends('layouts.tenant')

@section('title', 'Restaurant Analytics Dashboard')
@section('description', 'Advanced restaurant analytics and insights dashboard. Track sales trends, monitor performance metrics, analyze customer behavior, and make data-driven business decisions.')
@section('keywords', 'restaurant analytics, business intelligence, sales analytics, performance tracking, customer analytics, restaurant insights, data visualization, business metrics')

@section('content')
<div class="space-y-4 sm:space-y-6 lg:space-y-8">
    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-dm">Analytics Dashboard</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-2">Comprehensive insights into your restaurant's performance</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                <select id="periodSelect" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-royal-purple focus:border-royal-purple text-sm sm:text-base">
                    <option value="7days">Last 7 Days</option>
                    <option value="30days" selected>Last 30 Days</option>
                    <option value="90days">Last 90 Days</option>
                    <option value="1year">Last Year</option>
                </select>
                <button onclick="refreshData()" class="px-4 py-2 bg-royal-purple text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center justify-center gap-2 text-sm sm:text-base">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="hidden sm:inline">Refresh</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Sales</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900" id="totalSales">₹0</p>
                    <p class="text-xs sm:text-sm text-green-600" id="salesGrowth">+0%</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900" id="totalOrders">0</p>
                    <p class="text-xs sm:text-sm text-green-600" id="ordersGrowth">+0%</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Avg Order Value</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900" id="avgOrderValue">₹0</p>
                    <p class="text-xs sm:text-sm text-gray-500">Per order</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Top Category</p>
                    <p class="text-lg sm:text-xl font-bold text-gray-900" id="topCategory">-</p>
                    <p class="text-xs sm:text-sm text-gray-500">Best performer</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- AI-style insights from trends (filled by JS) --}}
    <div id="trendInsights" class="hidden bg-gradient-to-r from-royal-purple/10 via-tiffany-blue/10 to-royal-purple/10 rounded-2xl border border-royal-purple/20 p-4 sm:p-5">
        <p class="text-xs sm:text-sm font-semibold text-royal-purple uppercase tracking-wide mb-2">Trend insights</p>
        <p class="text-sm sm:text-base text-gray-800 leading-relaxed" id="trendInsightsText"></p>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
        {{-- Sales Trend Chart --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-dm">Revenue &amp; order volume</h3>
                <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-gray-600">
                    <span class="inline-flex items-center gap-1"><span class="w-2.5 h-2.5 bg-royal-purple rounded-full"></span> Revenue</span>
                    <span class="inline-flex items-center gap-1"><span class="w-2.5 h-2.5 bg-tiffany-blue rounded-full"></span> Orders</span>
                </div>
            </div>
            <div class="h-48 sm:h-56 lg:h-64 flex items-center justify-center">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>

        {{-- Hourly Sales Chart --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-dm">Today's Hourly Sales</h3>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-tiffany-blue rounded-full"></div>
                    <span class="text-xs sm:text-sm text-gray-600">Hourly Revenue</span>
                </div>
            </div>
            <div class="h-48 sm:h-56 lg:h-64 flex items-center justify-center">
                <canvas id="hourlyChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Demand & revenue patterns (selected period) --}}
    <div class="space-y-2">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 font-dm px-1">Demand &amp; revenue patterns</h2>
        <p class="text-sm text-gray-600 px-1">Use these trends to plan staffing, promotions, and channel focus.</p>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-dm">Sales by day of week</h3>
                <span class="text-xs sm:text-sm text-gray-500" id="weekdayTrendPeriod">Last 30 days</span>
            </div>
            <div class="h-52 sm:h-64 flex items-center justify-center">
                <canvas id="weekdayChart" width="400" height="220"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-dm">Peak hours <span class="text-gray-500 font-normal text-sm">(period total)</span></h3>
                <span class="text-xs sm:text-sm text-gray-500" id="peakHourTrendPeriod">Last 30 days</span>
            </div>
            <div class="h-52 sm:h-64 flex items-center justify-center">
                <canvas id="peakHourChart" width="400" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-dm">Revenue by service mode</h3>
                <span class="text-xs sm:text-sm text-gray-500" id="modeMixPeriod">Last 30 days</span>
            </div>
            <div class="h-56 sm:h-64 flex items-center justify-center max-w-md mx-auto">
                <canvas id="modeMixChart" width="320" height="280"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-dm">Revenue by payment method</h3>
                <span class="text-xs sm:text-sm text-gray-500" id="paymentMixPeriod">Last 30 days</span>
            </div>
            <div class="h-56 sm:h-64 flex items-center justify-center max-w-md mx-auto">
                <canvas id="paymentMixChart" width="320" height="280"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Items and Categories --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
        {{-- Top Items --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-dm">Top Selling Items</h3>
                <span class="text-xs sm:text-sm text-gray-500" id="topItemsPeriod">Last 30 days</span>
            </div>
            <div class="space-y-3 sm:space-y-4" id="topItemsList">
                <div class="flex items-center justify-center py-6 sm:py-8">
                    <div class="animate-spin rounded-full h-6 w-6 sm:h-8 sm:w-8 border-b-2 border-royal-purple"></div>
                </div>
            </div>
        </div>

        {{-- Category Performance --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-dm">Category Performance</h3>
                <span class="text-xs sm:text-sm text-gray-500" id="categoryPeriod">Last 30 days</span>
            </div>
            <div class="space-y-3 sm:space-y-4" id="categoryList">
                <div class="flex items-center justify-center py-6 sm:py-8">
                    <div class="animate-spin rounded-full h-6 w-6 sm:h-8 sm:w-8 border-b-2 border-royal-purple"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Type Analytics --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-dm">Order Type Performance</h3>
            <span class="text-xs sm:text-sm text-gray-500" id="orderTypePeriod">Last 30 days</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" id="orderTypeList">
            <div class="flex items-center justify-center py-6 sm:py-8">
                <div class="animate-spin rounded-full h-6 w-6 sm:h-8 sm:w-8 border-b-2 border-royal-purple"></div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let salesChart, hourlyChart, weekdayChart, peakHourChart, modeMixChart, paymentMixChart;
let currentPeriod = '30days';

const analyticsPalette = ['#6E46AE', '#00B6B4', '#F59E0B', '#10B981', '#6366F1', '#EC4899', '#14B8A6', '#A855F7'];

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
    // Sales Trend Chart (revenue + order volume)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Revenue (₹)',
                    data: [],
                    borderColor: '#6E46AE',
                    backgroundColor: 'rgba(110, 70, 174, 0.12)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Orders',
                    data: [],
                    borderColor: '#00B6B4',
                    backgroundColor: 'rgba(0, 182, 180, 0.06)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.35,
                    yAxisID: 'y1',
                    pointRadius: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            },
            scales: {
                y: {
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + Number(value).toLocaleString();
                        }
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
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

    const weekdayCtx = document.getElementById('weekdayChart').getContext('2d');
    weekdayChart = new Chart(weekdayCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Revenue',
                data: [],
                backgroundColor: 'rgba(110, 70, 174, 0.78)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + Number(value).toLocaleString();
                        }
                    }
                }
            }
        }
    });

    const peakCtx = document.getElementById('peakHourChart').getContext('2d');
    peakHourChart = new Chart(peakCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Revenue',
                data: [],
                backgroundColor: 'rgba(0, 182, 180, 0.65)',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { maxRotation: 45, minRotation: 0, font: { size: 9 } } },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + Number(value).toLocaleString();
                        }
                    }
                }
            }
        }
    });

    const modeCtx = document.getElementById('modeMixChart').getContext('2d');
    modeMixChart = new Chart(modeCtx, {
        type: 'doughnut',
        data: { labels: [], datasets: [{ data: [], backgroundColor: analyticsPalette }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const v = ctx.raw;
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total ? ((v / total) * 100).toFixed(1) : 0;
                            return ctx.label + ': ₹' + Number(v).toLocaleString() + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });

    const payCtx = document.getElementById('paymentMixChart').getContext('2d');
    paymentMixChart = new Chart(payCtx, {
        type: 'doughnut',
        data: { labels: [], datasets: [{ data: [], backgroundColor: analyticsPalette }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const v = ctx.raw;
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total ? ((v / total) * 100).toFixed(1) : 0;
                            return ctx.label + ': ₹' + Number(v).toLocaleString() + ' (' + pct + '%)';
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
            loadBusinessTrends(),
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
            const ordersPerDay = data.daily_sales.map(item => parseInt(item.orders_count, 10) || 0);
            
            salesChart.data.labels = labels;
            salesChart.data.datasets[0].data = salesData;
            salesChart.data.datasets[1].data = ordersPerDay;
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

function updateTrendInsights(data) {
    const box = document.getElementById('trendInsights');
    const textEl = document.getElementById('trendInsightsText');
    const best = data.best_weekday;
    const hourly = data.hourly_distribution || [];
    const pt = getPeriodText(currentPeriod);
    if (!best || best.total_sales <= 0) {
        box.classList.add('hidden');
        return;
    }
    box.classList.remove('hidden');
    let peak = hourly.length ? hourly[0] : null;
    hourly.forEach(function (h) {
        if (peak && h.total_sales > peak.total_sales) {
            peak = h;
        }
    });
    const topMode = (data.mode_mix && data.mode_mix.length) ? data.mode_mix[0] : null;
    const topPay = (data.payment_mix && data.payment_mix.length) ? data.payment_mix[0] : null;
    const parts = [
        pt + ': strongest day is ' + best.label + ' (₹' + Number(best.total_sales).toLocaleString() + ' revenue, ' + best.orders_count + ' orders).'
    ];
    if (peak && peak.total_sales > 0) {
        parts.push('Peak hour in this range: ' + peak.label + ' (~₹' + Number(peak.total_sales).toLocaleString() + ').');
    }
    if (topMode && topMode.pct_revenue > 0) {
        parts.push('Leading service mode: ' + topMode.label + ' (' + topMode.pct_revenue + '% of revenue).');
    }
    if (topPay && topPay.pct_revenue > 0) {
        parts.push('Top payment: ' + topPay.label + ' (' + topPay.pct_revenue + '%).');
    }
    textEl.textContent = parts.join(' ');
}

async function loadBusinessTrends() {
    try {
        const response = await fetch(`/{{ $tenant->slug }}/analytics/business-trends?period=${currentPeriod}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        if (!response.ok) {
            return;
        }
        const data = await response.json();
        const pt = getPeriodText(currentPeriod);
        document.getElementById('weekdayTrendPeriod').textContent = pt;
        document.getElementById('peakHourTrendPeriod').textContent = pt;
        document.getElementById('modeMixPeriod').textContent = pt;
        document.getElementById('paymentMixPeriod').textContent = pt;

        updateTrendInsights(data);

        if (data.by_weekday && data.by_weekday.length) {
            weekdayChart.data.labels = data.by_weekday.map(function (d) { return d.label; });
            weekdayChart.data.datasets[0].data = data.by_weekday.map(function (d) { return d.total_sales; });
            weekdayChart.update();
        }

        if (data.hourly_distribution && data.hourly_distribution.length) {
            peakHourChart.data.labels = data.hourly_distribution.map(function (d) { return d.label; });
            peakHourChart.data.datasets[0].data = data.hourly_distribution.map(function (d) { return d.total_sales; });
            peakHourChart.update();
        }

        if (data.mode_mix && data.mode_mix.length) {
            modeMixChart.data.labels = data.mode_mix.map(function (m) { return m.label; });
            modeMixChart.data.datasets[0].data = data.mode_mix.map(function (m) { return m.total_sales; });
            modeMixChart.data.datasets[0].backgroundColor = data.mode_mix.map(function (_, i) {
                return analyticsPalette[i % analyticsPalette.length];
            });
            modeMixChart.update();
        } else {
            modeMixChart.data.labels = ['No data'];
            modeMixChart.data.datasets[0].data = [1];
            modeMixChart.data.datasets[0].backgroundColor = ['#e5e7eb'];
            modeMixChart.update();
        }

        if (data.payment_mix && data.payment_mix.length) {
            paymentMixChart.data.labels = data.payment_mix.map(function (p) { return p.label; });
            paymentMixChart.data.datasets[0].data = data.payment_mix.map(function (p) { return p.total_sales; });
            paymentMixChart.data.datasets[0].backgroundColor = data.payment_mix.map(function (_, i) {
                return analyticsPalette[i % analyticsPalette.length];
            });
            paymentMixChart.update();
        } else {
            paymentMixChart.data.labels = ['No data'];
            paymentMixChart.data.datasets[0].data = [1];
            paymentMixChart.data.datasets[0].backgroundColor = ['#e5e7eb'];
            paymentMixChart.update();
        }
    } catch (error) {
        console.error('Error loading business trends:', error);
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
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-royal-purple text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold flex-shrink-0">
                                ${index + 1}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 text-sm sm:text-base truncate">${item.name}</h4>
                                <p class="text-xs sm:text-sm text-gray-600">${item.total_quantity} sold</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-2">
                            <p class="font-semibold text-gray-900 text-sm sm:text-base">₹${parseFloat(item.total_revenue).toLocaleString()}</p>
                            <p class="text-xs sm:text-sm text-gray-600">${item.order_count} orders</p>
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
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-tiffany-blue text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold flex-shrink-0">
                                ${index + 1}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 text-sm sm:text-base truncate">${category.name}</h4>
                                <p class="text-xs sm:text-sm text-gray-600">${category.total_quantity} items sold</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-2">
                            <p class="font-semibold text-gray-900 text-sm sm:text-base">₹${parseFloat(category.total_revenue).toLocaleString()}</p>
                            <p class="text-xs sm:text-sm text-gray-600">${category.order_count} orders</p>
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
                    <div class="bg-gray-50 rounded-lg p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <h4 class="font-semibold text-gray-900 text-sm sm:text-base">${orderType.name}</h4>
                            <div class="w-3 h-3 bg-royal-purple rounded-full"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs sm:text-sm text-gray-600">Orders:</span>
                                <span class="font-semibold text-sm sm:text-base">${orderType.orders_count}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs sm:text-sm text-gray-600">Revenue:</span>
                                <span class="font-semibold text-sm sm:text-base">₹${parseFloat(orderType.total_sales).toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs sm:text-sm text-gray-600">Avg Value:</span>
                                <span class="font-semibold text-sm sm:text-base">₹${parseFloat(orderType.avg_order_value).toLocaleString()}</span>
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
