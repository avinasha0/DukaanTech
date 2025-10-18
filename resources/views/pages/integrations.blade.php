{{-- resources/views/pages/integrations.blade.php --}}
@extends('layouts.page')

@section('title', 'Integrations - Dukaantech POS')
@section('meta')
<meta name="description" content="Connect Dukaantech POS with popular payment gateways, accounting software, food delivery platforms, and other essential tools.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Powerful
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Integrations
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Connect Dukaantech POS with the tools you already use to streamline your operations
      </p>
    </div>
  </div>
</section>

{{-- Integration Categories --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Integration Categories</h2>
      <p class="text-xl text-gray-600">Connect with the tools that matter to your business</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Payment Gateways</h3>
        <p class="text-gray-600 mb-4">Accept payments through multiple channels</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Razorpay</li>
          <li>• PayU</li>
          <li>• Paytm</li>
          <li>• PhonePe</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Accounting Software</h3>
        <p class="text-gray-600 mb-4">Sync with your accounting system</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Tally ERP</li>
          <li>• QuickBooks</li>
          <li>• Zoho Books</li>
          <li>• Busy</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Food Delivery</h3>
        <p class="text-gray-600 mb-4">Manage online orders seamlessly</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Swiggy</li>
          <li>• Zomato</li>
          <li>• Uber Eats</li>
          <li>• Dunzo</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Communication</h3>
        <p class="text-gray-600 mb-4">Keep customers informed</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• SMS Gateway</li>
          <li>• Email Services</li>
          <li>• WhatsApp Business</li>
          <li>• Push Notifications</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- Popular Integrations --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Popular Integrations</h2>
      <p class="text-xl text-gray-600">Most requested integrations by our customers</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center gap-4 mb-6">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-xl font-bold text-gray-900">Razorpay</h3>
            <p class="text-gray-600">Payment Gateway</p>
          </div>
        </div>
        <p class="text-gray-600 mb-4">Accept payments via UPI, cards, wallets, and net banking with Razorpay's secure payment gateway.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• UPI payments</li>
          <li>• Credit/Debit cards</li>
          <li>• Digital wallets</li>
          <li>• Net banking</li>
        </ul>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center gap-4 mb-6">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-xl font-bold text-gray-900">Tally ERP</h3>
            <p class="text-gray-600">Accounting Software</p>
          </div>
        </div>
        <p class="text-gray-600 mb-4">Automatically sync sales data, inventory, and financial reports with Tally ERP for seamless accounting.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Sales data sync</li>
          <li>• Inventory updates</li>
          <li>• Financial reports</li>
          <li>• GST compliance</li>
        </ul>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center gap-4 mb-6">
          <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-xl font-bold text-gray-900">Swiggy</h3>
            <p class="text-gray-600">Food Delivery</p>
          </div>
        </div>
        <p class="text-gray-600 mb-4">Manage Swiggy orders directly from your POS system with automatic order synchronization.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Order synchronization</li>
          <li>• Inventory updates</li>
          <li>• Order status tracking</li>
          <li>• Payment reconciliation</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- API Documentation --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Build Custom Integrations</h2>
      <p class="text-xl text-gray-600">Use our powerful API to create custom integrations</p>
    </div>
    
    <div class="grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h3 class="text-3xl font-bold text-gray-900 mb-6">RESTful API</h3>
        <p class="text-lg text-gray-600 mb-8">Our comprehensive REST API allows you to integrate Dukaantech POS with any third-party application or build custom solutions.</p>
        
        <div class="space-y-6">
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Real-time Data Sync</h4>
              <p class="text-gray-600">Get real-time updates on orders, inventory, and sales data</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Webhook Support</h4>
              <p class="text-gray-600">Receive instant notifications when events occur in your system</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Comprehensive Documentation</h4>
              <p class="text-gray-600">Detailed API documentation with code examples and SDKs</p>
            </div>
          </div>
        </div>
        
        <div class="mt-8">
          <a href="/api-documentation" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
            View API Documentation
          </a>
        </div>
      </div>
      
      <div class="relative">
        <div class="bg-gray-900 rounded-2xl p-8 text-green-400 font-mono text-sm">
          <div class="mb-4">
            <span class="text-gray-500">// Example API call</span>
          </div>
          <div class="mb-2">
            <span class="text-blue-400">POST</span> <span class="text-white">/api/v1/orders</span>
          </div>
          <div class="mb-4">
            <span class="text-gray-500">{</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-yellow-400">"customer_id"</span><span class="text-white">: </span><span class="text-green-400">"12345"</span><span class="text-white">,</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-yellow-400">"items"</span><span class="text-white">: [</span>
          </div>
          <div class="ml-8 mb-2">
            <span class="text-gray-500">{</span>
          </div>
          <div class="ml-12 mb-2">
            <span class="text-yellow-400">"product_id"</span><span class="text-white">: </span><span class="text-green-400">"101"</span><span class="text-white">,</span>
          </div>
          <div class="ml-12 mb-2">
            <span class="text-yellow-400">"quantity"</span><span class="text-white">: </span><span class="text-blue-400">2</span>
          </div>
          <div class="ml-8 mb-2">
            <span class="text-gray-500">}</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-white">]</span>
          </div>
          <div class="mb-4">
            <span class="text-gray-500">}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Integrate?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Connect Dukaantech POS with your favorite tools and streamline your restaurant operations.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Start Free Trial
      </a>
      <a href="/contact-us" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Request Integration
      </a>
    </div>
  </div>
</section>
@endsection
