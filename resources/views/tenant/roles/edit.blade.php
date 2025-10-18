<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Role - {{ $tenant->name }} - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-red-50 min-h-screen">
    {{-- Header Component --}}
    <div class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 py-4">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg lg:text-xl font-bold text-gray-900">{{ $tenant->name }}</span>
                        <span class="text-sm text-gray-600 ml-1">POS</span>
                    </div>
                </a>

                {{-- Mobile Navigation Menu --}}
                <div x-data="{ mobileMenuOpen: false }" class="lg:hidden w-full">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Menu</span>
                        <svg class="w-5 h-5 text-gray-500" :class="mobileMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="mobileMenuOpen" x-transition class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                        <nav class="p-4 space-y-3">
                            @php
                                $isPathBased = !str_contains(request()->getHost(), '.');
                                $dashboardRoute = $isPathBased ? 'tenant.dashboard' : 'tenant.dashboard';
                                $posRoute = $isPathBased ? 'tenant.pos.terminal' : 'tenant.pos.terminal';
                                $kotRoute = 'tenant.kot.public';
                            @endphp
                            <a href="{{ route($dashboardRoute, ['tenant' => $tenant->slug]) }}" class="block text-gray-700 hover:text-orange-600 transition-colors py-2">Dashboard</a>
                            <a href="{{ route($posRoute, ['tenant' => $tenant->slug]) }}" target="_blank" class="block text-gray-700 hover:text-orange-600 transition-colors py-2">POS Register</a>
                            <a href="{{ route($kotRoute, ['tenant' => $tenant->slug]) }}" target="_blank" class="block text-gray-700 hover:text-orange-600 transition-colors py-2">KOT Dashboard</a>
                            <a href="{{ route('tenant.reports', ['tenant' => $tenant->slug]) }}" class="block text-gray-700 hover:text-orange-600 transition-colors py-2">Reports</a>
                            @if(Auth::user()->hasPermission('view-roles'))
                                <a href="{{ route('tenant.roles.index', ['tenant' => $tenant->slug]) }}" class="block text-orange-600 font-semibold py-2">Roles</a>
                            @endif
                            @if(Auth::user()->hasPermission('view-users'))
                                <a href="{{ route('tenant.users.index', ['tenant' => $tenant->slug]) }}" class="block text-gray-700 hover:text-orange-600 transition-colors py-2">Users</a>
                            @endif
                        </nav>
                    </div>
                </div>

                {{-- Desktop Navigation --}}
                <nav class="hidden lg:flex items-center gap-8">
                    @php
                        $isPathBased = !str_contains(request()->getHost(), '.');
                        $dashboardRoute = 'tenant.dashboard';
                        $posRoute = 'tenant.pos.terminal';
                        $kotRoute = 'tenant.kot.public';
                    @endphp
                    <a href="{{ route($dashboardRoute, ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-orange-600 transition-colors">Dashboard</a>
                    <a href="{{ route($posRoute, ['tenant' => $tenant->slug]) }}" target="_blank" class="text-gray-700 hover:text-orange-600 transition-colors">POS Register</a>
                    <a href="{{ route($kotRoute, ['tenant' => $tenant->slug]) }}" target="_blank" class="text-gray-700 hover:text-orange-600 transition-colors">KOT Dashboard</a>
                    <a href="{{ route('tenant.reports', ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-orange-600 transition-colors">Reports</a>
                    @if(Auth::user()->hasPermission('view-roles'))
                        <a href="{{ route('tenant.roles.index', ['tenant' => $tenant->slug]) }}" class="text-orange-600 font-semibold">Roles</a>
                    @endif
                    @if(Auth::user()->hasPermission('view-users'))
                        <a href="{{ route('tenant.users.index', ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-orange-600 transition-colors">Users</a>
                    @endif
                </nav>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 lg:gap-4 w-full lg:w-auto">
                    <div class="text-sm text-gray-600">
                        Welcome, <span class="font-medium">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->roles->count() > 0)
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-500">Role:</span>
                                @foreach(Auth::user()->roles as $userRole)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ $userRole->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-orange-600 transition-colors text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="min-h-screen py-12">
        <div class="mx-auto max-w-4xl px-4">
            {{-- Page Header --}}
            <div class="mb-6 lg:mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2 font-dm">Edit Role: {{ $role->name }}</h1>
                        <p class="text-sm lg:text-base text-gray-600">Update role information and permissions</p>
                    </div>
                    <a href="{{ route('tenant.roles.index', ['tenant' => $tenant->slug]) }}" 
                       class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Roles
                    </a>
                </div>
            </div>

            {{-- Form --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <form method="POST" action="{{ route('tenant.roles.update', ['tenant' => $tenant->slug, 'role' => $role]) }}">
                    @csrf
                    @method('PUT')
                <div class="px-6 py-6">
                    <!-- Role Information -->
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-300 @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-300 @enderror">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions</h3>
                        <p class="text-sm text-gray-600 mb-6">Select the permissions to assign to this role.</p>

                        @foreach($permissions as $module => $modulePermissions)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-800 mb-3 capitalize">{{ $module }} Permissions</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($modulePermissions as $permission)
                                        <label class="flex items-start">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                   class="mt-1 h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                                                   {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                                @if($permission->description)
                                                    <div class="text-xs text-gray-500">{{ $permission->description }}</div>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        @error('permissions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('tenant.roles.index', ['tenant' => $tenant->slug]) }}" 
                       class="px-6 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        Update Role
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
