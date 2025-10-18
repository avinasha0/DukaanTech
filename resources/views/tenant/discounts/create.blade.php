<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Discount - {{ $tenant->name }} - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('discountForm', () => ({
                type: 'percentage',
                showBxGy() { return this.type === 'buy_x_get_y' },
                showValue() { return this.type !== 'buy_x_get_y' },
            }))
        })
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function submitDisable(e){ 
            // Convert comma-separated inputs to arrays
            const itemsInput = document.querySelector('input[name="applicable_items_input"]');
            const orderTypesInput = document.querySelector('input[name="applicable_order_types_input"]');
            
            if (itemsInput.value.trim()) {
                const itemsArray = itemsInput.value.split(',').map(id => id.trim()).filter(id => id);
                document.getElementById('applicable_items_hidden').value = JSON.stringify(itemsArray);
            }
            
            if (orderTypesInput.value.trim()) {
                const orderTypesArray = orderTypesInput.value.split(',').map(slug => slug.trim()).filter(slug => slug);
                document.getElementById('applicable_order_types_hidden').value = JSON.stringify(orderTypesArray);
            }
            
            e.target.disabled = true; 
            e.target.form.submit(); 
        }
    </script>
    <style>[x-cloak]{ display:none !important; }</style>
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
                        <span class="text-gray-900 font-medium">Create Discount</span>
                    </li>
                </ol>
            </nav>

            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6">Create Discount</h1>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <ul class="list-disc list-inside text-red-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tenant.discounts.store', ['tenant' => $tenant->slug]) }}" x-data="discountForm()" class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input name="name" type="text" required class="w-full border-gray-300 rounded-lg" placeholder="Weekend Offer" value="{{ old('name') }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                        <input name="code" type="text" required class="w-full border-gray-300 rounded-lg" placeholder="WKND20" value="{{ old('code') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" x-model="type" class="w-full border-gray-300 rounded-lg">
                            <option value="percentage">Percentage</option>
                            <option value="fixed_amount">Fixed amount</option>
                            <option value="buy_x_get_y">Buy X Get Y</option>
                        </select>
                    </div>
                </div>

                <div x-show="showValue()" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                    <input name="value" type="number" step="0.01" min="0" class="w-full border-gray-300 rounded-lg" placeholder="e.g. 20 for 20% or 50 for $50" value="{{ old('value') }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="showBxGy()" x-cloak>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buy quantity</label>
                        <input name="buy_quantity" type="number" min="1" class="w-full border-gray-300 rounded-lg" value="{{ old('buy_quantity') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Get quantity</label>
                        <input name="get_quantity" type="number" min="1" class="w-full border-gray-300 rounded-lg" value="{{ old('get_quantity') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum order amount</label>
                        <input name="minimum_amount" type="number" step="0.01" min="0" class="w-full border-gray-300 rounded-lg" value="{{ old('minimum_amount') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum discount</label>
                        <input name="maximum_discount" type="number" step="0.01" min="0" class="w-full border-gray-300 rounded-lg" value="{{ old('maximum_discount') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valid from</label>
                        <input name="valid_from" type="date" class="w-full border-gray-300 rounded-lg" value="{{ old('valid_from') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valid until</label>
                        <input name="valid_until" type="date" class="w-full border-gray-300 rounded-lg" value="{{ old('valid_until') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Applicable items (IDs)</label>
                        <input name="applicable_items_input" type="text" class="w-full border-gray-300 rounded-lg" placeholder="e.g. 1,2,3" value="{{ old('applicable_items_input') }}">
                        <input type="hidden" name="applicable_items" id="applicable_items_hidden">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Applicable order types (slugs)</label>
                        <input name="applicable_order_types_input" type="text" class="w-full border-gray-300 rounded-lg" placeholder="e.g. dine-in,takeaway" value="{{ old('applicable_order_types_input') }}">
                        <input type="hidden" name="applicable_order_types" id="applicable_order_types_hidden">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Usage limit</label>
                        <input name="usage_limit" type="number" min="1" class="w-full border-gray-300 rounded-lg" value="{{ old('usage_limit') }}">
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-gray-800">Active</label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg" placeholder="Optional notes"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" onclick="submitDisable(event)" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all">Save Discount</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


