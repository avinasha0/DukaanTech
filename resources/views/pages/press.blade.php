{{-- resources/views/pages/press.blade.php --}}
@extends('layouts.page')

@section('title', 'Press - Dukaantech POS')
@section('meta')
<meta name="description" content="Latest news, press releases, and media coverage about Dukaantech POS. Stay updated with our company announcements and achievements.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Press
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Center
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Latest news, press releases, and media coverage about Dukaantech POS
      </p>
    </div>
  </div>
</section>

{{-- Press Releases --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Press Releases</h2>
      <p class="text-xl text-gray-600">Official announcements and company news</p>
    </div>
    
    <div class="space-y-8">
      <article class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-all">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <div class="text-sm text-gray-500 mb-2">December 20, 2023</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Dukaantech POS Reaches 10,000 Restaurant Milestone</h3>
            <p class="text-gray-600 mb-4">
              Dukaantech POS announces that it has successfully onboarded its 10,000th restaurant customer, 
              marking a significant milestone in the company's mission to revolutionize restaurant management in India.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Milestone</span>
              <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Growth</span>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <a href="#" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all text-center">
              Read Full Release
            </a>
            <a href="#" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all text-center">
              Download PDF
            </a>
          </div>
        </div>
      </article>
      
      <article class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-all">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <div class="text-sm text-gray-500 mb-2">December 10, 2023</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Dukaantech POS Launches Mobile App for iOS and Android</h3>
            <p class="text-gray-600 mb-4">
              The company announces the launch of its mobile application, enabling restaurant owners to manage their 
              operations on the go with full POS functionality and real-time synchronization.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Product Launch</span>
              <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">Mobile</span>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <a href="#" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all text-center">
              Read Full Release
            </a>
            <a href="#" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all text-center">
              Download PDF
            </a>
          </div>
        </div>
      </article>
      
      <article class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-all">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <div class="text-sm text-gray-500 mb-2">November 25, 2023</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Dukaantech POS Secures Series A Funding Round</h3>
            <p class="text-gray-600 mb-4">
              The company announces the successful completion of its Series A funding round, raising ₹50 crores 
              to accelerate product development and expand market presence across India.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Funding</span>
              <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Investment</span>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <a href="#" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all text-center">
              Read Full Release
            </a>
            <a href="#" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all text-center">
              Download PDF
            </a>
          </div>
        </div>
      </article>
    </div>
  </div>
</section>

{{-- Media Coverage --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Media Coverage</h2>
      <p class="text-xl text-gray-600">What the media is saying about us</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <article class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
            <span class="text-sm font-bold text-gray-600">ET</span>
          </div>
          <div>
            <div class="font-semibold text-gray-900">Economic Times</div>
            <div class="text-sm text-gray-600">December 15, 2023</div>
          </div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">"Dukaantech POS Revolutionizes Restaurant Management"</h3>
        <p class="text-gray-600 mb-4">
          The Economic Times features Dukaantech POS as a leading innovator in restaurant technology, 
          highlighting its impact on small and medium restaurants across India.
        </p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Read Article →</a>
      </article>
      
      <article class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
            <span class="text-sm font-bold text-gray-600">BW</span>
          </div>
          <div>
            <div class="font-semibold text-gray-900">Business World</div>
            <div class="text-sm text-gray-600">December 8, 2023</div>
          </div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">"How Technology is Transforming Indian Restaurants"</h3>
        <p class="text-gray-600 mb-4">
          Business World interviews our CEO Rajesh Kumar about the digital transformation of the restaurant 
          industry and Dukaantech POS's role in this evolution.
        </p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Read Article →</a>
      </article>
      
      <article class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
            <span class="text-sm font-bold text-gray-600">TC</span>
          </div>
          <div>
            <div class="font-semibold text-gray-900">TechCrunch India</div>
            <div class="text-sm text-gray-600">November 30, 2023</div>
          </div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">"Dukaantech POS Raises Series A to Scale Restaurant Tech"</h3>
        <p class="text-gray-600 mb-4">
          TechCrunch covers our Series A funding announcement and discusses our plans for expanding 
          restaurant technology solutions across India.
        </p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Read Article →</a>
      </article>
    </div>
  </div>
</section>

{{-- Media Kit --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Media Kit</h2>
      <p class="text-xl text-gray-600">Resources for journalists and media professionals</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Company Logo</h3>
        <p class="text-gray-600 mb-4">High-resolution logos in various formats</p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Download</a>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Team Photos</h3>
        <p class="text-gray-600 mb-4">Professional photos of our leadership team</p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Download</a>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Fact Sheet</h3>
        <p class="text-gray-600 mb-4">Key company facts and statistics</p>
        <a href="#" class="text-orange-600 font-semibold hover:text-orange-700">Download</a>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Press Contact</h3>
        <p class="text-gray-600 mb-4">Get in touch with our press team</p>
        <a href="/contact-us" class="text-orange-600 font-semibold hover:text-orange-700">Contact</a>
      </div>
    </div>
  </div>
</section>

{{-- Contact Press --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-4xl px-4">
    <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Media Inquiries</h2>
      <p class="text-xl text-gray-600 mb-8">
        For press inquiries, interview requests, or media kit access, please contact our press team.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/contact-us" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
          Contact Press Team
        </a>
        <a href="mailto:press@Dukaantechpos.com" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg text-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
          Email Us
        </a>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Experience Dukaantech POS</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      See why thousands of restaurants trust Dukaantech POS for their management needs.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Start Free Trial
      </a>
      <a href="/demo" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Request Demo
      </a>
    </div>
  </div>
</section>
@endsection
