@extends('layouts.tenant')

@section('title', 'Restaurant Reports & Analytics')
@section('description', 'Comprehensive restaurant reports and analytics dashboard. Generate sales reports, track performance metrics, analyze top-selling items, and export data in multiple formats.')
@section('keywords', 'restaurant reports, sales reports, restaurant analytics, performance metrics, business reports, sales analysis, restaurant data, export reports, POS reports')

@section('content')
<div class="space-y-4 sm:space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 font-dm">Reports</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-2">Analyze your sales and performance data</p>
            </div>
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Today's Sales</p>
                    <p class="text-lg sm:text-2xl font-semibold text-gray-900" id="today-sales">₹0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Total Orders</p>
                    <p class="text-lg sm:text-2xl font-semibold text-gray-900" id="total-orders">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Avg Order Value</p>
                    <p class="text-lg sm:text-2xl font-semibold text-gray-900" id="avg-order-value">₹0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Top Items</p>
                    <p class="text-lg sm:text-2xl font-semibold text-gray-900" id="top-items-count">0</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Report Templates --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Pre-made Report Templates</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- Sales Summary Template --}}
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow cursor-pointer" onclick="openViewReportsModal('sales_summary')">
                <div class="flex items-center mb-2 sm:mb-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Sales Summary</h3>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">Complete overview of sales performance with daily, hourly, and summary breakdowns</p>
                <div class="flex items-center text-xs sm:text-sm text-gray-500">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF, CSV, Excel
                </div>
            </div>

            {{-- Top Items Template --}}
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow cursor-pointer" onclick="openViewReportsModal('top_items')">
                <div class="flex items-center mb-2 sm:mb-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Top Selling Items</h3>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">Best performing menu items with quantity sold and revenue analysis</p>
                <div class="flex items-center text-xs sm:text-sm text-gray-500">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF, CSV, Excel
                </div>
            </div>

            {{-- Shift Report Template --}}
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow cursor-pointer" onclick="openViewReportsModal('shift_report')">
                <div class="flex items-center mb-2 sm:mb-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Shift Reports</h3>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">Detailed shift analysis with cash management and performance metrics</p>
                <div class="flex items-center text-xs sm:text-sm text-gray-500">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF, CSV, Excel
                </div>
            </div>

            {{-- Order Summary Template --}}
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow cursor-pointer" onclick="openViewReportsModal('order_summary')">
                <div class="flex items-center mb-2 sm:mb-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Order Summary</h3>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">Comprehensive order analysis by type with customer details and delivery info</p>
                <div class="flex items-center text-xs sm:text-sm text-gray-500">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF, CSV, Excel
                </div>
            </div>

            {{-- Custom Report Template --}}
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow cursor-pointer" onclick="openGenerateReportModal()">
                <div class="flex items-center mb-2 sm:mb-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Custom Report</h3>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">Create a custom report with your specific requirements and filters</p>
                <div class="flex items-center text-xs sm:text-sm text-gray-500">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF, CSV, Excel
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
        <button onclick="openViewReportsModal()" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors w-full sm:w-auto">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            View Reports
        </button>
        <button onclick="openGenerateReportModal()" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors w-full sm:w-auto">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Generate Report
        </button>
        <button onclick="generateTestData()" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors w-full sm:w-auto">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Generate Test Data
        </button>
    </div>
</div>

{{-- View Reports Modal --}}
<div id="viewReportsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-11/12 sm:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-medium text-gray-900">View Reports</h3>
                <button onclick="closeViewReportsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select id="reportType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="sales">Sales Report</option>
                        <option value="top_items">Top Selling Items</option>
                        <option value="shift">Shift Report</option>
                        <option value="order_summary">Order Summary</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Outlet</label>
                    <select id="outletSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                        <input type="date" id="dateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                        <input type="date" id="dateTo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                
                <div id="reportTypeOptions" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Options</label>
                    <select id="reportOptions" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="daily">Daily Report</option>
                        <option value="hourly">Hourly Report</option>
                        <option value="summary">Summary Report</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button onclick="closeViewReportsModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button onclick="loadReportData()" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                    Load Report
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Generate Report Modal --}}
<div id="generateReportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-11/12 sm:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-medium text-gray-900">Generate Report</h3>
                <button onclick="closeGenerateReportModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select id="generateReportType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="sales">Sales Report</option>
                        <option value="top_items">Top Selling Items</option>
                        <option value="shift">Shift Report</option>
                        <option value="order_summary">Order Summary</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="exportFormat" value="pdf" class="mr-2" checked>
                            <span class="text-sm">PDF</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="exportFormat" value="csv" class="mr-2">
                            <span class="text-sm">CSV</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="exportFormat" value="excel" class="mr-2">
                            <span class="text-sm">Excel</span>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Outlet</label>
                    <select id="generateOutletSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                        <input type="date" id="generateDateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                        <input type="date" id="generateDateTo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button onclick="closeGenerateReportModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button onclick="generateReport()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors" id="generateReportBtn">
                    Generate Report
                            </button>
            </div>
        </div>
    </div>
                    </div>

