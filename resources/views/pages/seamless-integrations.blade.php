{{-- resources/views/pages/seamless-integrations.blade.php --}}
@extends('layouts.page')

@section('title', 'Seamless Integrations - Dukaantech POS')
@section('meta')
<meta name="description" content="Connect delivery, payments, accounting, and marketing tools. One dashboard for all your restaurant integrations.">
@endsection

@section('page_content')
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-4">
    <div class="max-w-3xl">
      <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
        </svg>
        Ecosystem
      </div>
      <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
        Seamless
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">Integrations</span>
      </h1>
      <p class="text-xl text-gray-600 leading-relaxed mb-8">
        Connect with popular delivery platforms, payment gateways, accounting software, and marketing tools. One dashboard to manage all your restaurant integrations.
      </p>
      <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('register') }}" class="inline-flex justify-center bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
          Start for free
        </a>
        <a href="{{ route('integrations') }}" class="inline-flex justify-center border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-lg text-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-colors">
          Full integrations list
        </a>
      </div>
    </div>
  </div>
</section>

<section class="py-16 lg:py-20 bg-white border-t border-gray-100">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-14">
      <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Everything plugs into one operations hub</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">Fewer logins, fewer mistakes, faster reconciliation.</p>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Delivery platforms</h3>
        <p class="text-gray-600">Aggregate aggregator orders with your in-house flow where supported.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Payment gateways</h3>
        <p class="text-gray-600">Card, UPI, and wallets—aligned with how your guests already pay.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Accounting</h3>
        <p class="text-gray-600">Push sales and fees to your books with less manual entry.</p>
      </div>
      <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 md:col-span-2 lg:col-span-3">
        <div class="max-w-2xl mx-auto text-center">
          <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-5 mx-auto">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147 6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1c.256 0 .512.098.707.293l4.414 4.414a2 2 0 01.293.707V17a2 2 0 01-2 2h-1.436a4 4 0 01-2.128-.687l-1.128-.687a4 4 0 00-2.128-.687H5a2 2 0 01-2-2V9a2 2 0 012-2h1.436a4 4 0 002.128.687l1.128.687a4 4 0 002.128.687z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Marketing tools</h3>
          <p class="text-gray-600 mb-6">Hook campaigns and CRM touches to real purchase data—not siloed lists.</p>
          <a href="{{ route('integrations') }}" class="inline-flex text-orange-600 font-semibold hover:text-red-600 transition-colors">See all integrations →</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-16 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-2xl lg:text-3xl font-bold text-white mb-4">Connect once, run everything from one place</h2>
    <p class="text-orange-100 mb-8 max-w-xl mx-auto">Start free and simplify your stack.</p>
    <a href="{{ route('register') }}" class="inline-flex bg-white text-orange-600 px-8 py-3 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
      Start for free
    </a>
  </div>
</section>
@endsection
