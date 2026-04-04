@extends('layouts.app')

@section('title', 'Pricing - Dukaantech POS | Free Restaurant POS (for now)')

@section('meta')
<meta name="description" content="Dukaantech POS: Starter and Enterprise plans are free right now—no platform subscription fees. Pick the feature set that fits your restaurant; we’ll give notice before any paid pricing.">
@endsection

@section('content')
<div class="min-h-[100dvh] bg-white text-gray-900">
  {{-- Header Component --}}
  <x-header />

  {{-- Hero Section --}}
  <section class="relative bg-gradient-to-br from-orange-50 via-white to-red-50 pt-20 pb-16">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
          Simple, Transparent
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
            Pricing
          </span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          <span class="font-semibold text-gray-900">Right now the platform is free</span>—no subscription fees for Starter or Enterprise.
          Pick the plan that matches how you operate; we’ll announce any pricing changes in advance.
        </p>
      </div>
    </div>
  </section>

  {{-- Free-for-now notice --}}
  <section class="py-8 bg-gray-50">
    <div class="mx-auto max-w-3xl px-4">
      <div class="rounded-xl border border-green-200 bg-green-50 px-6 py-4 text-center text-green-900">
        <p class="font-semibold">No platform fees today</p>
        <p class="text-sm text-green-800 mt-1">Starter and Enterprise are ₹0 while we’re in this phase—we’ll give clear notice before any paid subscription.</p>
      </div>
    </div>
  </section>

  {{-- Pricing Cards --}}
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4">
      <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        {{-- Starter Plan --}}
        <div class="bg-white rounded-2xl p-8 border-2 border-gray-200 hover:border-orange-300 transition-all duration-300 hover:shadow-xl">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
            <p class="text-gray-600 mb-4">Perfect for small cafes & food trucks</p>
            <div class="text-5xl font-bold text-gray-900 mb-2">₹0</div>
            <div class="text-gray-600">per month</div>
            <div class="text-sm text-green-600 font-semibold mt-2">Always Free</div>
          </div>
          
          <ul class="space-y-4 mb-8">
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Basic POS billing</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Up to 2 terminals</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Basic menu management</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Email support</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Basic reports</span>
            </li>
          </ul>
          
          <a href="/register" class="w-full bg-gray-900 text-white py-4 rounded-lg font-semibold text-center block hover:bg-gray-800 transition-colors">
            Start Free
          </a>
        </div>

{{-- Enterprise Plan --}}
        <div class="bg-white rounded-2xl p-8 border-2 border-gray-200 hover:border-purple-300 transition-all duration-300 hover:shadow-xl">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
            <p class="text-gray-600 mb-4">For restaurant chains & franchises</p>
            <div class="flex flex-wrap items-baseline justify-center gap-2 mb-2">
              <span class="text-3xl text-gray-400 font-semibold" style="text-decoration: line-through; text-decoration-thickness: 2px;">₹299</span>
              <span class="text-5xl font-bold text-gray-900">₹0</span>
            </div>
            <div class="text-gray-600">per month</div>
            <div class="text-sm text-green-600 font-semibold mt-2">Free for now — full platform for chains</div>
          </div>
          
          <ul class="space-y-4 mb-8">
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Unlimited terminals</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Advanced Analytics</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Advanced Reporting</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">KOT (Kitchen Order Tickets)</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Inventory Management</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">QR Menu</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Dedicated Account Manager</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Email Support</span>
            </li>
          </ul>
          
          <a href="/contact-us" class="w-full bg-gray-900 text-white py-4 rounded-lg font-semibold text-center block hover:bg-gray-800 transition-colors">
            Start free for your chain
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- Features Comparison --}}
  <section class="py-20 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Compare All Features</h2>
        <p class="text-xl text-gray-600">See what's included in each plan</p>
      </div>
      
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Features</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Starter</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-purple-600">Enterprise</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4 text-sm text-gray-900">POS Billing</td>
                <td class="px-6 py-4 text-center"><svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></td>
                <td class="px-6 py-4 text-center"><svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></td>
              </tr>
              <tr class="bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">Terminals</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Up to 2</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Unlimited</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-sm text-gray-900">Inventory Management</td>
                <td class="px-6 py-4 text-center"><span class="text-gray-400">-</span></td>
                <td class="px-6 py-4 text-center"><svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></td>
              </tr>
              <tr class="bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">Analytics & Reports</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Basic</td>
                <td class="px-6 py-4 text-center"><svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></td>
              </tr>
              <tr class="bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">Support</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Email</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Email</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ Section --}}
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-4xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
        <p class="text-xl text-gray-600">Everything you need to know about our pricing</p>
      </div>
      
      <div class="space-y-8">
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Is the software really free right now?</h3>
          <p class="text-gray-600">Yes. There are no Dukaantech platform subscription fees at this time for Starter or Enterprise—you can use the product without paying us for the POS software today.</p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Will pricing stay free forever?</h3>
          <p class="text-gray-600">We don’t guarantee free pricing forever. If we introduce paid plans later, we’ll give advance notice so you can decide what works for your business.</p>
        </div>
        
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Can I switch between Starter and Enterprise?</h3>
          <p class="text-gray-600">Yes. Move to the feature set that fits you as you grow. While the platform is free, there’s no billing to worry about—just pick the right capabilities for your outlets and team.</p>
        </div>
        
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Is there a setup fee?</h3>
          <p class="text-gray-600">No setup fee for the POS platform in this phase. We also provide onboarding help for new customers.</p>
        </div>
        
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">What about payments to you in the future?</h3>
          <p class="text-gray-600">Today you’re not paying platform fees. If we introduce optional add-ons or paid tiers later, we’ll explain them clearly—including how you can pay (e.g. UPI, cards, or invoice for larger teams).</p>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
    <div class="mx-auto max-w-7xl px-4 text-center">
      <h2 class="text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
      <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
        Join restaurants using Dukaantech POS while the platform is free—no subscription fees in this phase, no card required to get started.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
          Start free
        </a>
        <a href="/contact-us" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
          Talk to our team
        </a>
      </div>
    </div>
  </section>

  {{-- Footer Component --}}
  <x-footer />
</div>
@endsection