{{-- Report Display Area --}}
<div id="reportDisplayArea" class="hidden bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-gray-900">Report Results</h3>
        <button onclick="closeReportDisplay()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div id="reportContent" class="space-y-4">
        <!-- Report content will be loaded here -->
    </div>
</div>

<script>
// Error logging function
function logError(level, message, data = null) {
    const timestamp = new Date().toISOString();
    const logEntry = {
        timestamp: timestamp,
        level: level,
        message: message,
        data: data,
        url: window.location.href,
        userAgent: navigator.userAgent
    };
    
    console.log(`[${level}] ${message}`, data || '');
    
    // Send to server for logging
    fetch('/teabench1/reports/log-error', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(logEntry)
    }).catch(err => {
        console.error('Failed to log error to server:', err);
    });
}

// Set default dates
document.addEventListener('DOMContentLoaded', function() {
    try {
        logError('INFO', 'DOM loaded, initializing reports page');
        
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        document.getElementById('dateFrom').value = yesterday.toISOString().split('T')[0];
        document.getElementById('dateTo').value = today.toISOString().split('T')[0];
        document.getElementById('generateDateFrom').value = yesterday.toISOString().split('T')[0];
        document.getElementById('generateDateTo').value = today.toISOString().split('T')[0];
        
        logError('INFO', 'Default dates set', {
            yesterday: yesterday.toISOString().split('T')[0],
            today: today.toISOString().split('T')[0]
        });
        
        // Check if CSRF token exists
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            logError('ERROR', 'CSRF token not found');
        } else {
            logError('INFO', 'CSRF token found');
        }
        
        // Load quick stats
        loadQuickStats();
        
        logError('INFO', 'Reports page initialization completed');
    } catch (error) {
        logError('ERROR', 'DOM initialization failed', {
            error: error.message,
            stack: error.stack
        });
    }
});

function openViewReportsModal(reportType = null) {
    try {
        logError('INFO', 'Opening view reports modal', { reportType: reportType });
        document.getElementById('viewReportsModal').classList.remove('hidden');
        if (reportType) {
            document.getElementById('reportType').value = reportType;
        }
    } catch (error) {
        logError('ERROR', 'Failed to open view reports modal', {
            error: error.message,
            reportType: reportType
        });
    }
}

function closeViewReportsModal() {
    try {
        logError('INFO', 'Closing view reports modal');
        document.getElementById('viewReportsModal').classList.add('hidden');
    } catch (error) {
        logError('ERROR', 'Failed to close view reports modal', {
            error: error.message
        });
    }
}

function openGenerateReportModal() {
    try {
        logError('INFO', 'Opening generate report modal');
        document.getElementById('generateReportModal').classList.remove('hidden');
    } catch (error) {
        logError('ERROR', 'Failed to open generate report modal', {
            error: error.message
        });
    }
}

function closeGenerateReportModal() {
    try {
        logError('INFO', 'Closing generate report modal');
        document.getElementById('generateReportModal').classList.add('hidden');
    } catch (error) {
        logError('ERROR', 'Failed to close generate report modal', {
            error: error.message
        });
    }
}

function closeReportDisplay() {
    try {
        logError('INFO', 'Closing report display');
        document.getElementById('reportDisplayArea').classList.add('hidden');
    } catch (error) {
        logError('ERROR', 'Failed to close report display', {
            error: error.message
        });
    }
}

