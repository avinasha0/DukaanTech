{{-- resources/views/pages/community.blade.php --}}
@extends('layouts.page')

@section('title', 'Community - Dukaantech POS')
@section('meta')
<meta name="description" content="Join the Dukaantech POS community. Connect with other restaurant owners, share experiences, and get support from peers.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Dukaantech
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Community
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Connect with fellow restaurant owners, share experiences, and learn from each other
      </p>
    </div>
  </div>
</section>

{{-- Community Features --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Community Features</h2>
      <p class="text-xl text-gray-600">Connect, learn, and grow together</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Discussion Forums</h3>
        <p class="text-gray-600 mb-4">Ask questions, share tips, and get advice from fellow restaurant owners</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• General discussions</li>
          <li>• Feature requests</li>
          <li>• Best practices</li>
          <li>• Troubleshooting</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Webinars & Events</h3>
        <p class="text-gray-600 mb-4">Join live webinars, workshops, and community events</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Monthly webinars</li>
          <li>• Product demos</li>
          <li>• Industry insights</li>
          <li>• Networking events</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Knowledge Base</h3>
        <p class="text-gray-600 mb-4">Access comprehensive guides, tutorials, and documentation</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Getting started guides</li>
          <li>• Video tutorials</li>
          <li>• FAQ section</li>
          <li>• Best practices</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- Community Stats --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Community Stats</h2>
      <p class="text-xl text-gray-600">Growing community of restaurant owners</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-2">5,000+</h3>
        <p class="text-gray-600">Active Members</p>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-2">12,000+</h3>
        <p class="text-gray-600">Discussions</p>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-2">50+</h3>
        <p class="text-gray-600">Monthly Events</p>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-2">500+</h3>
        <p class="text-gray-600">Knowledge Articles</p>
      </div>
    </div>
  </div>
</section>

{{-- Recent Discussions --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Recent Discussions</h2>
      <p class="text-xl text-gray-600">Latest conversations in the community</p>
    </div>
    
    <div class="space-y-6">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-all">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center">
            <span class="text-white font-bold">RK</span>
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
              <h3 class="text-lg font-bold text-gray-900">How to optimize inventory management?</h3>
              <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-semibold">Inventory</span>
            </div>
            <p class="text-gray-600 mb-3">
              I'm looking for tips on how to better manage my restaurant inventory. 
              Any suggestions on setting up automated alerts?
            </p>
            <div class="flex items-center gap-4 text-sm text-gray-500">
              <span>Rajesh Kumar • 2 hours ago</span>
              <span>5 replies</span>
              <span>12 likes</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-all">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
            <span class="text-white font-bold">PS</span>
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
              <h3 class="text-lg font-bold text-gray-900">Best practices for staff scheduling</h3>
              <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Staff Management</span>
            </div>
            <p class="text-gray-600 mb-3">
              Sharing some tips that have worked well for our restaurant. 
              Would love to hear your experiences too!
            </p>
            <div class="flex items-center gap-4 text-sm text-gray-500">
              <span>Priya Sharma • 4 hours ago</span>
              <span>8 replies</span>
              <span>15 likes</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-all">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
            <span class="text-white font-bold">AP</span>
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
              <h3 class="text-lg font-bold text-gray-900">Feature request: Multi-location reporting</h3>
              <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">Feature Request</span>
            </div>
            <p class="text-gray-600 mb-3">
              It would be great to have consolidated reporting across multiple locations. 
              Anyone else interested in this feature?
            </p>
            <div class="flex items-center gap-4 text-sm text-gray-500">
              <span>Amit Patel • 6 hours ago</span>
              <span>12 replies</span>
              <span>23 likes</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Join Community --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-4xl px-4">
    <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Join Our Community</h2>
      <p class="text-xl text-gray-600 mb-8">
        Connect with fellow restaurant owners and get the most out of Dukaantech POS.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="#" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
          Join Community
        </a>
        <a href="/help-center" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg text-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
          Browse Knowledge Base
        </a>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Join Our Community?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Start your free trial today and become part of our growing community of successful restaurant owners.
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
