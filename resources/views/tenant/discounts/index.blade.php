@extends('layouts.tenant')

@section('title', 'Discount Management')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2 font-dm">Discount Management</h1>
                <p class="text-sm lg:text-base text-gray-600">Create and manage promotional discounts and offers</p>
            </div>
            <a href="{{ route('tenant.discounts.create', ['tenant' => $tenant->slug]) }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-teal-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create Discount
            </a>
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

            {{-- Discounts Table --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-50 to-teal-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Discount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Value</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Validity</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usage</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($discounts as $discount)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-100 to-teal-100 flex items-center justify-center">
                                                    <span class="text-green-600 font-semibold text-lg">
                                                        {{ strtoupper(substr($discount->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $discount->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $discount->code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $discount->type === 'percentage' ? 'bg-blue-100 text-blue-800' : ($discount->type === 'fixed_amount' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                                {{ ucfirst(str_replace('_', ' ', $discount->type)) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @if($discount->type === 'percentage')
                                                {{ $discount->value }}%
                                            @elseif($discount->type === 'fixed_amount')
                                                ${{ number_format($discount->value, 2) }}
                                            @else
                                                Buy {{ $discount->buy_quantity }} Get {{ $discount->get_quantity }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @if($discount->valid_from && $discount->valid_until)
                                                <div class="text-xs">
                                                    <div>{{ \Carbon\Carbon::parse($discount->valid_from)->format('M d, Y') }}</div>
                                                    <div class="text-gray-500">to {{ \Carbon\Carbon::parse($discount->valid_until)->format('M d, Y') }}</div>
                                                </div>
                                            @elseif($discount->valid_from)
                                                <div class="text-xs">From {{ \Carbon\Carbon::parse($discount->valid_from)->format('M d, Y') }}</div>
                                            @elseif($discount->valid_until)
                                                <div class="text-xs">Until {{ \Carbon\Carbon::parse($discount->valid_until)->format('M d, Y') }}</div>
                                            @else
                                                <span class="text-gray-400">No expiry</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @if($discount->usage_limit)
                                                <div class="text-xs">
                                                    <div>{{ $discount->usage_count }} / {{ $discount->usage_limit }}</div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                                        <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ ($discount->usage_count / $discount->usage_limit) * 100 }}%"></div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $discount->usage_count }} used
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $discount->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $discount->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('tenant.discounts.show', ['tenant' => $tenant->slug, 'discount' => $discount]) }}" 
                                               class="text-green-600 hover:text-green-800 font-medium transition-colors">View</a>
                                            <a href="{{ route('tenant.discounts.edit', ['tenant' => $tenant->slug, 'discount' => $discount]) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Edit</a>
                                            <form method="POST" action="{{ route('tenant.discounts.toggle', ['tenant' => $tenant->slug, 'discount' => $discount]) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-800 font-medium transition-colors">
                                                    {{ $discount->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('tenant.discounts.destroy', ['tenant' => $tenant->slug, 'discount' => $discount]) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this discount?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition-colors">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 mb-2">No discounts found</p>
                                            <p class="text-gray-500 mb-4">Create your first discount to start offering promotions to customers.</p>
                                            <a href="{{ route('tenant.discounts.create', ['tenant' => $tenant->slug]) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                                                Create First Discount
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
            @if($discounts->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        {{ $discounts->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