function loadQuickStats() {
    const outletId = document.getElementById('outletSelect').value;
    
    fetch(`/teabench1/reports/quick-stats?outlet_id=${outletId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('today-sales').textContent = '₹' + (data.today_sales || 0).toLocaleString();
        document.getElementById('total-orders').textContent = (data.total_orders || 0).toLocaleString();
        document.getElementById('avg-order-value').textContent = '₹' + (data.avg_order_value || 0).toLocaleString();
        document.getElementById('top-items-count').textContent = (data.top_items_count || 0).toLocaleString();
    })
    .catch(error => {
        console.error('Error loading quick stats:', error);
        // Fallback to placeholder data
        document.getElementById('today-sales').textContent = '₹0';
        document.getElementById('total-orders').textContent = '0';
        document.getElementById('avg-order-value').textContent = '₹0';
        document.getElementById('top-items-count').textContent = '0';
    });
}

function loadReportData() {
    const reportType = document.getElementById('reportType').value;
    const outletId = document.getElementById('outletSelect').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const reportOptions = document.getElementById('reportOptions').value;
    
    // Show loading state
    const reportContent = document.getElementById('reportContent');
    reportContent.innerHTML = '<div class="flex justify-center items-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div></div>';
    
    // Show report display area
    document.getElementById('reportDisplayArea').classList.remove('hidden');
    
    // Make AJAX request
    fetch(`/teabench1/reports/${reportType}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            outlet_id: outletId,
            date_from: dateFrom,
            date_to: dateTo,
            report_type: reportOptions
        })
    })
    .then(response => response.json())
    .then(data => {
        displayReportData(data, reportType);
        closeViewReportsModal();
    })
    .catch(error => {
        console.error('Error:', error);
        reportContent.innerHTML = '<div class="text-red-600 text-center py-8">Error loading report data</div>';
    });
}

function generateReport() {
    try {
        console.log('Generate report function called');
        
        // Log function execution start
        logError('INFO', 'Generate Report function started');
        
        const reportType = document.getElementById('generateReportType').value;
        const format = document.querySelector('input[name="exportFormat"]:checked').value;
        const outletId = document.getElementById('generateOutletSelect').value;
        const dateFrom = document.getElementById('generateDateFrom').value;
        const dateTo = document.getElementById('generateDateTo').value;
        
        console.log('Report data:', { reportType, format, outletId, dateFrom, dateTo });
        
        // Log form data
        logError('INFO', 'Form data collected', {
            reportType: reportType,
            format: format,
            outletId: outletId,
            dateFrom: dateFrom,
            dateTo: dateTo
        });
        
        // Validate dates
        if (!dateFrom || !dateTo) {
            logError('ERROR', 'Missing date fields', { dateFrom: dateFrom, dateTo: dateTo });
            alert('Please select both start and end dates');
            return;
        }
        
        if (new Date(dateFrom) > new Date(dateTo)) {
            logError('ERROR', 'Invalid date range', { dateFrom: dateFrom, dateTo: dateTo });
            alert('Start date cannot be after end date');
            return;
        }
        
        // Check if dates are in the future
        const today = new Date();
        const startDate = new Date(dateFrom);
        const endDate = new Date(dateTo);
        
        if (startDate > today || endDate > today) {
            logError('WARNING', 'Future dates selected', { 
                startDate: startDate.toISOString(), 
                endDate: endDate.toISOString(),
                today: today.toISOString()
            });
            if (!confirm('You have selected future dates. This may result in empty reports. Do you want to continue?')) {
                logError('INFO', 'User cancelled future date report');
                return;
            }
        }
    
        // Show loading state
        const reportContent = document.getElementById('reportContent');
        reportContent.innerHTML = '<div class="flex justify-center items-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';
        
        // Show report display area
        document.getElementById('reportDisplayArea').classList.remove('hidden');
        
        // Check CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            logError('ERROR', 'CSRF token not found');
            throw new Error('CSRF token not found');
        }
        
        logError('INFO', 'Starting AJAX request to export endpoint');
        
        // Make AJAX request
        fetch('/teabench1/reports/export', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({
                report_type: reportType,
                format: format,
                outlet_id: outletId,
                date_from: dateFrom,
                date_to: dateTo
            })
        })
        .then(response => {
            logError('INFO', 'Response received', {
                status: response.status,
                statusText: response.statusText,
                contentType: response.headers.get('content-type')
            });
            
            if (response.ok) {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    logError('INFO', 'Processing JSON response');
                    return response.json();
                } else {
                    logError('INFO', 'Processing file download response');
                    return response.blob();
                }
            } else {
                // Handle error responses
                return response.json().then(errorData => {
                    throw new Error(errorData.message || errorData.error || 'Export failed with status: ' + response.status);
                });
            }
        })
        .then(data => {
            logError('INFO', 'Response data processed', {
                dataType: data instanceof Blob ? 'Blob' : 'JSON',
                dataSize: data instanceof Blob ? data.size : 'N/A'
            });
            
            if (data instanceof Blob) {
                // Handle file download
                logError('INFO', 'Initiating file download');
                const url = window.URL.createObjectURL(data);
                const a = document.createElement('a');
                a.href = url;
                a.download = `${reportType}_report_${dateFrom}_to_${dateTo}.${format}`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                logError('INFO', 'File download completed successfully');
                closeGenerateReportModal();
                closeReportDisplay();
            } else {
                // Handle JSON response (for debugging or when export is not ready)
                logError('INFO', 'Displaying JSON response data');
                reportContent.innerHTML = '<div class="text-green-600 text-center py-8">Report generated successfully! Check logs for details.</div>';
                console.log('Generated report data:', data);
                closeGenerateReportModal();
            }
        })
        .catch(error => {
            logError('ERROR', 'Export request failed', {
                error: error.message,
                stack: error.stack
            });
            console.error('Error:', error);
            reportContent.innerHTML = '<div class="text-red-600 text-center py-8">Error generating report: ' + error.message + '</div>';
        });
        
    } catch (error) {
        logError('ERROR', 'Generate report function failed', {
            error: error.message,
            stack: error.stack
        });
        console.error('Generate report error:', error);
        alert('An error occurred while generating the report. Check logs for details.');
    }
}

