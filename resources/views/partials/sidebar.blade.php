{{-- Desktop Sidebar Component --}}
<div x-data="sidebar()" 
     class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 lg:bg-white lg:shadow-lg lg:border-r lg:border-gray-200"
     :class="{
         'lg:w-64': !isCollapsed,
         'lg:w-20': isCollapsed
     }"
     x-init="init()">
    
    {{-- Desktop Sidebar Content --}}
    <div class="flex flex-col h-full">
        {{-- Header Section --}}
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center space-x-3" :class="{ 'justify-center': isCollapsed }">
                {{-- Favicon --}}
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center">
                        <img src="/favicon.png" alt="Favicon" class="w-full h-full object-cover">
                    </div>
                </div>
                
                {{-- Brand Name --}}
                <div x-show="!isCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <h1 class="text-lg font-bold text-gray-900 font-dm">Dukaantech</h1>
                </div>
            </div>
            
            {{-- Collapse Toggle --}}
            <button @click="toggleCollapse()" 
                    class="flex items-center justify-center w-8 h-8 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                    :title="isCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{ 'rotate-180': isCollapsed }">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 overflow-y-auto py-4">
            <div class="px-3 space-y-1">
                
                {{-- Dashboard Section --}}
                <div class="mb-6">
                    <div class="space-y-1">
                        {{-- Dashboard --}}
                        <a href="{{ route('tenant.dashboard', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.dashboard') }"
                           :title="isCollapsed ? 'Dashboard' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Dashboard</span>
                        </a>
                    </div>
                </div>

                {{-- Order Management Section --}}
                <div class="mb-6">
                    <div class="px-3 mb-3">
                        <h3 x-show="!isCollapsed" class="sidebar-section-title">Order Management</h3>
                    </div>
                    
                    <div class="space-y-1">
                        {{-- New Order --}}
                        <a href="{{ route('tenant.pos.terminal', $tenant->slug) }}" 
                           target="_blank"
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.pos.terminal') }"
                           :title="isCollapsed ? 'Create Order' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Create Order</span>
                        </a>

                        {{-- View Orders --}}
                        <a href="{{ route('tenant.orders', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.orders') }"
                           :title="isCollapsed ? 'View Orders' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">View Orders</span>
                        </a>

                        {{-- KOT Dashboard --}}
                        <a href="{{ route('tenant.kot.public', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.kot.public') }"
                           :title="isCollapsed ? 'View KOT' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">View KOT</span>
                        </a>
                    </div>
                </div>

                {{-- Menu Management Section --}}
                <div class="mb-6">
                    <div class="px-3 mb-3">
                        <h3 x-show="!isCollapsed" class="sidebar-section-title">Menu Management</h3>
                    </div>
                    
                    <div class="space-y-1">
                        {{-- Manage Menu --}}
                        <a href="{{ route('tenant.menu.path', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.menu.path') }"
                           :title="isCollapsed ? 'Manage Menu' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Manage Menu</span>
                        </a>

                        {{-- Manage Discounts --}}
                        <a href="{{ route('tenant.discounts.index', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.discounts.*') }"
                           :title="isCollapsed ? 'Manage Discounts' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Manage Discounts</span>
                        </a>

                        {{-- QR Codes --}}
                        <a href="{{ route('tenant.qr-codes', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.qr-codes') }"
                           :title="isCollapsed ? 'Manage QR Codes' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Manage QR Codes</span>
                        </a>
                    </div>
                </div>

                {{-- System Configuration Section --}}
                <div class="mb-6">
                    <div class="px-3 mb-3">
                        <h3 x-show="!isCollapsed" class="sidebar-section-title">System Configuration</h3>
                    </div>
                    
                    <div class="space-y-1">
                        {{-- Settings --}}
                        <a href="{{ route('tenant.settings', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.settings.*') }"
                           :title="isCollapsed ? 'Settings' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Settings</span>
                        </a>

                        {{-- Manage Terminals --}}
                        <a href="{{ route('tenant.users.index', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.users.*') }"
                           :title="isCollapsed ? 'Manage Terminals' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Manage Terminals</span>
                        </a>

                        {{-- Manage Tables --}}
                        <a href="{{ route('tenant.tables.index', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.tables.*') }"
                           :title="isCollapsed ? 'Manage Tables' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Manage Tables</span>
                        </a>

                        {{-- Manage Users (Admin Only) --}}
                        @can('manage-users')
                        <a href="{{ route('tenant.users.index', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.users.*') }"
                           :title="isCollapsed ? 'Manage Users' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Manage Users</span>
                        </a>
                        @endcan

                        {{-- Manage Roles (Admin Only) --}}
                        @can('manage-roles')
                        <a href="{{ route('tenant.roles.index', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.roles.*') }"
                           :title="isCollapsed ? 'Manage Roles' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">Manage Roles</span>
                        </a>
                        @endcan
                    </div>
                </div>

                {{-- Analytics & Reports Section --}}
                <div class="mb-6">
                    <div class="px-3 mb-3">
                        <h3 x-show="!isCollapsed" class="sidebar-section-title">Analytics & Reports</h3>
                    </div>
                    
                    <div class="space-y-1">
                        {{-- View Reports --}}
                        <a href="{{ route('tenant.reports', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.reports.*') }"
                           :title="isCollapsed ? 'View Reports' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">View Reports</span>
                        </a>

                        {{-- View Analytics --}}
                        <a href="{{ route('tenant.analytics', $tenant->slug) }}" 
                           class="sidebar-item group"
                           :class="{ 'sidebar-item-active': isActiveRoute('tenant.analytics.*') }"
                           :title="isCollapsed ? 'View Analytics' : ''">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span x-show="!isCollapsed" class="sidebar-text">View Analytics</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Footer Section --}}
        {{-- User Profile Section --}}
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center space-x-3 mb-4" :class="{ 'justify-center': isCollapsed }">
                <div class="w-8 h-8 bg-gradient-to-br from-royal-purple to-tiffany-blue rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div x-show="!isCollapsed" class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            
            {{-- Profile and Logout Options --}}
            <div class="space-y-1">
                {{-- Profile --}}
                <a href="{{ route('profile.edit') }}" 
                   class="sidebar-item group"
                   :class="{ 'sidebar-item-active': isActiveRoute('profile.*') }"
                   :title="isCollapsed ? 'Profile' : ''">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span x-show="!isCollapsed" class="sidebar-text">Profile</span>
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="sidebar-item group w-full text-left"
                            :title="isCollapsed ? 'Logout' : ''">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span x-show="!isCollapsed" class="sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Sidebar Styles --}}
