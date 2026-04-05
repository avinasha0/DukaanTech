{{-- resources/views/pages/unified-order-management.blade.php --}}
@extends('layouts.page')

@section('title', 'Unified Order Management - Dukaantech POS')
@section('meta')
<meta name="description" content="Dine-in, takeaway, and delivery from one dashboard. Digital menu, order status, and payments—efficiently managed.">
@endsection

@section('page_content')
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-4">
    <div class="max-w-3xl">
      <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
        </svg>
        Omnichannel orders
      </div>
      <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
        Unified
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">Order Management</span>
      </h1>
      <p class="text-xl text-gray-600 leading-relaxed mb-8">
        Seamlessly handle dine-in, takeaway, and delivery orders from one central dashboard. Manage your digital menu, track order status, and process payments efficiently.
      </p>
      <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('register') }}" class="inline-flex justify-center bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
          Start for free
        </a>
        <a href="{{ route('features') }}" class="inline-flex justify-center border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-lg text-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-colors">
          All features
        </a>
      </div>
    </div>
  </div>
</section>

<section class="py-16 lg:py-20 bg-white border-t border-gray-100">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-14">
      <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">One queue for every way guests order</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">Stop juggling tabs and tools—see every channel in one place.</p>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Dine-in, takeaway &amp; delivery</h3>
        <p class="text-gray-600">Same workflow for tables, counters, and riders—clear labels and handoff.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Digital menu</h3>
        <p class="text-gray-600">Keep items, prices, and modifiers consistent wherever orders originate.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Order status</h3>
        <p class="text-gray-600">Track each order from new to served or out for delivery—no guesswork.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 md:col-span-2 lg:col-span-3">
        <div class="max-w-2xl mx-auto text-center">
          <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5 mx-auto">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Payments</h3>
          <p class="text-gray-600">Settle checks quickly with the methods your guests expect, tied to the same order record.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-16 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-2xl lg:text-3xl font-bold text-white mb-4">One dashboard for every order type</h2>
    <p class="text-orange-100 mb-8 max-w-xl mx-auto">Start free and simplify how your team runs service.</p>
    <a href="{{ route('register') }}" class="inline-flex bg-white text-orange-600 px-8 py-3 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
      Start for free
    </a>
  </div>
</section>
@endsection