function displayReportData(data, reportType) {
    const reportContent = document.getElementById('reportContent');
    
    switch (reportType) {
        case 'sales':
            displaySalesReport(data);
            break;
        case 'top_items':
            displayTopItemsReport(data);
            break;
        case 'shift':
            displayShiftReport(data);
            break;
        case 'order_summary':
            displayOrderSummaryReport(data);
            break;
        default:
            reportContent.innerHTML = '<div class="text-gray-600 text-center py-8">Report data not available</div>';
    }
}

function displaySalesReport(data) {
    const reportContent = document.getElementById('reportContent');
    
    let html = `
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900">Total Sales</h4>
                    <p class="text-2xl font-bold text-green-600">₹${data.total_sales || 0}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900">Total Bills</h4>
                    <p class="text-2xl font-bold text-blue-600">${data.total_bills || 0}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900">Average Bill Value</h4>
                    <p class="text-2xl font-bold text-purple-600">₹${data.average_bill_value || 0}</p>
                </div>
            </div>
    `;
    
    if (data.payment_methods) {
        html += `
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-3">Payment Methods</h4>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Cash</p>
                        <p class="font-semibold">₹${data.payment_methods.cash || 0}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Card</p>
                        <p class="font-semibold">₹${data.payment_methods.card || 0}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">UPI</p>
                        <p class="font-semibold">₹${data.payment_methods.upi || 0}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Wallet</p>
                        <p class="font-semibold">₹${data.payment_methods.wallet || 0}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Other</p>
                        <p class="font-semibold">₹${data.payment_methods.other || 0}</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    html += '</div>';
    reportContent.innerHTML = html;
}

function displayTopItemsReport(data) {
    const reportContent = document.getElementById('reportContent');
    
    let html = '<div class="space-y-4">';
    
    if (data && data.length > 0) {
        data.forEach((item, index) => {
            html += `
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-purple-600">${index + 1}</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">${item.item.name}</h4>
                            <p class="text-sm text-gray-600">${item.total_qty} sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₹${item.total_revenue}</p>
                        <p class="text-sm text-gray-600">${item.order_count} orders</p>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<div class="text-center py-8 text-gray-600">No data available</div>';
    }
    
    html += '</div>';
    reportContent.innerHTML = html;
}

