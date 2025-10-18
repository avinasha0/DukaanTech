@extends('layouts.app')

@section('title', 'About Us - Dukaantech POS | Restaurant Management Solutions')

@section('meta')
<meta name="description" content="Learn about Dukaantech POS - India's leading restaurant management platform. Discover our mission, team, and commitment to revolutionizing restaurant operations.">
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
          About
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
            Dukaantech POS
          </span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          We're on a mission to revolutionize restaurant management across India, 
          empowering thousands of restaurant owners with cutting-edge technology.
        </p>
      </div>
    </div>
  </section>

  {{-- Mission & Vision Section --}}
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <div>
          <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Mission</h2>
          <p class="text-xl text-gray-600 mb-8 leading-relaxed">
            To democratize restaurant technology by providing affordable, easy-to-use POS solutions 
            that help restaurant owners focus on what they do best - creating amazing food experiences.
          </p>
          <div class="space-y-6">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Innovation First</h3>
                <p class="text-gray-600">Constantly evolving our platform with the latest technology to stay ahead of industry needs.</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Customer Success</h3>
                <p class="text-gray-600">Every feature we build is designed with our customers' success in mind.</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Reliability</h3>
                <p class="text-gray-600">99.9% uptime guarantee ensures your business never stops running.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="relative">
          <div class="bg-gradient-to-br from-orange-100 to-red-100 rounded-3xl p-8">
            <div class="text-center">
              <div class="w-24 h-24 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl mx-auto mb-6 flex items-center justify-center">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
              <p class="text-gray-700 text-lg leading-relaxed">
                To become India's most trusted restaurant technology partner, 
                helping every restaurant owner achieve their dreams through 
                innovative, accessible, and reliable POS solutions.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Stats Section --}}
  <section class="py-20 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Impact in Numbers</h2>
        <p class="text-xl text-gray-600">The numbers speak for themselves</p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="text-center">
          <div class="text-5xl font-bold text-orange-600 mb-2">10,000+</div>
          <div class="text-lg text-gray-600">Restaurants Served</div>
        </div>
        <div class="text-center">
          <div class="text-5xl font-bold text-green-600 mb-2">₹2.5Cr+</div>
          <div class="text-lg text-gray-600">Revenue Generated</div>
        </div>
        <div class="text-center">
          <div class="text-5xl font-bold text-blue-600 mb-2">99.9%</div>
          <div class="text-lg text-gray-600">Uptime Guarantee</div>
        </div>
        <div class="text-center">
          <div class="text-5xl font-bold text-purple-600 mb-2">24/7</div>
          <div class="text-lg text-gray-600">Customer Support</div>
        </div>
      </div>
    </div>
  </section>

  {{-- Team Section --}}
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
        <p class="text-xl text-gray-600">The passionate people behind Dukaantech POS</p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="text-center">
          <div class="w-32 h-32 bg-gradient-to-br from-orange-500 to-red-600 rounded-full mx-auto mb-6 flex items-center justify-center">
            <span class="text-4xl font-bold text-white">RK</span>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Rajesh Kumar</h3>
          <p class="text-orange-600 font-semibold mb-2">Founder & CEO</p>
          <p class="text-gray-600">15+ years in restaurant technology. Former VP at leading POS companies.</p>
        </div>
        
        <div class="text-center">
          <div class="w-32 h-32 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full mx-auto mb-6 flex items-center justify-center">
            <span class="text-4xl font-bold text-white">PS</span>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Priya Sharma</h3>
          <p class="text-green-600 font-semibold mb-2">CTO</p>
          <p class="text-gray-600">Tech visionary with expertise in scalable cloud architecture and mobile solutions.</p>
        </div>
        
        <div class="text-center">
          <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full mx-auto mb-6 flex items-center justify-center">
            <span class="text-4xl font-bold text-white">AP</span>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Amit Patel</h3>
          <p class="text-blue-600 font-semibold mb-2">Head of Customer Success</p>
          <p class="text-gray-600">Ensuring every customer gets the most value from our platform.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Values Section --}}
  <section class="py-20 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
        <p class="text-xl text-gray-600">The principles that guide everything we do</p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Customer First</h3>
          <p class="text-gray-600">Every decision we make is guided by what's best for our customers and their success.</p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Innovation</h3>
          <p class="text-gray-600">We constantly push boundaries to bring you the most advanced restaurant technology.</p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Reliability</h3>
          <p class="text-gray-600">You can count on us to keep your business running smoothly, every single day.</p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Transparency</h3>
          <p class="text-gray-600">We believe in clear communication and honest business practices with all our partners.</p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Accessibility</h3>
          <p class="text-gray-600">Great technology should be accessible to restaurants of all sizes, not just the big chains.</p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Passion</h3>
          <p class="text-gray-600">We're passionate about helping restaurant owners succeed and grow their businesses.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
    <div class="mx-auto max-w-7xl px-4 text-center">
      <h2 class="text-4xl font-bold text-white mb-4">Ready to Join Our Success Story?</h2>
      <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
        Be part of the 10,000+ restaurants that trust Dukaantech POS to power their operations. 
        Start your journey with us today.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
          Start Free Trial
        </a>
        <a href="/contact-us" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
          Contact Us
        </a>
      </div>
    </div>
  </section>

  {{-- Footer Component --}}
  <x-footer />
</div>
@endsection
