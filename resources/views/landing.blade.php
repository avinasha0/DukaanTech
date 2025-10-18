{{-- resources/views/landing.blade.php --}}
@extends('layouts.app')

@section('title', 'Dukaantech POS - Restaurant POS Software Made Simple!')

@section('meta')
<meta name="description" content="Restaurant POS software made simple! Manages all your restaurant operations efficiently so that you can focus on growing your brand, like a real boss! Trusted by 90,000+ restaurants.">
@endsection

@section('content')
<div class="min-h-[100dvh] bg-white text-gray-900">
  {{-- Header Component --}}
  <x-header />

  {{-- Hero Section --}}
  <section class="relative bg-gradient-to-br from-orange-50 via-white to-red-50 pt-8 pb-20 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23F66C17" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
    
    <div class="mx-auto max-w-7xl px-4 relative">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div>
          <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Trusted by 90,000+ Restaurants
          </div>
          
          <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
            Smart Restaurant Management
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
              Made Effortless!
            </span>
          </h1>
          
          <p class="text-xl text-gray-600 mb-8 leading-relaxed">
            Streamline your restaurant operations with our comprehensive POS solution. From order taking to inventory management, we handle everything so you can focus on what matters most - growing your business!
          </p>
          
          <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <a href="/register" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
              Take a free demo
            </a>
            <a href="#features" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg text-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
              Explore Features
            </a>
          </div>
          
          <div class="flex items-center gap-8 text-sm text-gray-600">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              QR Code Ordering
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              Advanced Reporting
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              Real-Time Analytics
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
              <h3 class="text-2xl font-bold text-gray-900 mb-2">Restaurant POS</h3>
              <p class="text-gray-600 mb-4">Complete control at your fingertips</p>
              <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-gray-50 rounded-lg p-3">
                  <div class="text-2xl font-bold text-orange-600">₹45K</div>
                  <div class="text-xs text-gray-600">Today's Sales</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                  <div class="text-2xl font-bold text-red-600">127</div>
                  <div class="text-xs text-gray-600">Orders</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                  <div class="text-2xl font-bold text-green-600">89%</div>
                  <div class="text-xs text-gray-600">Efficiency</div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Floating elements -->
          <div class="absolute -top-4 -right-4 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg" style="background: linear-gradient(to right, #F66C17, #DE2B25);">
            Live Orders: 12
          </div>
          <div class="absolute -bottom-4 -left-4 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg" style="background: linear-gradient(to right, #F66C17, #DE2B25);">
            Staff Online: 8
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Trusted By Section --}}
  <section class="py-12 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
      <p class="text-center text-gray-600 mb-8">Trusted by leading restaurant chains across India</p>
      <div class="flex items-center justify-center gap-12 opacity-60 flex-wrap">
        <div class="text-2xl font-bold text-gray-400">RebootChai</div>
        <div class="text-2xl font-bold text-gray-400">Tea Nine</div>
      </div>
    </div>
  </section>

  {{-- SMART POS FEATURES Section --}}
  <section id="features" class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">COMPREHENSIVE RESTAURANT FEATURES</h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Everything you need to run a successful restaurant. Our intuitive platform makes managing high-volume operations seamless and efficient.
        </p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Quick 3-click billing -->
        <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Lightning-Fast Order Processing</h3>
          <p class="text-gray-600 mb-4">Process orders in seconds with our intuitive interface. Split bills, merge tables, apply discounts, and generate kitchen tickets effortlessly. Perfect for busy restaurants.</p>
          <a href="#" class="text-orange-600 font-semibold hover:text-red-600 transition-colors">Explore all features →</a>
        </div>
        
        <!-- Inventory Management -->
        <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
          <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform" style="background: linear-gradient(to bottom right, #F66C17, #DE2B25);">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Inventory Control</h3>
          <p class="text-gray-600 mb-4">Automate your inventory tracking with real-time updates. Get instant low-stock alerts, track consumption patterns, and generate detailed reports to optimize your food costs.</p>
          <a href="#" class="text-orange-600 font-semibold hover:text-red-600 transition-colors">Explore all features →</a>
        </div>
        
        <!-- Real-Time Reporting -->
        <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Advanced Analytics & Reporting</h3>
          <p class="text-gray-600 mb-4">Make data-driven decisions with comprehensive reports. Track sales performance, analyze customer behavior, monitor staff productivity, and identify growth opportunities instantly.</p>
          <a href="#" class="text-orange-600 font-semibold hover:text-red-600 transition-colors">Explore all features →</a>
        </div>
        
        <!-- Online Order System -->
        <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
          <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform" style="background: linear-gradient(to bottom right, #F66C17, #DE2B25);">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Unified Order Management</h3>
          <p class="text-gray-600 mb-4">Seamlessly handle dine-in, takeaway, and delivery orders from one central dashboard. Manage your digital menu, track order status, and process payments efficiently.</p>
          <a href="#" class="text-orange-600 font-semibold hover:text-red-600 transition-colors">Explore all features →</a>
        </div>
      </div>
    </div>
  </section>

  {{-- APP MARKETPLACE Section --}}
  <section class="py-20 bg-gradient-to-br from-orange-50 to-red-50">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">POWERFUL ADD-ONS</h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Enhance your restaurant operations with our integrated solutions and third-party integrations
        </p>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <!-- CRM -->
        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-4">Customer Relationship Management</h3>
          <p class="text-gray-600 mb-6">Build lasting relationships with your customers. Track preferences, manage loyalty programs, send targeted promotions, and provide personalized service that keeps customers coming back.</p>
          <a href="#" class="text-orange-600 font-semibold hover:text-red-600 transition-colors">Learn more →</a>
        </div>
        
        <!-- Analytics -->
        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
          <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6" style="background: linear-gradient(to bottom right, #F66C17, #DE2B25);">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-4">Business Intelligence</h3>
          <p class="text-gray-600 mb-6">Gain deep insights into your restaurant's performance with advanced analytics. Monitor key metrics, identify trends, and make informed decisions to boost profitability and efficiency.</p>
          <a href="#" class="text-orange-600 font-semibold hover:text-red-600 transition-colors">Learn more →</a>
        </div>
        
        <!-- Integrations -->
        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-4">Seamless Integrations</h3>
          <p class="text-gray-600 mb-6">Connect with popular delivery platforms, payment gateways, accounting software, and marketing tools. One dashboard to manage all your restaurant integrations.</p>
          <a href="#" class="text-orange-600 font-semibold hover:text-red-600 transition-colors">See all integrations →</a>
        </div>
      </div>
    </div>
  </section>

  {{-- Outlet Types Section --}}
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Perfect for Every Food Business</h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Whether you run a small café or a large restaurant chain, our flexible platform adapts to your unique needs and business model.
        </p>
      </div>
      
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-6">
        <div class="text-center group cursor-pointer">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-700">Food Courts</p>
        </div>
        
        <div class="text-center group cursor-pointer">
          <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-700">Cafes</p>
        </div>
        
        <div class="text-center group cursor-pointer">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-700">Fine Dining</p>
        </div>
        
        <div class="text-center group cursor-pointer">
          <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-700">Bars & Breweries</p>
        </div>
        
        <div class="text-center group cursor-pointer">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-700">Pizzeria</p>
        </div>
        
        <div class="text-center group cursor-pointer">
          <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-700">QSR</p>
        </div>
        
        <div class="text-center group cursor-pointer">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-700">Desserts</p>
        </div>
        
        <div class="text-center group cursor-pointer">
          <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-700">Large chains</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Pricing Section --}}
  <section id="pricing" class="py-20 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
        <p class="text-xl text-gray-600">Choose the plan that fits your restaurant size</p>
      </div>
      
      <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl p-8 border border-gray-200">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
            <p class="text-gray-600 mb-4">Perfect for small cafes</p>
            <div class="text-4xl font-bold text-gray-900">₹0</div>
            <div class="text-gray-600">per month</div>
          </div>
          <ul class="space-y-4 mb-8">
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Basic POS billing</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Up to 2 terminals</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Email support</span>
            </li>
          </ul>
          <a href="/signup" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold text-center block hover:bg-gray-800 transition-colors">
            Start Free
          </a>
        </div>
        
        <div class="bg-white rounded-2xl p-8 border border-gray-200">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
            <p class="text-gray-600 mb-4">For restaurant chains</p>
            <div class="text-4xl font-bold text-gray-900">₹299</div>
            <div class="text-gray-600">per month</div>
          </div>
          <ul class="space-y-4 mb-8">
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Complete POS system</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Unlimited terminals</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Advanced Analytics</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Advanced Reporting</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">KOT (Kitchen Order Tickets)</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Inventory Management</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">QR Menu</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Dedicated Account Manager</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Email Support</span>
            </li>
          </ul>
          <a href="/contact" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold text-center block hover:bg-gray-800 transition-colors">
            Contact Sales
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- Statistics Section --}}
  <section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
    <div class="mx-auto max-w-7xl px-4">
      <div class="grid md:grid-cols-4 gap-8 text-center text-white">
        <div>
          <div class="text-5xl font-bold mb-2">500+</div>
          <div class="text-xl opacity-90">Restaurants Served</div>
        </div>
        <div>
          <div class="text-5xl font-bold mb-2">50K+</div>
          <div class="text-xl opacity-90">Orders Processed Daily</div>
        </div>
        <div>
          <div class="text-5xl font-bold mb-2">99.9%</div>
          <div class="text-xl opacity-90">Uptime Guarantee</div>
        </div>
        <div>
          <div class="text-5xl font-bold mb-2">₹2M+</div>
          <div class="text-xl opacity-90">Revenue Processed</div>
        </div>
      </div>
    </div>
  </section>

  {{-- Industry Ratings Section --}}
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Trusted by Industry Leaders</h2>
        <p class="text-xl text-gray-600">Recognized for excellence in restaurant technology solutions</p>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div class="text-center p-8 bg-gray-50 rounded-2xl">
          <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6" style="background: linear-gradient(to bottom right, #F66C17, #DE2B25);">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
          <p class="text-gray-600">Featured as "Best Restaurant POS Solution" for 2024</p>
        </div>
        
        <div class="text-center p-8 bg-gray-50 rounded-2xl">
          <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6" style="background: linear-gradient(to bottom right, #F66C17, #DE2B25);">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
          <p class="text-gray-600">Recognized as "Most Innovative POS Platform" for restaurants</p>
        </div>
        
        <div class="text-center p-8 bg-gray-50 rounded-2xl">
          <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6" style="background: linear-gradient(to bottom right, #F66C17, #DE2B25);">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
          <p class="text-gray-600">Restaurateurs rated us the most recommended POS provider</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Testimonials Section --}}
  <section id="testimonials" class="py-20 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
        <p class="text-xl text-gray-600">Join thousands of satisfied restaurant owners</p>
      </div>
      
      <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="flex items-center gap-1 mb-4">
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
          </div>
          <p class="text-gray-700 mb-6 italic">"Dukaantech has completely transformed how we manage our restaurant. The inventory tracking is incredible - we've reduced food waste by 30% and our staff loves how easy it is to use. The reporting features help us make better business decisions every day."</p>
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center text-white font-bold">
              M
            </div>
            <div>
              <div class="font-semibold text-gray-900">Maria Rodriguez</div>
              <div class="text-sm text-gray-600">Restaurant Owner, Bella Vista</div>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="flex items-center gap-1 mb-4">
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
          </div>
          <p class="text-gray-700 mb-6 italic">"We switched to Dukaantech six months ago and haven't looked back. The multi-location management feature is perfect for our chain of restaurants. The customer support team is fantastic, and the system is so intuitive that our staff was up and running in just one day."</p>
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold" style="background: linear-gradient(to bottom right, #F66C17, #DE2B25);">
              A
            </div>
            <div>
              <div class="font-semibold text-gray-900">Ahmed Hassan</div>
              <div class="text-sm text-gray-600">Operations Manager, Food Chain</div>
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
        Join hundreds of successful restaurants already using Dukaantech. Start your free trial today and see the difference.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
          Start Free Trial
        </a>
        <a href="/contact" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
          Schedule Demo
        </a>
      </div>
    </div>
  </section>

  {{-- Footer Component --}}
  <x-footer />
</div>
@endsection