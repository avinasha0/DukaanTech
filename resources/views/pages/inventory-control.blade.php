{{-- resources/views/pages/inventory-control.blade.php --}}
@extends('layouts.page')

@section('title', 'Smart Inventory Control - Dukaantech POS')
@section('meta')
<meta name="description" content="Automate inventory with real-time updates, low-stock alerts, consumption tracking, and reports to optimize food costs.">
@endsection

@section('page_content')
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-4">
    <div class="max-w-3xl">
      <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        Stock &amp; costing
      </div>
      <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
        Smart
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">Inventory Control</span>
      </h1>
      <p class="text-xl text-gray-600 leading-relaxed mb-8">
        Automate your inventory tracking with real-time updates. Get instant low-stock alerts, track consumption patterns, and generate detailed reports to optimize your food costs.
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
      <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Stay ahead of waste and stockouts</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">Know what you have, what you use, and what to order next—without spreadsheets.</p>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Real-time stock levels</h3>
        <p class="text-gray-600">Updates as you sell and receive goods so your team always works from current numbers.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Low-stock alerts</h3>
        <p class="text-gray-600">Get notified before you run out of critical ingredients—before the kitchen does.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Consumption patterns</h3>
        <p class="text-gray-600">See how fast items move by daypart and outlet so you can prep and purchase smarter.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 md:col-span-2 lg:col-span-1">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Reports for food cost control</h3>
        <p class="text-gray-600">Detailed inventory and usage reports help you trim waste and protect margins.</p>
      </div>
    </div>
  </div>
</section>

<section class="py-16 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-2xl lg:text-3xl font-bold text-white mb-4">Tighten control without extra admin</h2>
    <p class="text-orange-100 mb-8 max-w-xl mx-auto">Start free and bring inventory discipline to every outlet.</p>
    <a href="{{ route('register') }}" class="inline-flex bg-white text-orange-600 px-8 py-3 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
      Start for free
    </a>
  </div>
</section>
@endsection