function displayShiftReport(data) {
    const reportContent = document.getElementById('reportContent');
    
    let html = '<div class="space-y-4">';
    
    if (data && data.length > 0) {
        data.forEach(shift => {
            html += `
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900">Shift #${shift.shift_id}</h4>
                        <span class="text-sm text-gray-600">${shift.opened_by}</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Total Sales</p>
                            <p class="font-semibold">₹${shift.total_sales}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Bills</p>
                            <p class="font-semibold">${shift.total_bills}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Opened At</p>
                            <p class="font-semibold">${new Date(shift.opened_at).toLocaleString()}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Closed At</p>
                            <p class="font-semibold">${shift.closed_at ? new Date(shift.closed_at).toLocaleString() : 'Open'}</p>
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<div class="text-center py-8 text-gray-600">No shift data available</div>';
    }
    
    html += '</div>';
    reportContent.innerHTML = html;
}

function displayOrderSummaryReport(data) {
    const reportContent = document.getElementById('reportContent');
    
    let html = '<div class="space-y-6">';
    
    if (data && data.summary) {
        html += `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900">Total Orders</h4>
                    <p class="text-2xl font-bold text-blue-600">${data.summary.total_orders}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900">Total Revenue</h4>
                    <p class="text-2xl font-bold text-green-600">₹${data.summary.total_revenue}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900">Average Order Value</h4>
                    <p class="text-2xl font-bold text-purple-600">₹${data.summary.average_order_value}</p>
                </div>
            </div>
        `;
    }
    
    if (data && data.order_types && data.order_types.length > 0) {
        html += '<div class="space-y-4">';
        data.order_types.forEach(orderType => {
            html += `
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3">${orderType.order_type.name}</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Total Orders</p>
                            <p class="font-semibold">${orderType.total_orders}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Revenue</p>
                            <p class="font-semibold">₹${orderType.total_revenue}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Average Value</p>
                            <p class="font-semibold">₹${orderType.average_order_value}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tables/Addresses</p>
                            <p class="font-semibold">${orderType.table_numbers.length + orderType.delivery_addresses.length}</p>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
    }
    
    html += '</div>';
    reportContent.innerHTML = html;
}

// Show report type options for sales reports
document.getElementById('reportType').addEventListener('change', function() {
    const reportTypeOptions = document.getElementById('reportTypeOptions');
    if (this.value === 'sales') {
        reportTypeOptions.classList.remove('hidden');
    } else {
        reportTypeOptions.classList.add('hidden');
    }
});

// Refresh quick stats when outlet selection changes
document.getElementById('outletSelect').addEventListener('change', function() {
    loadQuickStats();
});

function generateTestData() {
    try {
        logError('INFO', 'Generating test data for reports');
        
        if (!confirm('This will generate sample orders, bills, and payments for the last 7 days. This will help you test the reports with real data. Continue?')) {
            logError('INFO', 'User cancelled test data generation');
            return;
        }
        
        // Show loading state
        const reportContent = document.getElementById('reportContent');
        reportContent.innerHTML = '<div class="flex justify-center items-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div><span class="ml-3">Generating test data...</span></div>';
        
        // Show report display area
        document.getElementById('reportDisplayArea').classList.remove('hidden');
        
        fetch('/teabench1/reports/generate-test-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            logError('INFO', 'Test data generation response', data);
            
            if (data.success) {
                reportContent.innerHTML = `
                    <div class="text-center py-8">
                        <div class="text-green-600 text-lg font-semibold mb-2">✅ Test Data Generated Successfully!</div>
                        <p class="text-gray-600 mb-4">${data.message}</p>
                        <p class="text-sm text-gray-500">You can now generate reports with real data for the last 7 days.</p>
                        <button onclick="closeReportDisplay()" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            Close
                        </button>
                    </div>
                `;
            } else {
                reportContent.innerHTML = `
                    <div class="text-center py-8">
                        <div class="text-red-600 text-lg font-semibold mb-2">❌ Error Generating Test Data</div>
                        <p class="text-gray-600 mb-4">${data.error}</p>
                        <button onclick="closeReportDisplay()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            Close
                        </button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Test data generation error:', error);
            logError('ERROR', 'Test data generation failed', {
                error: error.message,
                stack: error.stack
            });
            reportContent.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-600 text-lg font-semibold mb-2">❌ Error Generating Test Data</div>
                    <p class="text-gray-600 mb-4">An error occurred while generating test data.</p>
                    <p class="text-sm text-gray-500 mb-4">Error: ${error.message}</p>
                    <button onclick="closeReportDisplay()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Close
                    </button>
                </div>
            `;
        });
        
    } catch (error) {
        logError('ERROR', 'Test data generation function failed', {
            error: error.message,
            stack: error.stack
        });
        alert('An error occurred while generating test data. Check logs for details.');
    }
}

// Make functions globally available for onclick handlers
window.openViewReportsModal = openViewReportsModal;
window.closeViewReportsModal = closeViewReportsModal;
window.openGenerateReportModal = openGenerateReportModal;
window.closeGenerateReportModal = closeGenerateReportModal;
window.closeReportDisplay = closeReportDisplay;
window.loadReportData = loadReportData;
window.generateReport = generateReport;
window.generateTestData = generateTestData;

// Add event listener as backup
document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.getElementById('generateReportBtn');
    if (generateBtn) {
        generateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Generate button clicked via event listener');
            generateReport();
        });
    }
});
</script>
@endsection