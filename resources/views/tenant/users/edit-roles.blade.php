<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Roles - {{ $tenant->name }} - Dukaantech POS</title>
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
                        <a href="{{ route('tenant.roles.index', ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-orange-600 transition-colors">Roles</a>
                    @endif
                    @if(Auth::user()->hasPermission('view-users'))
                        <a href="{{ route('tenant.users.index', ['tenant' => $tenant->slug]) }}" class="text-orange-600 font-semibold">Users</a>
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

    {{-- Main Content --}}
    <div class="min-h-screen py-12">
        <div class="mx-auto max-w-4xl px-4">
            {{-- Page Header --}}
            <div class="mb-6 lg:mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2 font-dm">Edit Roles for {{ $user->name }}</h1>
                        <p class="text-sm lg:text-base text-gray-600">Assign or remove roles for this user</p>
                    </div>
                    <a href="{{ route('tenant.users.index', ['tenant' => $tenant->slug]) }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Back</a>
                </div>
            </div>

            {{-- Form --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <form method="POST" action="{{ route('tenant.users.update-roles', ['tenant' => $tenant->slug, 'user' => $user]) }}">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-8">
                        {{-- User Information --}}
                        <div class="mb-8">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center">
                                        <span class="text-orange-600 font-semibold text-lg">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Current Roles --}}
                        @if($user->roles->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-700 mb-3">Current Roles</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Available Roles --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Roles</h3>
                            <p class="text-sm text-gray-600 mb-6">Select the roles to assign to this user.</p>

                            <div class="space-y-4">
                                @forelse($roles as $role)
                                    <label class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                               class="mt-1 h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                                               {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                                            @if($role->description)
                                                <div class="text-xs text-gray-500">{{ $role->description }}</div>
                                            @endif
                                            <div class="text-xs text-gray-400 mt-1">{{ $role->permissions->count() }} permissions</div>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-gray-500 text-center py-8">No roles available.</p>
                                @endforelse
                            </div>

                            @error('roles')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="px-6 py-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-4">
                        <a href="{{ route('tenant.users.index', ['tenant' => $tenant->slug]) }}" class="px-6 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 shadow-lg hover:shadow-xl">Update Roles</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
