<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Role - {{ $role->name }} - {{ $tenant->name }} - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-red-50 min-h-screen">
    <!-- Header -->
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

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-8">
                    @php
                        $isPathBased = !str_contains(request()->getHost(), '.');
                        $dashboardRoute = 'tenant.dashboard';
                        $posRoute = 'tenant.pos.terminal';
                        $kotRoute = 'tenant.kot.public';
                    @endphp
                    <a href="{{ route($dashboardRoute, ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-orange-600 transition-colors">Dashboard</a>
                    <a href="{{ route('tenant.pos.terminal', ['tenant' => $tenant->slug]) }}" target="_blank" class="text-gray-700 hover:text-orange-600 transition-colors">POS Register</a>
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
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">{{ $userRole->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-orange-600 transition-colors text-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="min-h-screen py-12">
        <div class="mx-auto max-w-7xl px-4">
        <!-- Header -->
        <div class="mb-6 lg:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2 font-dm">{{ $role->name }}</h1>
                    <p class="text-sm lg:text-base text-gray-600">{{ $role->description ?: 'Role details and assignments' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('tenant.roles.index', ['tenant' => $tenant->slug]) }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Back</a>
                    <a href="{{ route('tenant.roles.edit', ['tenant' => $tenant->slug, 'role' => $role]) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl">Edit Role</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Permissions -->
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Permissions ({{ $role->permissions->count() }})</h3>
                </div>
                <div class="px-6 py-6">
                    @if($role->permissions->count() > 0)
                        <div class="space-y-4">
                            @foreach($role->permissions->groupBy('module') as $module => $modulePermissions)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800 mb-2 capitalize">{{ $module }}</h4>
                                    <div class="space-y-2">
                                        @foreach($modulePermissions as $permission)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                                    @if($permission->description)
                                                        <div class="text-xs text-gray-500">{{ $permission->description }}</div>
                                                    @endif
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Assigned
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No permissions assigned to this role.</p>
                    @endif
                </div>
            </div>

            <!-- Users -->
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Users ({{ $role->users->count() }})</h3>
                </div>
                <div class="px-6 py-6">
                    @if($role->users->count() > 0)
                        <div class="space-y-3">
                            @foreach($role->users as $user)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center">
                                                <span class="text-orange-600 font-semibold text-xs">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No users assigned to this role.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Role Information -->
        <div class="mt-8 bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Role Information</h3>
            </div>
            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Role Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $role->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->created_at->format('M d, Y \a\t g:i A') }}</dd>
                    </div>
                    @if($role->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $role->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
        </div>
    </div>
    </div>
</body>
</html>