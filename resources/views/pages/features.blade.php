{{-- resources/views/pages/features.blade.php --}}
@extends('layouts.page')

@section('title', 'Features - Dukaantech POS')
@section('meta')
<meta name="description" content="Explore all features of Dukaantech POS including smart billing, inventory management, staff scheduling, analytics, and more.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Powerful Features for
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Modern Restaurants
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Everything you need to run a successful restaurant business, all in one platform
      </p>
    </div>
  </div>
</section>

{{-- Core Features --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Core Features</h2>
      <p class="text-xl text-gray-600">Essential tools for restaurant management</p>
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
          <li>• Quick order entry with touch interface</li>
          <li>• Multiple payment modes (Cash, Card, UPI, Wallet)</li>
          <li>• GST compliant invoices</li>
          <li>• Split billing and table management</li>
          <li>• Kitchen order tickets (KOT)</li>
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
          <li>• Real-time stock level monitoring</li>
          <li>• Automated low stock alerts</li>
          <li>• Purchase order management</li>
          <li>• Recipe costing and menu pricing</li>
          <li>• Supplier management</li>
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
          <li>• Shift scheduling and planning</li>
          <li>• Attendance tracking</li>
          <li>• Performance analytics</li>
          <li>• Role-based access control</li>
          <li>• Employee commission tracking</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Analytics & Reports</h3>
        <p class="text-gray-600 mb-4">Comprehensive reporting and analytics to make data-driven decisions.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Sales analytics and trends</li>
          <li>• Profit and loss reports</li>
          <li>• Customer analytics</li>
          <li>• Inventory reports</li>
          <li>• Staff performance reports</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Mobile App</h3>
        <p class="text-gray-600 mb-4">Manage your restaurant on the go with our mobile application.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Mobile POS for servers</li>
          <li>• Manager dashboard</li>
          <li>• Real-time notifications</li>
          <li>• Offline mode support</li>
          <li>• Push notifications</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Integrations</h3>
        <p class="text-gray-600 mb-4">Connect with popular third-party services and tools.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Payment gateways (Razorpay, PayU)</li>
          <li>• Accounting software (Tally)</li>
          <li>• Food delivery platforms</li>
          <li>• Loyalty programs</li>
          <li>• SMS and email services</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- Advanced Features --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Advanced Features</h2>
      <p class="text-xl text-gray-600">Powerful tools for growing restaurants</p>
    </div>
    
    <div class="grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h3 class="text-3xl font-bold text-gray-900 mb-6">Multi-location Management</h3>
        <p class="text-lg text-gray-600 mb-8">Manage multiple restaurant locations from a single dashboard with centralized reporting and control.</p>
        
        <div class="space-y-6">
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Centralized Control</h4>
              <p class="text-gray-600">Manage all locations from one dashboard</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Consolidated Reporting</h4>
              <p class="text-gray-600">Get insights across all your locations</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Role-based Access</h4>
              <p class="text-gray-600">Control access for different management levels</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="relative">
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
          <div class="text-center mb-6">
            <h4 class="text-xl font-bold text-gray-900 mb-2">Multi-location Dashboard</h4>
            <p class="text-gray-600">Overview of all restaurant locations</p>
          </div>
          
          <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="font-medium text-gray-900">Mumbai Central</span>
              </div>
              <span class="text-sm text-gray-600">₹1.2L today</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="font-medium text-gray-900">Delhi NCR</span>
              </div>
              <span class="text-sm text-gray-600">₹95K today</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <span class="font-medium text-gray-900">Bangalore</span>
              </div>
              <span class="text-sm text-gray-600">₹78K today</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="font-medium text-gray-900">Chennai</span>
              </div>
              <span class="text-sm text-gray-600">₹65K today</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Security & Compliance --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Security & Compliance</h2>
      <p class="text-xl text-gray-600">Your data is safe and secure with us</p>
    </div>
    
    <div class="grid md:grid-cols-3 gap-8">
      <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Data Security</h3>
        <p class="text-gray-600">Bank-grade encryption and secure cloud infrastructure to protect your data.</p>
      </div>
      
      <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">GST Compliance</h3>
        <p class="text-gray-600">Automated GST calculations and compliant invoice generation for all transactions.</p>
      </div>
      
      <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Kitchen Order Tickets (KOT)</h3>
        <p class="text-gray-600">Streamline kitchen operations with automated order tickets and real-time order tracking.</p>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Experience All Features</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Start your free trial today and explore all the powerful features that Dukaantech POS has to offer.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Start Free Trial
      </a>
      <a href="/pricing" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        View Pricing
      </a>
    </div>
  </div>
</section>
@endsection
