{{-- resources/views/pages/blog.blade.php --}}
@extends('layouts.page')

@section('title', 'Blog - Dukaantech POS')
@section('meta')
<meta name="description" content="Read the latest insights, tips, and updates from Dukaantech POS. Restaurant management tips, industry trends, and product updates.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Dukaantech
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Blog
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Insights, tips, and updates to help you run a successful restaurant business
      </p>
    </div>
  </div>
</section>

{{-- Featured Post --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl p-8 text-white">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <div>
          <div class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full text-sm font-medium mb-4">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Featured Article
          </div>
          <h2 class="text-3xl font-bold mb-4">10 Essential Tips for Restaurant Inventory Management</h2>
          <p class="text-orange-100 mb-6">
            Learn how to optimize your inventory management to reduce waste, control costs, and improve profitability. 
            These proven strategies have helped thousands of restaurants streamline their operations.
          </p>
          <a href="#" class="bg-white text-orange-600 px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
            Read More
          </a>
        </div>
        <div class="relative">
          <div class="bg-white/10 rounded-xl p-6 backdrop-blur">
            <div class="text-center">
              <div class="w-16 h-16 bg-white/20 rounded-xl mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold mb-2">Inventory Management</h3>
              <p class="text-orange-100 text-sm">Essential strategies for restaurant success</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Blog Posts --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Latest Articles</h2>
      <p class="text-xl text-gray-600">Stay updated with the latest insights</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <article class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="h-48 bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
          <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <div class="p-6">
          <div class="text-sm text-gray-500 mb-2">December 15, 2023</div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">How to Increase Restaurant Sales with Data Analytics</h3>
          <p class="text-gray-600 mb-4">
            Discover how data analytics can help you identify trends, optimize menu pricing, and increase your restaurant's revenue.
          </p>
          <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Read More →</a>
        </div>
      </article>
      
      <article class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="h-48 bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
          <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <div class="p-6">
          <div class="text-sm text-gray-500 mb-2">December 10, 2023</div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Staff Management Best Practices for Restaurants</h3>
          <p class="text-gray-600 mb-4">
            Learn effective strategies for hiring, training, and retaining restaurant staff to build a strong team.
          </p>
          <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Read More →</a>
        </div>
      </article>
      
      <article class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="h-48 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
          <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <div class="p-6">
          <div class="text-sm text-gray-500 mb-2">December 5, 2023</div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Mobile POS: The Future of Restaurant Operations</h3>
          <p class="text-gray-600 mb-4">
            Explore how mobile POS systems are revolutionizing restaurant operations and improving customer experience.
          </p>
          <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Read More →</a>
        </div>
      </article>
    </div>
  </div>
</section>

{{-- Categories --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Browse by Category</h2>
      <p class="text-xl text-gray-600">Find articles that interest you most</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">POS Tips</h3>
        <p class="text-gray-600 mb-4">Tips and tricks for getting the most out of your POS system</p>
        <div class="text-sm text-gray-500">12 articles</div>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Inventory</h3>
        <p class="text-gray-600 mb-4">Best practices for restaurant inventory management</p>
        <div class="text-sm text-gray-500">8 articles</div>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Staff Management</h3>
        <p class="text-gray-600 mb-4">Strategies for effective restaurant staff management</p>
        <div class="text-sm text-gray-500">6 articles</div>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Analytics</h3>
        <p class="text-gray-600 mb-4">Using data to make better business decisions</p>
        <div class="text-sm text-gray-500">10 articles</div>
      </div>
    </div>
  </div>
</section>

{{-- Newsletter --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-4xl px-4">
    <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Stay Updated</h2>
      <p class="text-xl text-gray-600 mb-8">
        Subscribe to our newsletter and get the latest restaurant management tips delivered to your inbox.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
        <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
        <button class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
          Subscribe
        </button>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Transform Your Restaurant?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Put these insights into practice with Dukaantech POS. Start your free trial today and see the difference.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Start Free Trial
      </a>
      <a href="/features" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Explore Features
      </a>
    </div>
  </div>
</section>
@endsection
