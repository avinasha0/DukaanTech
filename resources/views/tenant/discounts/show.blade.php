<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Discount Details - {{ $tenant->name }} - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-teal-50 min-h-screen">
    {{-- Header Component --}}
    <div class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 py-4">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center">
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
                    <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-green-600 transition-colors">Dashboard</a>
                    <a href="{{ route('taxes.index', ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-green-600 transition-colors">Taxes</a>
                    <a href="{{ route('tenant.discounts.index', ['tenant' => $tenant->slug]) }}" class="text-green-600 font-semibold">Discounts</a>
                    <a href="{{ route('tax-groups.index', ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-green-600 transition-colors">Tax Groups</a>
                </nav>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 lg:gap-4 w-full lg:w-auto">
                    <div class="text-sm text-gray-600">
                        Welcome, <span class="font-medium">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-green-600 transition-colors text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="min-h-screen py-10">
        <div class="mx-auto max-w-3xl px-4">
            {{-- Breadcrumb Navigation --}}
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="hover:text-green-600">Dashboard</a></li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('tenant.discounts.index', ['tenant' => $tenant->slug]) }}" class="hover:text-green-600">Discounts</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-900 font-medium">Discount Details</span>
                    </li>
                </ol>
            </nav>

            <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ $discount->name }}</h1>
                        <div class="text-gray-600">Code: <span class="font-mono">{{ $discount->code }}</span></div>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $discount->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $discount->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Type</div>
                        <div class="text-gray-900 font-medium capitalize">{{ str_replace('_', ' ', $discount->type) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Value</div>
                        <div class="text-gray-900 font-medium">
                            @if($discount->type === 'percentage')
                                {{ $discount->value }}%
                            @elseif($discount->type === 'fixed_amount')
                                ${{ number_format($discount->value, 2) }}
                            @else
                                Buy {{ $discount->buy_quantity }} Get {{ $discount->get_quantity }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Validity</div>
                        <div class="text-gray-900 font-medium">
                            @if($discount->valid_from)
                                From {{ optional($discount->valid_from)->format('M d, Y') }}
                            @endif
                            @if($discount->valid_until)
                                to {{ optional($discount->valid_until)->format('M d, Y') }}
                            @endif
                            @if(!$discount->valid_from && !$discount->valid_until)
                                No expiry
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Usage</div>
                        <div class="text-gray-900 font-medium">
                            @if($discount->usage_limit)
                                {{ $discount->usage_count }} / {{ $discount->usage_limit }}
                            @else
                                {{ $discount->usage_count }} used
                            @endif
                        </div>
                    </div>
                </div>

                @if($discount->description)
                    <div class="mt-6">
                        <div class="text-sm text-gray-500 mb-1">Description</div>
                        <div class="text-gray-900">{{ $discount->description }}</div>
                    </div>
                @endif

                <div class="mt-8 flex items-center gap-3">
                    <a href="{{ route('tenant.discounts.edit', ['tenant' => $tenant->slug, 'discount' => $discount]) }}" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">Edit</a>
                    <form method="POST" action="{{ route('tenant.discounts.toggle', ['tenant' => $tenant->slug, 'discount' => $discount]) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition">
                            {{ $discount->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('tenant.discounts.destroy', ['tenant' => $tenant->slug, 'discount' => $discount]) }}" class="inline" onsubmit="return confirm('Delete this discount?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


