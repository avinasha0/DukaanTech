{{-- resources/views/pages/product.blade.php --}}
@extends('layouts.page')

@section('title', 'Product Overview - Dukaantech POS')
@section('meta')
<meta name="description" content="Discover Dukaantech POS - the complete restaurant management solution with POS billing, inventory management, staff scheduling, and analytics.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Complete Restaurant
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Management Solution
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        From POS billing to inventory management, staff scheduling to analytics - 
        manage your entire restaurant operations with one powerful platform.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/register" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
          Start Free Trial
        </a>
        <a href="/features" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg text-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
          Explore Features
        </a>
      </div>
    </div>
  </div>
</section>

{{-- Core Features Grid --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Everything Your Restaurant Needs</h2>
      <p class="text-xl text-gray-600 max-w-3xl mx-auto">
        Comprehensive tools designed specifically for Indian restaurants
      </p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Smart POS Billing</h3>
        <p class="text-gray-600 mb-4">Lightning-fast billing with customizable menus, modifiers, and split billing options.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Quick order entry</li>
          <li>• Multiple payment modes</li>
          <li>• GST compliant invoices</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Inventory Management</h3>
        <p class="text-gray-600 mb-4">Real-time inventory tracking with automated alerts and supplier management.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Stock level monitoring</li>
          <li>• Purchase order management</li>
          <li>• Recipe costing</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Staff Management</h3>
        <p class="text-gray-600 mb-4">Complete staff scheduling, attendance tracking, and performance management.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Shift scheduling</li>
          <li>• Attendance tracking</li>
          <li>• Performance analytics</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- Why Choose Dukaantech --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Dukaantech POS?</h2>
      <p class="text-xl text-gray-600">Built specifically for Indian restaurants</p>
    </div>
    
    <div class="grid md:grid-cols-2 gap-12 items-center">
      <div>
        <div class="space-y-8">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-gray-900 mb-2">GST Compliant</h3>
              <p class="text-gray-600">Automated GST calculations and compliant invoice generation for all transactions.</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-gray-900 mb-2">Multi-language Support</h3>
              <p class="text-gray-600">Support for Hindi, English, and regional languages for better customer experience.</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-gray-900 mb-2">Cloud-based</h3>
              <p class="text-gray-600">Access your data from anywhere with secure cloud storage and real-time synchronization.</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="relative">
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
          <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl mx-auto mb-6 flex items-center justify-center">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Restaurant Dashboard</h3>
            <p class="text-gray-600 mb-4">Complete control at your fingertips</p>
            <div class="grid grid-cols-3 gap-4 text-center">
              <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-orange-600">₹45K</div>
                <div class="text-xs text-gray-600">Today's Sales</div>
              </div>
              <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-green-600">127</div>
                <div class="text-xs text-gray-600">Orders</div>
              </div>
              <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-blue-600">89%</div>
                <div class="text-xs text-gray-600">Efficiency</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Transform Your Restaurant?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Join thousands of restaurant owners who have already made the switch to Dukaantech POS. 
      Start your free trial today and see the difference.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Start Free Trial
      </a>
      <a href="/contact-us" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Schedule Demo
      </a>
    </div>
  </div>
</section>
@endsection
