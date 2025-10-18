<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tax Management - {{ $tenant->name }} - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 via-white to-blue-50 min-h-screen">
    {{-- Header Component --}}
    <div class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 py-4">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center">
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
                    <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-purple-600 transition-colors">Dashboard</a>
                    <a href="{{ route('tenant.taxes.index', ['tenant' => $tenant->slug]) }}" class="text-purple-600 font-semibold">Taxes</a>
                    <a href="{{ route('tenant.discounts.index', ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-purple-600 transition-colors">Discounts</a>
                    <a href="{{ route('tenant.tax-groups.index', ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-purple-600 transition-colors">Tax Groups</a>
                </nav>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 lg:gap-4 w-full lg:w-auto">
                    <div class="text-sm text-gray-600">
                        Welcome, <span class="font-medium">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-purple-600 transition-colors text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="min-h-screen py-12">
        <div class="mx-auto max-w-7xl px-4">
            {{-- Page Header --}}
            <div class="mb-6 lg:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2 font-dm">Tax Management</h1>
                        <p class="text-sm lg:text-base text-gray-600">Configure tax rates, groups, and regional settings</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('tenant.tax-groups.create', ['tenant' => $tenant->slug]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Tax Groups
                        </a>
                        <a href="{{ route('tenant.taxes.create', ['tenant' => $tenant->slug]) }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Tax Rate
                        </a>
                    </div>
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 max-w-4xl mx-auto">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mt-0.5 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-green-800">Success!</h3>
                                <p class="text-green-700 mt-1">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 max-w-4xl mx-auto">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-600 mt-0.5 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-red-800">Error!</h3>
                                <p class="text-red-700 mt-1">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tax Rates Table --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-purple-50 to-blue-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tax Rate</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Group</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rate</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($taxRates as $taxRate)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-100 to-blue-100 flex items-center justify-center">
                                                    <span class="text-purple-600 font-semibold text-lg">
                                                        {{ strtoupper(substr($taxRate->name ?? $taxRate->code, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $taxRate->name ?? $taxRate->code }}</div>
                                                <div class="text-xs text-gray-500">{{ $taxRate->code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @if($taxRate->taxGroup)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $taxRate->taxGroup->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">No Group</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @if($taxRate->calculation_type === 'percentage')
                                                {{ $taxRate->rate }}%
                                            @else
                                                ${{ number_format($taxRate->fixed_amount ?? 0, 2) }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $taxRate->calculation_type === 'percentage' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($taxRate->calculation_type) }}
                                            </span>
                                            @if($taxRate->is_compound)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-1">
                                                    Compound
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $taxRate->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $taxRate->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('tenant.taxes.show', ['tenant' => $tenant->slug, 'tax' => $taxRate]) }}" 
                                               class="text-purple-600 hover:text-purple-800 font-medium transition-colors">View</a>
                                            <a href="{{ route('tenant.taxes.edit', ['tenant' => $tenant->slug, 'tax' => $taxRate]) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Edit</a>
                                            <form method="POST" action="{{ route('tenant.taxes.destroy', ['tenant' => $tenant->slug, 'tax' => $taxRate]) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this tax rate?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition-colors">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 mb-2">No tax rates found</p>
                                            <p class="text-gray-500 mb-4">Create your first tax rate to get started with tax management.</p>
                                            <a href="{{ route('tenant.taxes.create', ['tenant' => $tenant->slug]) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                                Create First Tax Rate
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if($taxRates->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        {{ $taxRates->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
