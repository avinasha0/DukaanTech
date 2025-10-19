<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Dashboard') - {{ !empty($tenant->name) ? $tenant->name : $tenant->website }} - Dukaantech POS</title>
    <meta name="description" content="@yield('description', 'Complete restaurant management solution with POS, inventory management, staff scheduling, and analytics. Streamline your restaurant operations with Dukaantech POS.')">
    <meta name="keywords" content="@yield('keywords', 'restaurant POS, point of sale, restaurant management, inventory management, staff scheduling, restaurant analytics, food service, restaurant software, POS system, restaurant technology, Dukaantech')">
    <meta name="author" content="Dukaantech">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'Dashboard') - {{ !empty($tenant->name) ? $tenant->name : $tenant->website }} - Dukaantech POS">
    <meta property="og:description" content="@yield('description', 'Complete restaurant management solution with POS, inventory management, staff scheduling, and analytics. Streamline your restaurant operations with Dukaantech POS.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="Dukaantech POS">
    <meta property="og:locale" content="en_US">
    @if(isset($tenant->logo_url) && $tenant->logo_url)
    <meta property="og:image" content="{{ $tenant->logo_url }}">
    @else
    <meta property="og:image" content="{{ url('/images/og-image.jpg') }}">
    @endif
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Dashboard') - {{ !empty($tenant->name) ? $tenant->name : $tenant->website }} - Dukaantech POS">
    <meta name="twitter:description" content="@yield('description', 'Complete restaurant management solution with POS, inventory management, staff scheduling, and analytics. Streamline your restaurant operations with Dukaantech POS.')">
    @if(isset($tenant->logo_url) && $tenant->logo_url)
    <meta name="twitter:image" content="{{ $tenant->logo_url }}">
    @else
    <meta name="twitter:image" content="{{ url('/images/og-image.jpg') }}">
    @endif
    
    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="#6E46AE">
    <meta name="msapplication-TileColor" content="#6E46AE">
    <meta name="application-name" content="Dukaantech POS">
    <meta name="apple-mobile-web-app-title" content="Dukaantech POS">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ request()->url() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <link rel="apple-touch-icon" href="/favicon.png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'royal-purple': '#6E46AE',
                        'tiffany-blue': '#00B6B4'
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: false, profileDropdownOpen: false }">
    <div class="flex h-screen">
        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64" 
             x-data="{ sidebarCollapsed: false }" 
             x-init="
                sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                updateMargin();
                
                // Listen for sidebar collapse events
                window.addEventListener('sidebar-collapsed', (e) => {
                    sidebarCollapsed = e.detail.collapsed;
                    updateMargin();
                });
                
                function updateMargin() {
                    if (sidebarCollapsed) { 
                        $el.classList.remove('lg:ml-64'); 
                        $el.classList.add('lg:ml-20'); 
                    } else { 
                        $el.classList.remove('lg:ml-20'); 
                        $el.classList.add('lg:ml-64'); 
                    }
                }
             ">
            {{-- Top Navigation Bar --}}
            <header class="bg-white shadow-sm border-b border-gray-200 lg:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    {{-- Hamburger Menu Button --}}
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    {{-- Favicon/Brand --}}
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center">
                            <img src="/favicon.png" alt="Favicon" class="w-full h-full object-cover">
                        </div>
                        <h1 class="text-lg font-bold text-gray-900 font-dm">DukaanTech</h1>
                    </div>
                    
                    {{-- User Menu --}}
                    <div class="relative" x-data="{ profileDropdownOpen: false }">
                        {{-- Profile Button --}}
                        <button @click="profileDropdownOpen = !profileDropdownOpen" 
                                @click.away="profileDropdownOpen = false"
                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="profileDropdownOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-72 sm:w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 max-w-[calc(100vw-2rem)]"
                             style="display: none;">
                            
                            {{-- User Info Header --}}
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Menu Items --}}
                            <div class="py-1">
                                {{-- Email --}}
                                <div class="px-4 py-2">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Email</p>
                                            <p class="text-sm text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Plan --}}
                                <div class="px-4 py-2">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Plan</p>
                                            <p class="text-sm text-gray-900">Free Trial</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Divider --}}
                                <div class="border-t border-gray-100 my-1"></div>

                                {{-- Logout --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 flex items-center space-x-3 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    {{-- Mobile Sidebar --}}
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg lg:hidden">
        
        {{-- Mobile Sidebar Content --}}
        <div class="flex flex-col h-full">
            {{-- Header Section --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center">
                        <img src="/favicon.png" alt="Favicon" class="w-full h-full object-cover">
                    </div>
                    <h1 class="text-lg font-bold text-gray-900 font-dm">DukaanTech</h1>
                </div>
                
                {{-- Close Button --}}
                <button @click="sidebarOpen = false" 
                        class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Navigation Menu (Same as desktop sidebar) --}}
            <nav class="flex-1 overflow-y-auto py-4">
                <div class="px-3 space-y-1">
                    
                    {{-- Dashboard Section --}}
                    <div class="mb-6">
                        <div class="space-y-1">
                            {{-- Dashboard --}}
                            <a href="{{ route('tenant.dashboard', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.dashboard') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                                </svg>
                                <span class="sidebar-text">Dashboard</span>
                            </a>
                        </div>
                    </div>

                    {{-- Order Management Section --}}
                    <div class="mb-6">
                        <div class="px-3 mb-3">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Order Management</h3>
                        </div>
                        
                        <div class="space-y-1">
                            {{-- New Order --}}
                            <a href="{{ route('tenant.pos.terminal', $tenant->slug) }}" 
                               target="_blank"
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.pos.terminal') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span class="sidebar-text">Create Order</span>
                            </a>

                            {{-- View Orders --}}
                            <a href="{{ route('tenant.orders', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.orders') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="sidebar-text">View Orders</span>
                            </a>

                            {{-- KOT Dashboard --}}
                            <a href="{{ route('tenant.kot.public', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.kot.public') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="sidebar-text">View KOT</span>
                            </a>
                        </div>
                    </div>

                    {{-- Menu Management Section --}}
                    <div class="mb-6">
                        <div class="px-3 mb-3">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menu Management</h3>
                        </div>
                        
                        <div class="space-y-1">
                            {{-- Manage Menu --}}
                            <a href="{{ route('tenant.menu.path', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.menu.path') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="sidebar-text">Manage Menu</span>
                            </a>

                            {{-- Manage Discounts --}}
                            <a href="{{ route('tenant.discounts.index', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.discounts.*') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span class="sidebar-text">Manage Discounts</span>
                            </a>

                            {{-- QR Codes --}}
                            <a href="{{ route('tenant.qr-codes', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.qr-codes') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                <span class="sidebar-text">Manage QR Codes</span>
                            </a>
                        </div>
                    </div>

                    {{-- System Configuration Section --}}
                    <div class="mb-6">
                        <div class="px-3 mb-3">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">System Configuration</h3>
                        </div>
                        
                        <div class="space-y-1">
                            {{-- Settings --}}
                            <a href="{{ route('tenant.settings', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.settings.*') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="sidebar-text">Settings</span>
                            </a>

                            {{-- Manage Terminals --}}
                            <a href="{{ route('tenant.users.index', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.users.*') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="sidebar-text">Manage Terminals</span>
                            </a>

                            {{-- Manage Tables --}}
                            <a href="{{ route('tenant.tables.index', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.tables.*') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="sidebar-text">Manage Tables</span>
                            </a>

                            {{-- Manage Users (Admin Only) --}}
                            @can('manage-users')
                            <a href="{{ route('tenant.users.index', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.users.*') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <span class="sidebar-text">Manage Users</span>
                            </a>
                            @endcan

                            {{-- Manage Roles (Admin Only) --}}
                            @can('manage-roles')
                            <a href="{{ route('tenant.roles.index', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.roles.*') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span class="sidebar-text">Manage Roles</span>
                            </a>
                            @endcan
                        </div>
                    </div>

                    {{-- Analytics & Reports Section --}}
                    <div class="mb-6">
                        <div class="px-3 mb-3">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Analytics & Reports</h3>
                        </div>
                        
                        <div class="space-y-1">
                            {{-- View Reports --}}
                            <a href="{{ route('tenant.reports', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.reports.*') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span class="sidebar-text">View Reports</span>
                            </a>

                            {{-- View Analytics --}}
                            <a href="{{ route('tenant.analytics', $tenant->slug) }}" 
                               class="sidebar-item group"
                               :class="{ 'sidebar-item-active': isActiveRoute('tenant.analytics.*') }"
                               @click="sidebarOpen = false">
                                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span class="sidebar-text">View Analytics</span>
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Footer Section --}}
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-royal-purple to-tiffany-blue rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                
                {{-- Profile and Logout Options --}}
                <div class="space-y-1">
                    {{-- Profile --}}
                    <a href="{{ route('tenant.profile.edit', $tenant->slug) }}" 
                       class="sidebar-item group"
                       :class="{ 'sidebar-item-active': isActiveRoute('tenant.profile.*') }"
                       @click="sidebarOpen = false">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="sidebar-text">Profile</span>
                    </a>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="sidebar-item group w-full text-left"
                                @click="sidebarOpen = false">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="sidebar-text">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
