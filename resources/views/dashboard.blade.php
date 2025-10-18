<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - {{ !empty(app('tenant')->name) ? app('tenant')->name : (app('tenant')->website ?? 'Dukaantech POS') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <link rel="apple-touch-icon" href="/favicon.png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-red-50 min-h-screen">
    {{-- Header Component --}}
    <x-dashboard-header />

    {{-- Main Content --}}
    <div class="min-h-screen py-12">
        <div class="mx-auto max-w-7xl px-4">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-8 max-w-4xl mx-auto">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mt-0.5 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-green-800">Setup Complete!</h3>
                                <p class="text-green-700 mt-1">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Welcome Section --}}
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-bold text-gray-900 mb-4 font-dm">Welcome to Dukaantech POS!</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
                    Your restaurant management system is now ready. Start taking orders and managing your business efficiently.
                </p>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-12">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">New Order</h3>
                    <p class="text-gray-600 text-sm mb-4">Start taking orders from customers</p>
                    <button class="w-full border-2 border-gray-200 text-gray-700 py-2 px-4 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
                        Create Order
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manage Menu</h3>
                    <p class="text-gray-600 text-sm mb-4">Add and edit your restaurant menu</p>
                    <button class="w-full border-2 border-gray-200 text-gray-700 py-2 px-4 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
                        Manage Menu
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">View Reports</h3>
                    <p class="text-gray-600 text-sm mb-4">Analyze your sales and performance</p>
                    <button onclick="window.location.href='/dukaantech/reports'" class="w-full border-2 border-gray-200 text-gray-700 py-2 px-4 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
                        View Reports
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Settings</h3>
                    <p class="text-gray-600 text-sm mb-4">Configure your restaurant settings</p>
                    <button class="w-full border-2 border-gray-200 text-gray-700 py-2 px-4 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
                        Settings
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manage Tables</h3>
                    <p class="text-gray-600 text-sm mb-4">Create and manage restaurant tables</p>
                    <button onclick="openTableManager()" class="w-full border-2 border-gray-200 text-gray-700 py-2 px-4 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
                        Manage Tables
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200">
                    <div class="w-12 h-12 bg-gradient-to-br from-royal-purple to-tiffany-blue rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">View Analytics</h3>
                    <p class="text-gray-600 text-sm mb-4">Comprehensive insights and performance metrics</p>
                    <button onclick="window.location.href='/analytics'" class="w-full border-2 border-gray-200 text-gray-700 py-2 px-4 rounded-lg font-semibold hover:border-royal-purple hover:text-royal-purple transition-all">
                        View Analytics
                    </button>
                </div>
            </div>

            {{-- Reports Section --}}
            <div id="reports" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 font-dm">Reports & Analytics</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Sales Reports</h3>
                        <p class="text-gray-600 text-sm mb-4">Daily, hourly, and summary sales analysis</p>
                        <button onclick="window.location.href='/dukaantech/reports'" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            View Sales
                        </button>
                    </div>
                    
                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Top Items</h3>
                        <p class="text-gray-600 text-sm mb-4">Best selling items and performance metrics</p>
                        <button onclick="window.location.href='/dukaantech/reports'" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                            View Top Items
                        </button>
                    </div>
                    
                    <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                        <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Shift Reports</h3>
                        <p class="text-gray-600 text-sm mb-4">Shift-wise sales and cash management</p>
                        <button onclick="window.location.href='/dukaantech/reports'" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors">
                            View Shifts
                        </button>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600 mb-4">Access detailed reports and analytics for your restaurant</p>
                    <button onclick="showReportsInfo()" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                        Learn More About Reports
                    </button>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 font-dm">Getting Started</h2>
                    @if($completionPercentage > 0)
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">{{ $completionPercentage }}% Complete</span>
                            <div class="w-20 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-gradient-to-r from-orange-500 to-red-600 rounded-full transition-all duration-300" style="width: {{ $completionPercentage }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Next Steps</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                @if($setupStatus && $setupStatus['organization_setup'])
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Organization setup completed</span>
                                @else
                                    <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-orange-600 text-sm font-semibold">1</span>
                                    </div>
                                    <span class="text-gray-700">Complete organization setup</span>
                                @endif
                            </li>
                            <li class="flex items-center">
                                @if($setupStatus && $setupStatus['menu_items'])
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Menu items added</span>
                                @else
                                    <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-orange-600 text-sm font-semibold">2</span>
                                    </div>
                                    <span class="text-gray-700">Add your menu items</span>
                                @endif
                            </li>
                            <li class="flex items-center">
                                @if($setupStatus && $setupStatus['orders_taken'])
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Orders being taken</span>
                                @else
                                    <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-orange-600 text-sm font-semibold">3</span>
                                    </div>
                                    <span class="text-gray-700">Start taking orders</span>
                                @endif
                            </li>
                            <li class="flex items-center">
                                @if($setupStatus && $setupStatus['share_to_friend'])
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Shared to a friend</span>
                                @else
                                    <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-orange-600 text-sm font-semibold">4</span>
                                    </div>
                                    <a href="#" onclick="shareToFriend()" class="text-orange-600 hover:text-orange-700 font-medium cursor-pointer transition-colors">
                                        Share to A Friend
                                    </a>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Tips</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li>• Use the "New Order" button to start taking customer orders</li>
                            <li>• Add your menu items in the "Manage Menu" section</li>
                            <li>• Check reports regularly to track your performance</li>
                            <li>• Configure settings to match your restaurant's needs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Component --}}
    <x-dashboard-footer />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        function kotToggle() {
            return {
                kotEnabled: false,
                loading: false,
                
                async loadKotStatus() {
                    try {
                        const response = await fetch('/kot/status', {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.kotEnabled = data.kot_enabled;
                        }
                    } catch (error) {
                        console.error('Error loading KOT status:', error);
                    }
                },
                
                async toggleKot() {
                    try {
                        this.loading = true;
                        const response = await fetch('/kot/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                enabled: !this.kotEnabled
                            })
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.kotEnabled = data.kot_enabled;
                            
                            // Show success message
                            this.showNotification(data.message, 'success');
                        } else {
                            const error = await response.json();
                            this.showNotification(error.message || 'Failed to update KOT settings', 'error');
                        }
                    } catch (error) {
                        console.error('Error toggling KOT:', error);
                        this.showNotification('Failed to update KOT settings', 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                
                showNotification(message, type) {
                    // Create notification element
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                    }`;
                    notification.textContent = message;
                    
                    document.body.appendChild(notification);
                    
                    // Remove notification after 3 seconds
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            }
        }

        function showReportsInfo() {
            alert('Reports functionality is now available! You can:\n\n• View daily, hourly, and summary sales reports\n• Analyze top-selling items and performance metrics\n• Generate shift-wise reports for cash management\n• Export reports in PDF, CSV, and Excel formats\n\nTo access detailed reports, navigate to your tenant dashboard and click on the Reports section.');
        }

        // Smooth scroll to reports section
        document.addEventListener('DOMContentLoaded', function() {
            // Check if URL has #reports hash
            if (window.location.hash === '#reports') {
                setTimeout(() => {
                    document.getElementById('reports').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            }
        });

        // Share to Friend functionality
        function shareToFriend() {
            const shareUrl = window.location.origin;
            const shareText = "Check out this amazing POS system! It's helping me manage my restaurant efficiently.";
            
            // Create share modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Share to A Friend</h3>
                        <p class="text-gray-600">Help others discover this amazing POS system!</p>
                    </div>
                    
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Share Link</label>
                            <div class="flex">
                                <input type="text" value="${shareUrl}" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg bg-gray-50 text-sm">
                                <button onclick="copyToClipboard('${shareUrl}')" class="px-4 py-2 bg-orange-500 text-white rounded-r-lg hover:bg-orange-600 transition-colors text-sm">
                                    Copy
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Share Message</label>
                            <textarea readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm h-20 resize-none">${shareText}</textarea>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="shareViaWhatsApp('${shareUrl}', '${shareText}')" class="flex-1 bg-green-500 text-white py-2 px-4 rounded-lg font-semibold hover:bg-green-600 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            WhatsApp
                        </button>
                        <button onclick="shareViaEmail('${shareUrl}', '${shareText}')" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-600 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email
                        </button>
                    </div>
                    
                    <button onclick="closeShareModal()" class="w-full mt-4 py-2 px-4 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show success message
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.add('bg-green-500');
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-500');
                }, 2000);
            });
        }

        function shareViaWhatsApp(url, text) {
            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
            window.open(whatsappUrl, '_blank');
        }

        function shareViaEmail(url, text) {
            const subject = 'Check out this POS system!';
            const body = `${text}\n\n${url}`;
            const emailUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            window.location.href = emailUrl;
        }

        function closeShareModal() {
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) {
                modal.remove();
            }
        }

        // Table Management Functions
        function openTableManager() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 overflow-y-auto';
            modal.innerHTML = `
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeTableManager()"></div>
                    
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Table Management</h3>
                                    
                                    <!-- Add New Table -->
                                    <div class="mb-6">
                                        <div class="space-y-4">
                                            <div class="flex gap-3">
                                                <input type="text" id="newTableName" placeholder="Enter table name/number" 
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                                <button onclick="addTable()" 
                                                        class="px-4 py-2 bg-orange-600 text-white rounded-md text-sm font-medium hover:bg-orange-700 transition-colors">
                                                    Add Table
                                                </button>
                                            </div>
                                            
                                            <!-- Table Shape Selection -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Table Shape</label>
                                                <div class="grid grid-cols-4 gap-3">
                                                    <!-- Round Table -->
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" name="tableShape" value="round" class="sr-only" checked>
                                                        <div class="border-2 border-gray-300 rounded-full p-3 text-center hover:border-orange-500 transition-colors table-shape-option" data-shape="round">
                                                            <div class="w-8 h-8 mx-auto mb-2">
                                                                <svg class="w-full h-full text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                    <circle cx="12" cy="12" r="8" fill="currentColor" fill-opacity="0.1"/>
                                                                    <circle cx="12" cy="12" r="8"/>
                                                                    <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                                                    <circle cx="8" cy="8" r="1" fill="currentColor"/>
                                                                    <circle cx="16" cy="8" r="1" fill="currentColor"/>
                                                                    <circle cx="8" cy="16" r="1" fill="currentColor"/>
                                                                    <circle cx="16" cy="16" r="1" fill="currentColor"/>
                                                                </svg>
                                                            </div>
                                                            <span class="text-xs text-gray-600">Round</span>
                                                        </div>
                                                    </label>
                                                    
                                                    <!-- Rectangular Table -->
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" name="tableShape" value="rectangular" class="sr-only">
                                                        <div class="border-2 border-gray-300 rounded-lg p-3 text-center hover:border-orange-500 transition-colors table-shape-option" data-shape="rectangular">
                                                            <div class="w-8 h-6 mx-auto mb-2">
                                                                <svg class="w-full h-full text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                    <rect x="4" y="6" width="16" height="12" rx="2" fill="currentColor" fill-opacity="0.1"/>
                                                                    <rect x="4" y="6" width="16" height="12" rx="2"/>
                                                                    <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                                                                    <circle cx="7" cy="9" r="1" fill="currentColor"/>
                                                                    <circle cx="17" cy="9" r="1" fill="currentColor"/>
                                                                    <circle cx="7" cy="15" r="1" fill="currentColor"/>
                                                                    <circle cx="17" cy="15" r="1" fill="currentColor"/>
                                                                </svg>
                                                            </div>
                                                            <span class="text-xs text-gray-600">Rectangular</span>
                                                        </div>
                                                    </label>
                                                    
                                                    <!-- Oval Table -->
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" name="tableShape" value="oval" class="sr-only">
                                                        <div class="border-2 border-gray-300 rounded-full p-3 text-center hover:border-orange-500 transition-colors table-shape-option" data-shape="oval">
                                                            <div class="w-8 h-6 mx-auto mb-2">
                                                                <svg class="w-full h-full text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                    <ellipse cx="12" cy="12" rx="10" ry="6" fill="currentColor" fill-opacity="0.1"/>
                                                                    <ellipse cx="12" cy="12" rx="10" ry="6"/>
                                                                    <ellipse cx="12" cy="12" rx="3" ry="2" fill="currentColor"/>
                                                                    <circle cx="6" cy="8" r="1" fill="currentColor"/>
                                                                    <circle cx="18" cy="8" r="1" fill="currentColor"/>
                                                                    <circle cx="6" cy="16" r="1" fill="currentColor"/>
                                                                    <circle cx="18" cy="16" r="1" fill="currentColor"/>
                                                                </svg>
                                                            </div>
                                                            <span class="text-xs text-gray-600">Oval</span>
                                                        </div>
                                                    </label>
                                                    
                                                    <!-- Square Table -->
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" name="tableShape" value="square" class="sr-only">
                                                        <div class="border-2 border-gray-300 rounded-lg p-3 text-center hover:border-orange-500 transition-colors table-shape-option" data-shape="square">
                                                            <div class="w-8 h-8 mx-auto mb-2">
                                                                <svg class="w-full h-full text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                    <path d="M4 8h16v8H4z" fill="currentColor" fill-opacity="0.1"/>
                                                                    <path d="M4 8h16v8H4z"/>
                                                                    <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                                                                    <circle cx="6" cy="6" r="1" fill="currentColor"/>
                                                                    <circle cx="18" cy="6" r="1" fill="currentColor"/>
                                                                    <circle cx="6" cy="18" r="1" fill="currentColor"/>
                                                                    <circle cx="18" cy="18" r="1" fill="currentColor"/>
                                                                </svg>
                                                            </div>
                                                            <span class="text-xs text-gray-600">Square</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <!-- Capacity Selection -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Seating Capacity</label>
                                                <select id="tableCapacity" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                                    <option value="2">2 seats</option>
                                                    <option value="4" selected>4 seats</option>
                                                    <option value="6">6 seats</option>
                                                    <option value="8">8 seats</option>
                                                    <option value="10">10 seats</option>
                                                    <option value="12">12 seats</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tables Grid -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="tablesGrid">
                                        <!-- Tables will be dynamically added here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button onclick="closeTableManager()" 
                                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            loadTables();
            
            // Add shape selection functionality
            setupShapeSelection();
        }
        
        function setupShapeSelection() {
            // Handle shape selection visual feedback
            document.querySelectorAll('.table-shape-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    document.querySelectorAll('.table-shape-option').forEach(opt => {
                        opt.classList.remove('border-orange-500', 'bg-orange-50');
                        opt.classList.add('border-gray-300');
                    });
                    
                    // Add selected class to clicked option
                    this.classList.remove('border-gray-300');
                    this.classList.add('border-orange-500', 'bg-orange-50');
                    
                    // Check the corresponding radio button
                    const shape = this.dataset.shape;
                    document.querySelector(`input[name="tableShape"][value="${shape}"]`).checked = true;
                });
            });
            
            // Set initial selection
            document.querySelector('.table-shape-option[data-shape="round"]').classList.add('border-orange-500', 'bg-orange-50');
        }

        function closeTableManager() {
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) {
                modal.remove();
            }
        }

        function addTable() {
            const tableName = document.getElementById('newTableName').value.trim();
            if (!tableName) return;
            
            // Get selected shape
            const selectedShape = document.querySelector('input[name="tableShape"]:checked').value;
            
            // Get selected capacity
            const selectedCapacity = parseInt(document.getElementById('tableCapacity').value);
            
            const tables = JSON.parse(localStorage.getItem('restaurantTables') || '[]');
            const newTable = {
                id: Date.now(),
                name: tableName,
                status: 'free',
                capacity: selectedCapacity,
                shape: selectedShape,
                type: 'standard',
                description: '',
                totalAmount: 0,
                orders: [],
                createdAt: new Date().toISOString()
            };
            
            tables.push(newTable);
            localStorage.setItem('restaurantTables', JSON.stringify(tables));
            
            // Clear form
            document.getElementById('newTableName').value = '';
            document.querySelector('input[name="tableShape"][value="round"]').checked = true;
            document.getElementById('tableCapacity').value = '4';
            
            loadTables();
        }

        function loadTables() {
            const tables = JSON.parse(localStorage.getItem('restaurantTables') || '[]');
            const grid = document.getElementById('tablesGrid');
            
            if (!grid) return;
            
            grid.innerHTML = tables.map(table => `
                <div class="bg-white border-2 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                     style="border-color: ${table.status === 'occupied' ? '#ef4444' : '#10b981'}; background-color: ${table.status === 'occupied' ? '#fef2f2' : '#f0fdf4'};">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-lg font-semibold text-gray-900">${table.name}</h4>
                        <div class="w-3 h-3 rounded-full" style="background-color: ${table.status === 'occupied' ? '#ef4444' : '#10b981'}"></div>
                    </div>
                    
                    <!-- Table Shape Icon -->
                    <div class="flex justify-center mb-2">
                        ${getTableShapeIcon(table.shape || 'square')}
                    </div>
                    <div class="text-sm text-gray-600 mb-3">
                        <div class="flex items-center justify-between">
                            <span>${table.status === 'occupied' ? 'Occupied' : 'Available'}</span>
                            <span class="text-xs text-gray-500">${table.capacity || 4} seats</span>
                        </div>
                        ${table.status === 'occupied' ? `<div class="mt-1 font-semibold">Total: ₹${table.totalAmount.toFixed(2)}</div>` : ''}
                        ${table.shape ? `<div class="text-xs text-gray-500 mt-1">${table.shape.charAt(0).toUpperCase() + table.shape.slice(1)} table</div>` : ''}
                    </div>
                    <div class="flex gap-2">
                        <button onclick="toggleTableStatus(${table.id})" 
                                class="flex-1 px-3 py-2 text-white text-sm font-medium rounded-md transition-colors"
                                style="background-color: ${table.status === 'occupied' ? '#ef4444' : '#10b981'};"
                                onmouseover="this.style.backgroundColor='${table.status === 'occupied' ? '#dc2626' : '#059669'}'"
                                onmouseout="this.style.backgroundColor='${table.status === 'occupied' ? '#ef4444' : '#10b981'}'">
                            ${table.status === 'occupied' ? 'Free' : 'Occupy'}
                        </button>
                        <button onclick="deleteTable(${table.id})" 
                                class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `).join('');
        }
        
        function getTableShapeIcon(shape) {
            const iconSize = 'w-8 h-8';
            const iconColor = 'text-gray-600';
            
            switch(shape) {
                case 'round':
                    return `<svg class="${iconSize} ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="8" fill="currentColor" fill-opacity="0.1"/>
                        <circle cx="12" cy="12" r="8"/>
                        <circle cx="12" cy="12" r="2" fill="currentColor"/>
                        <circle cx="8" cy="8" r="1" fill="currentColor"/>
                        <circle cx="16" cy="8" r="1" fill="currentColor"/>
                        <circle cx="8" cy="16" r="1" fill="currentColor"/>
                        <circle cx="16" cy="16" r="1" fill="currentColor"/>
                    </svg>`;
                    
                case 'rectangular':
                    return `<svg class="${iconSize} ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="4" y="6" width="16" height="12" rx="2" fill="currentColor" fill-opacity="0.1"/>
                        <rect x="4" y="6" width="16" height="12" rx="2"/>
                        <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                        <circle cx="7" cy="9" r="1" fill="currentColor"/>
                        <circle cx="17" cy="9" r="1" fill="currentColor"/>
                        <circle cx="7" cy="15" r="1" fill="currentColor"/>
                        <circle cx="17" cy="15" r="1" fill="currentColor"/>
                    </svg>`;
                    
                case 'oval':
                    return `<svg class="${iconSize} ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <ellipse cx="12" cy="12" rx="10" ry="6" fill="currentColor" fill-opacity="0.1"/>
                        <ellipse cx="12" cy="12" rx="10" ry="6"/>
                        <ellipse cx="12" cy="12" rx="3" ry="2" fill="currentColor"/>
                        <circle cx="6" cy="8" r="1" fill="currentColor"/>
                        <circle cx="18" cy="8" r="1" fill="currentColor"/>
                        <circle cx="6" cy="16" r="1" fill="currentColor"/>
                        <circle cx="18" cy="16" r="1" fill="currentColor"/>
                    </svg>`;
                    
                default: // square
                    return `<svg class="${iconSize} ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M4 8h16v8H4z" fill="currentColor" fill-opacity="0.1"/>
                        <path d="M4 8h16v8H4z"/>
                        <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                        <circle cx="6" cy="6" r="1" fill="currentColor"/>
                        <circle cx="18" cy="6" r="1" fill="currentColor"/>
                        <circle cx="6" cy="18" r="1" fill="currentColor"/>
                        <circle cx="18" cy="18" r="1" fill="currentColor"/>
                    </svg>`;
            }
        }

        function toggleTableStatus(tableId) {
            const tables = JSON.parse(localStorage.getItem('restaurantTables') || '[]');
            const table = tables.find(t => t.id === tableId);
            if (table) {
                table.status = table.status === 'occupied' ? 'free' : 'occupied';
                if (table.status === 'free') {
                    table.totalAmount = 0;
                    table.orders = [];
                }
                localStorage.setItem('restaurantTables', JSON.stringify(tables));
                loadTables();
            }
        }

        function deleteTable(tableId) {
            if (confirm('Are you sure you want to delete this table?')) {
                const tables = JSON.parse(localStorage.getItem('restaurantTables') || '[]');
                const updatedTables = tables.filter(t => t.id !== tableId);
                localStorage.setItem('restaurantTables', JSON.stringify(updatedTables));
                loadTables();
            }
        }
    </script>
</body>
</html>