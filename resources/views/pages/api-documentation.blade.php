{{-- resources/views/pages/api-documentation.blade.php --}}
@extends('layouts.page')

@section('title', 'API Documentation - Dukaantech POS')
@section('meta')
<meta name="description" content="Complete API documentation for Dukaantech POS. Build custom integrations with our RESTful API, webhooks, and SDKs.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        API
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Documentation
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Build powerful integrations with Dukaantech POS using our comprehensive REST API
      </p>
    </div>
  </div>
</section>

{{-- API Overview --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">API Overview</h2>
      <p class="text-xl text-gray-600">Everything you need to integrate with Dukaantech POS</p>
    </div>
    
    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">RESTful API</h3>
        <p class="text-gray-600 mb-4">Standard HTTP methods with JSON responses for easy integration.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• GET, POST, PUT, DELETE</li>
          <li>• JSON request/response</li>
          <li>• RESTful resource URLs</li>
          <li>• HTTP status codes</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Authentication</h3>
        <p class="text-gray-600 mb-4">Secure API access using API keys and OAuth 2.0.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• API Key authentication</li>
          <li>• OAuth 2.0 support</li>
          <li>• Rate limiting</li>
          <li>• IP whitelisting</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5L19.5 4.5M19.5 4.5L19.5 9M19.5 4.5L15 4.5"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Webhooks</h3>
        <p class="text-gray-600 mb-4">Real-time notifications when events occur in your system.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Order events</li>
          <li>• Payment notifications</li>
          <li>• Inventory updates</li>
          <li>• Custom events</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- API Endpoints --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">API Endpoints</h2>
      <p class="text-xl text-gray-600">Comprehensive endpoints for all POS operations</p>
    </div>
    
    <div class="grid md:grid-cols-2 gap-8">
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Orders</h3>
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold">GET</span>
            <code class="text-gray-700">/api/v1/orders</code>
            <span class="text-gray-500 text-sm">List orders</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-semibold">POST</span>
            <code class="text-gray-700">/api/v1/orders</code>
            <span class="text-gray-500 text-sm">Create order</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold">GET</span>
            <code class="text-gray-700">/api/v1/orders/{id}</code>
            <span class="text-gray-500 text-sm">Get order details</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm font-semibold">PUT</span>
            <code class="text-gray-700">/api/v1/orders/{id}</code>
            <span class="text-gray-500 text-sm">Update order</span>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Products</h3>
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold">GET</span>
            <code class="text-gray-700">/api/v1/products</code>
            <span class="text-gray-500 text-sm">List products</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-semibold">POST</span>
            <code class="text-gray-700">/api/v1/products</code>
            <span class="text-gray-500 text-sm">Create product</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold">GET</span>
            <code class="text-gray-700">/api/v1/products/{id}</code>
            <span class="text-gray-500 text-sm">Get product details</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-red-100 text-red-800 px-3 py-1 rounded text-sm font-semibold">DELETE</span>
            <code class="text-gray-700">/api/v1/products/{id}</code>
            <span class="text-gray-500 text-sm">Delete product</span>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Inventory</h3>
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold">GET</span>
            <code class="text-gray-700">/api/v1/inventory</code>
            <span class="text-gray-500 text-sm">List inventory</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm font-semibold">PUT</span>
            <code class="text-gray-700">/api/v1/inventory/{id}</code>
            <span class="text-gray-500 text-sm">Update stock</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-semibold">POST</span>
            <code class="text-gray-700">/api/v1/inventory/adjust</code>
            <span class="text-gray-500 text-sm">Adjust inventory</span>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Payments</h3>
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold">GET</span>
            <code class="text-gray-700">/api/v1/payments</code>
            <span class="text-gray-500 text-sm">List payments</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-semibold">POST</span>
            <code class="text-gray-700">/api/v1/payments</code>
            <span class="text-gray-500 text-sm">Process payment</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold">GET</span>
            <code class="text-gray-700">/api/v1/payments/{id}</code>
            <span class="text-gray-500 text-sm">Get payment details</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Code Examples --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Code Examples</h2>
      <p class="text-xl text-gray-600">Get started quickly with these code samples</p>
    </div>
    
    <div class="grid md:grid-cols-2 gap-12">
      <div>
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Create an Order</h3>
        <div class="bg-gray-900 rounded-2xl p-8 text-green-400 font-mono text-sm overflow-x-auto">
          <div class="mb-4">
            <span class="text-gray-500">// JavaScript Example</span>
          </div>
          <div class="mb-2">
            <span class="text-blue-400">const</span> <span class="text-white">response</span> <span class="text-white">=</span> <span class="text-blue-400">await</span> <span class="text-yellow-400">fetch</span><span class="text-white">(</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-green-400">'https://api.Dukaantechpos.com/v1/orders'</span><span class="text-white">,</span>
          </div>
          <div class="mb-2">
            <span class="text-white">{</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-yellow-400">method</span><span class="text-white">: </span><span class="text-green-400">'POST'</span><span class="text-white">,</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-yellow-400">headers</span><span class="text-white">: {</span>
          </div>
          <div class="ml-8 mb-2">
            <span class="text-green-400">'Authorization'</span><span class="text-white">: </span><span class="text-green-400">'Bearer YOUR_API_KEY'</span><span class="text-white">,</span>
          </div>
          <div class="ml-8 mb-2">
            <span class="text-green-400">'Content-Type'</span><span class="text-white">: </span><span class="text-green-400">'application/json'</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-white">},</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-yellow-400">body</span><span class="text-white">: </span><span class="text-yellow-400">JSON.stringify</span><span class="text-white">({</span>
          </div>
          <div class="ml-8 mb-2">
            <span class="text-green-400">customer_id</span><span class="text-white">: </span><span class="text-green-400">"12345"</span><span class="text-white">,</span>
          </div>
          <div class="ml-8 mb-2">
            <span class="text-green-400">items</span><span class="text-white">: [{</span>
          </div>
          <div class="ml-12 mb-2">
            <span class="text-green-400">product_id</span><span class="text-white">: </span><span class="text-green-400">"101"</span><span class="text-white">,</span>
          </div>
          <div class="ml-12 mb-2">
            <span class="text-green-400">quantity</span><span class="text-white">: </span><span class="text-blue-400">2</span>
          </div>
          <div class="ml-8 mb-2">
            <span class="text-white">}]</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-white">})</span>
          </div>
          <div class="mb-2">
            <span class="text-white">}</span>
          </div>
          <div class="mb-2">
            <span class="text-white">);</span>
          </div>
        </div>
      </div>
      
      <div>
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Python Example</h3>
        <div class="bg-gray-900 rounded-2xl p-8 text-green-400 font-mono text-sm overflow-x-auto">
          <div class="mb-4">
            <span class="text-gray-500"># Python Example</span>
          </div>
          <div class="mb-2">
            <span class="text-blue-400">import</span> <span class="text-white">requests</span>
          </div>
          <div class="mb-4">
            <span class="text-blue-400">import</span> <span class="text-white">json</span>
          </div>
          <div class="mb-4">
            <span class="text-gray-500"># API Configuration</span>
          </div>
          <div class="mb-2">
            <span class="text-white">api_key</span> <span class="text-white">=</span> <span class="text-green-400">"YOUR_API_KEY"</span>
          </div>
          <div class="mb-2">
            <span class="text-white">base_url</span> <span class="text-white">=</span> <span class="text-green-400">"https://api.Dukaantechpos.com/v1"</span>
          </div>
          <div class="mb-4">
            <span class="text-white">headers</span> <span class="text-white">=</span> <span class="text-white">{</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-green-400">"Authorization"</span><span class="text-white">: </span><span class="text-white">f</span><span class="text-green-400">"Bearer {api_key}"</span><span class="text-white">,</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-green-400">"Content-Type"</span><span class="text-white">: </span><span class="text-green-400">"application/json"</span>
          </div>
          <div class="mb-2">
            <span class="text-white">}</span>
          </div>
          <div class="mb-4">
            <span class="text-gray-500"># Create Order</span>
          </div>
          <div class="mb-2">
            <span class="text-white">order_data</span> <span class="text-white">=</span> <span class="text-white">{</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-green-400">"customer_id"</span><span class="text-white">: </span><span class="text-green-400">"12345"</span><span class="text-white">,</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-green-400">"items"</span><span class="text-white">: [{</span>
          </div>
          <div class="ml-8 mb-2">
            <span class="text-green-400">"product_id"</span><span class="text-white">: </span><span class="text-green-400">"101"</span><span class="text-white">,</span>
          </div>
          <div class="ml-8 mb-2">
            <span class="text-green-400">"quantity"</span><span class="text-white">: </span><span class="text-blue-400">2</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-white">}]</span>
          </div>
          <div class="mb-2">
            <span class="text-white">}</span>
          </div>
          <div class="mb-4">
            <span class="text-white">response</span> <span class="text-white">=</span> <span class="text-white">requests.post(</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-white">f</span><span class="text-green-400">"{base_url}/orders"</span><span class="text-white">,</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-white">headers</span><span class="text-white">=</span><span class="text-white">headers</span><span class="text-white">,</span>
          </div>
          <div class="ml-4 mb-2">
            <span class="text-white">json</span><span class="text-white">=</span><span class="text-white">order_data</span>
          </div>
          <div class="mb-2">
            <span class="text-white">)</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- SDKs and Tools --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">SDKs and Tools</h2>
      <p class="text-xl text-gray-600">Official SDKs and development tools</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">JavaScript SDK</h3>
        <p class="text-gray-600 mb-4">Official JavaScript SDK for web applications</p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Download →</a>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Python SDK</h3>
        <p class="text-gray-600 mb-4">Python SDK for backend integrations</p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Download →</a>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">PHP SDK</h3>
        <p class="text-gray-600 mb-4">PHP SDK for server-side applications</p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Download →</a>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Postman Collection</h3>
        <p class="text-gray-600 mb-4">Ready-to-use API collection</p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Download →</a>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Build?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Start building powerful integrations with Dukaantech POS API today.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Get API Access
      </a>
      <a href="/contact-us" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Contact Developer Support
      </a>
    </div>
  </div>
</section>
@endsection
