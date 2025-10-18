{{-- resources/views/pages/careers.blade.php --}}
@extends('layouts.page')

@section('title', 'Careers - Dukaantech POS')
@section('meta')
<meta name="description" content="Join Dukaantech POS team and help revolutionize restaurant management in India. Remote-first culture with competitive benefits.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Join Our
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Mission
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Help us revolutionize restaurant management across India. Work with a passionate team building technology that makes a real difference.
      </p>
    </div>
  </div>
</section>

{{-- Why Work With Us --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Work With Us?</h2>
      <p class="text-xl text-gray-600">We offer more than just a job - we offer a mission</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Remote-First Culture</h3>
        <p class="text-gray-600">Work from anywhere in India with flexible hours and a healthy work-life balance.</p>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Learning & Growth</h3>
        <p class="text-gray-600">Continuous learning opportunities, conferences, and skill development programs.</p>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Make an Impact</h3>
        <p class="text-gray-600">Work on products that directly impact thousands of restaurant owners across India.</p>
      </div>
    </div>
  </div>
</section>

{{-- Open Positions --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Open Positions</h2>
      <p class="text-xl text-gray-600">Find your perfect role with us</p>
    </div>
    
    <div class="space-y-6">
      <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-all">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Senior Software Engineer</h3>
            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                Remote
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Full-time
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Engineering
              </span>
            </div>
            <p class="text-gray-600 mb-4">
              We're looking for a Senior Software Engineer to join our core platform team. You'll work on building scalable, 
              reliable systems that power thousands of restaurants across India.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">PHP</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Laravel</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">MySQL</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">AWS</span>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <a href="#" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all text-center">
              Apply Now
            </a>
            <a href="#" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all text-center">
              Learn More
            </a>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-all">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Product Manager</h3>
            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                Mumbai
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Full-time
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Product
              </span>
            </div>
            <p class="text-gray-600 mb-4">
              Lead product strategy and execution for our core POS platform. Work closely with engineering, design, 
              and customer success teams to deliver features that delight our restaurant customers.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Product Strategy</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">User Research</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Analytics</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">B2B SaaS</span>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <a href="#" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all text-center">
              Apply Now
            </a>
            <a href="#" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all text-center">
              Learn More
            </a>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-all">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Customer Success Manager</h3>
            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                Delhi
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Full-time
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Customer Success
              </span>
            </div>
            <p class="text-gray-600 mb-4">
              Help our restaurant customers succeed with Dukaantech POS. Work with customers to optimize their operations, 
              provide training, and ensure they get maximum value from our platform.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Customer Success</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Training</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Restaurant Industry</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Communication</span>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <a href="#" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all text-center">
              Apply Now
            </a>
            <a href="#" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all text-center">
              Learn More
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Benefits --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Benefits & Perks</h2>
      <p class="text-xl text-gray-600">We take care of our team</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Competitive Salary</h3>
        <p class="text-gray-600">Market-competitive compensation with performance bonuses</p>
      </div>
      
      <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Health Insurance</h3>
        <p class="text-gray-600">Comprehensive health insurance for you and your family</p>
      </div>
      
      <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Learning Budget</h3>
        <p class="text-gray-600">Annual learning budget for courses, conferences, and books</p>
      </div>
      
      <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Flexible PTO</h3>
        <p class="text-gray-600">Unlimited paid time off with flexible work arrangements</p>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Don't See Your Role?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      We're always looking for talented individuals who share our passion for revolutionizing restaurant management. 
      Send us your resume and let us know how you'd like to contribute.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/contact-us" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Send Your Resume
      </a>
      <a href="/about-us" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Learn About Us
      </a>
    </div>
  </div>
</section>
@endsection
