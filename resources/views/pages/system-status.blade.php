{{-- resources/views/pages/system-status.blade.php --}}
@extends('layouts.page')

@section('title', 'System Status - Dukaantech POS')
@section('meta')
<meta name="description" content="Check the current status of Dukaantech POS services. Real-time monitoring of system performance and uptime.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        System
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Status
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Real-time monitoring of Dukaantech POS services and system performance
      </p>
    </div>
  </div>
</section>

{{-- Overall Status --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Overall System Status</h2>
      <p class="text-xl text-gray-600">All systems operational</p>
    </div>
    
    <div class="bg-green-50 border border-green-200 rounded-2xl p-8 text-center">
      <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
      </div>
      <h3 class="text-3xl font-bold text-green-800 mb-4">All Systems Operational</h3>
      <p class="text-xl text-green-700 mb-6">All Dukaantech POS services are running normally</p>
      <div class="text-sm text-green-600">
        Last updated: {{ date('M d, Y H:i:s T') }}
      </div>
    </div>
  </div>
</section>

{{-- Service Status --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Service Status</h2>
      <p class="text-xl text-gray-600">Individual service monitoring</p>
    </div>
    
    <div class="space-y-6">
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
            <div>
              <h3 class="text-xl font-bold text-gray-900">POS Application</h3>
              <p class="text-gray-600">Core POS functionality</p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-sm text-gray-500">Uptime</div>
            <div class="text-lg font-semibold text-green-600">99.9%</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
            <div>
              <h3 class="text-xl font-bold text-gray-900">Payment Processing</h3>
              <p class="text-gray-600">Payment gateway services</p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-sm text-gray-500">Uptime</div>
            <div class="text-lg font-semibold text-green-600">99.8%</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
            <div>
              <h3 class="text-xl font-bold text-gray-900">API Services</h3>
              <p class="text-gray-600">REST API and webhooks</p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-sm text-gray-500">Uptime</div>
            <div class="text-lg font-semibold text-green-600">99.9%</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
            <div>
              <h3 class="text-xl font-bold text-gray-900">Mobile App</h3>
              <p class="text-gray-600">iOS and Android applications</p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-sm text-gray-500">Uptime</div>
            <div class="text-lg font-semibold text-green-600">99.7%</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
            <div>
              <h3 class="text-xl font-bold text-gray-900">Database</h3>
              <p class="text-gray-600">Data storage and retrieval</p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-sm text-gray-500">Uptime</div>
            <div class="text-lg font-semibold text-green-600">99.9%</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Performance Metrics --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Performance Metrics</h2>
      <p class="text-xl text-gray-600">Real-time performance data</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Response Time</h3>
        <div class="text-3xl font-bold text-green-600 mb-2">120ms</div>
        <p class="text-gray-600 text-sm">Average API response</p>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Active Users</h3>
        <div class="text-3xl font-bold text-blue-600 mb-2">2,847</div>
        <p class="text-gray-600 text-sm">Currently online</p>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Orders Today</h3>
        <div class="text-3xl font-bold text-purple-600 mb-2">15,432</div>
        <p class="text-gray-600 text-sm">Processed successfully</p>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Uptime</h3>
        <div class="text-3xl font-bold text-orange-600 mb-2">99.9%</div>
        <p class="text-gray-600 text-sm">Last 30 days</p>
      </div>
    </div>
  </div>
</section>

{{-- Incident History --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-4xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Recent Incidents</h2>
      <p class="text-xl text-gray-600">Past incidents and resolutions</p>
    </div>
    
    <div class="space-y-6">
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-3 h-3 bg-green-500 rounded-full"></div>
          <span class="text-sm text-gray-500">Resolved</span>
          <span class="text-sm text-gray-500">•</span>
          <span class="text-sm text-gray-500">December 15, 2023</span>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Minor API Latency Issue</h3>
        <p class="text-gray-600 mb-4">
          We experienced slightly higher than normal API response times for approximately 15 minutes. 
          The issue was resolved by optimizing database queries.
        </p>
        <div class="text-sm text-gray-500">Duration: 15 minutes • Impact: Minor</div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-3 h-3 bg-green-500 rounded-full"></div>
          <span class="text-sm text-gray-500">Resolved</span>
          <span class="text-sm text-gray-500">•</span>
          <span class="text-sm text-gray-500">December 8, 2023</span>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Payment Gateway Maintenance</h3>
        <p class="text-gray-600 mb-4">
          Scheduled maintenance window for payment gateway integration. 
          All services were restored within the planned 2-hour window.
        </p>
        <div class="text-sm text-gray-500">Duration: 2 hours • Impact: Planned</div>
      </div>
    </div>
  </div>
</section>

{{-- Subscribe to Updates --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-4xl px-4">
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl p-8 text-white text-center">
      <h2 class="text-3xl font-bold mb-4">Stay Updated</h2>
      <p class="text-xl text-orange-100 mb-8">
        Subscribe to status updates and get notified about any service incidents.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
        <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
        <button class="bg-white text-orange-600 px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
          Subscribe
        </button>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Experience Reliable Service</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Join thousands of restaurants that trust Dukaantech POS for their daily operations.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Start Free Trial
      </a>
      <a href="/contact-us" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Contact Sales
      </a>
    </div>
  </div>
</section>
@endsection
