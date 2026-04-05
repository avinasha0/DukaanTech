{{-- resources/views/pages/business-intelligence.blade.php --}}
@extends('layouts.page')

@section('title', 'Business Intelligence - Dukaantech POS')
@section('meta')
<meta name="description" content="Advanced analytics for restaurants: key metrics, trends, and decisions that improve profitability and efficiency.">
@endsection

@section('page_content')
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-4">
    <div class="max-w-3xl">
      <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        Performance &amp; insight
      </div>
      <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
        Business
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">Intelligence</span>
      </h1>
      <p class="text-xl text-gray-600 leading-relaxed mb-8">
        Gain deep insights into your restaurant's performance with advanced analytics. Monitor key metrics, identify trends, and make informed decisions to boost profitability and efficiency.
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
      <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">From raw numbers to next actions</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">Dashboards and reports built for operators, not spreadsheet jockeys.</p>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Key metrics</h3>
        <p class="text-gray-600">Sales, margin signals, labor ratios—surfaced so you can scan health at a glance.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Trends</h3>
        <p class="text-gray-600">Spot momentum early—busy periods, slipping categories, and outliers.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Informed decisions</h3>
        <p class="text-gray-600">Align menu, staffing, and spend with evidence instead of gut feel alone.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 md:col-span-2 lg:col-span-3">
        <p class="text-center text-gray-600 mb-4">Want channel-level reporting and operational analytics too?</p>
        <div class="flex flex-wrap justify-center gap-4">
          <a href="{{ route('features.analytics-reporting') }}" class="text-orange-600 font-semibold hover:text-red-600 transition-colors">Advanced Analytics &amp; Reporting →</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-16 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-2xl lg:text-3xl font-bold text-white mb-4">Run the business—not just the shift</h2>
    <p class="text-orange-100 mb-8 max-w-xl mx-auto">Start free and put BI in the hands of owners and GMs.</p>
    <a href="{{ route('register') }}" class="inline-flex bg-white text-orange-600 px-8 py-3 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
      Start for free
    </a>
  </div>
</section>
@endsection
