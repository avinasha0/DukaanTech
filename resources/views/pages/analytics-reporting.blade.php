{{-- resources/views/pages/analytics-reporting.blade.php --}}
@extends('layouts.page')

@section('title', 'Advanced Analytics & Reporting - Dukaantech POS')
@section('meta')
<meta name="description" content="Data-driven reports: sales performance, customer behavior, staff productivity, and growth opportunities—in one place.">
@endsection

@section('page_content')
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-4">
    <div class="max-w-3xl">
      <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        Insights &amp; performance
      </div>
      <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
        Advanced Analytics &amp;
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">Reporting</span>
      </h1>
      <p class="text-xl text-gray-600 leading-relaxed mb-8">
        Make data-driven decisions with comprehensive reports. Track sales performance, analyze customer behavior, monitor staff productivity, and identify growth opportunities instantly.
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
      <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">See the full picture, not just the register</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">Turn daily operations into clear trends you can act on.</p>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Sales performance</h3>
        <p class="text-gray-600">Revenue, categories, and channels so you know what is driving the day.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Customer behavior</h3>
        <p class="text-gray-600">Understand repeat visits, preferences, and what keeps guests coming back.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Staff productivity</h3>
        <p class="text-gray-600">Spot bottlenecks and coach teams with activity tied to real shifts.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 md:col-span-2 lg:col-span-3">
        <div class="max-w-2xl mx-auto text-center">
          <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5 mx-auto">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Growth opportunities</h3>
          <p class="text-gray-600">Surface trends early—menu tweaks, peak hours, and channels worth doubling down on.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-16 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-2xl lg:text-3xl font-bold text-white mb-4">Decisions you can defend with numbers</h2>
    <p class="text-orange-100 mb-8 max-w-xl mx-auto">Start free and give your team a single source of truth.</p>
    <a href="{{ route('register') }}" class="inline-flex bg-white text-orange-600 px-8 py-3 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
      Start for free
    </a>
  </div>
</section>
@endsection