<style>
.sidebar-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
}

.sidebar-item:hover {
    background-color: #f3f4f6;
    color: #111827;
}

.sidebar-item-active {
    background-color: #eff6ff;
    color: #1d4ed8;
    border-right: 2px solid #1d4ed8;
}

.sidebar-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

.sidebar-text {
    margin-left: 0.75rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sidebar-item:hover .sidebar-icon {
    color: #1d4ed8;
}

.sidebar-item-active .sidebar-icon {
    color: #1d4ed8;
}

.sidebar-item:hover .sidebar-text {
    color: #1d4ed8;
}

.sidebar-item-active .sidebar-text {
    color: #1d4ed8;
}

/* Additional modern styling */
.sidebar-section-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    margin-top: 1rem;
}

.sidebar-item-group {
    margin-bottom: 0.25rem;
}

.sidebar-item {
    margin-bottom: 0.125rem;
}

.sidebar-item:last-child {
    margin-bottom: 0;
}

/* Collapse button styling */
.sidebar-collapse-btn {
    transition: transform 0.2s ease-in-out;
}

.sidebar-collapse-btn:hover {
    background-color: #f3f4f6;
}

.sidebar-collapsed .sidebar-collapse-btn {
    transform: rotate(180deg);
}

/* Logout button styling */
.sidebar-item button {
    background: none;
    border: none;
    padding: 0;
    margin: 0;
    font: inherit;
    color: inherit;
    text-decoration: none;
    cursor: pointer;
}

.sidebar-item button:hover {
    background-color: #f3f4f6;
    color: #111827;
}

.sidebar-item button:hover .sidebar-icon {
    color: #1d4ed8;
}

.sidebar-item button:hover .sidebar-text {
    color: #1d4ed8;
}
</style>

{{-- Sidebar JavaScript --}}
<script>
function sidebar() {
    return {
        isCollapsed: false,
        
        init() {
            // Load collapsed state from localStorage
            this.isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        },
        
        toggleCollapse() {
            this.isCollapsed = !this.isCollapsed;
            localStorage.setItem('sidebar-collapsed', this.isCollapsed);
            
            // Dispatch event to notify main content area
            window.dispatchEvent(new CustomEvent('sidebar-collapsed', {
                detail: { collapsed: this.isCollapsed }
            }));
        },
        
        isActiveRoute(routePattern) {
            const currentRoute = window.location.pathname;
            
            // Handle wildcard patterns
            if (routePattern.includes('*')) {
                const basePattern = routePattern.replace('*', '');
                return currentRoute.startsWith(basePattern);
            }
            
            // Handle exact matches
            return currentRoute === routePattern;
        }
    }
}
</script>
