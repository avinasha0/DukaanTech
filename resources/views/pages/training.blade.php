{{-- resources/views/pages/training.blade.php --}}
@extends('layouts.page')

@section('title', 'Training - Dukaantech POS')
@section('meta')
<meta name="description" content="Get comprehensive training for Dukaantech POS. Learn how to maximize your restaurant management with our training programs.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Training
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Programs
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Master Dukaantech POS with our comprehensive training programs designed for restaurant owners and staff
      </p>
    </div>
  </div>
</section>

{{-- Training Options --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Training Options</h2>
      <p class="text-xl text-gray-600">Choose the training that fits your needs</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Online Training</h3>
        <p class="text-gray-600 mb-4">Self-paced learning with video tutorials and interactive modules</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Video tutorials</li>
          <li>• Interactive modules</li>
          <li>• Practice exercises</li>
          <li>• Certificate of completion</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Live Webinars</h3>
        <p class="text-gray-600 mb-4">Interactive live sessions with Q&A and hands-on practice</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Live instructor-led sessions</li>
          <li>• Real-time Q&A</li>
          <li>• Hands-on practice</li>
          <li>• Recorded sessions available</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">On-site Training</h3>
        <p class="text-gray-600 mb-4">Personalized training at your restaurant location</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Personalized training</li>
          <li>• On-site support</li>
          <li>• Customized curriculum</li>
          <li>• Team training sessions</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- Training Topics --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Training Topics</h2>
      <p class="text-xl text-gray-600">Comprehensive coverage of all Dukaantech POS features</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">POS Basics</h3>
        <p class="text-gray-600 mb-4">Learn the fundamentals of using Dukaantech POS</p>
        <div class="text-sm text-gray-500">2 hours</div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Inventory Management</h3>
        <p class="text-gray-600 mb-4">Master inventory tracking and management</p>
        <div class="text-sm text-gray-500">3 hours</div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Reports & Analytics</h3>
        <p class="text-gray-600 mb-4">Understand your business data and insights</p>
        <div class="text-sm text-gray-500">2 hours</div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Staff Management</h3>
        <p class="text-gray-600 mb-4">Manage your team effectively</p>
        <div class="text-sm text-gray-500">2 hours</div>
      </div>
    </div>
  </div>
</section>

{{-- Schedule Training --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-4xl px-4">
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl p-8 text-white text-center">
      <h2 class="text-3xl font-bold mb-4">Schedule Your Training</h2>
      <p class="text-xl text-orange-100 mb-8">
        Ready to get trained on Dukaantech POS? Contact our training team to schedule your session.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/contact-us" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
          Contact Training Team
        </a>
        <a href="mailto:training@dukaantech.com" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
          Email Us
        </a>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Start your free trial today and get access to our comprehensive training programs.
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
