@extends('layouts.app')

@section('title', 'Contact Us - Dukaantech POS | Get in Touch with Our Team')

@section('meta')
<meta name="description" content="Contact Dukaantech POS for support, sales inquiries, or general questions. We're here to help you succeed with your restaurant management needs.">
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
          Get in
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
            Touch
          </span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          Have questions? Need support? We're here to help you succeed. 
          Reach out to our team and we'll get back to you within 24 hours.
        </p>
      </div>
    </div>
  </section>

  {{-- Contact Form & Info Section --}}
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4">
      <div class="grid lg:grid-cols-2 gap-16">
        {{-- Contact Form --}}
        <div>
          <h2 class="text-3xl font-bold text-gray-900 mb-8">Send us a Message</h2>
          
          {{-- Success/Error Messages --}}
          @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
              <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('contact-us.submit') }}" class="space-y-6" id="contactForm">
            @csrf
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label for="firstName" class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                <input type="text" id="firstName" name="firstName" value="{{ old('firstName') }}" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('firstName') border-red-500 @enderror">
                @error('firstName')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label for="lastName" class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                <input type="text" id="lastName" name="lastName" value="{{ old('lastName') }}" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('lastName') border-red-500 @enderror">
                @error('lastName')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
            
            <div>
              <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('email') border-red-500 @enderror">
              @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            
            <div>
              <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
              <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('phone') border-red-500 @enderror">
              @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            
            <div>
              <label for="company" class="block text-sm font-semibold text-gray-700 mb-2">Restaurant/Company Name</label>
              <input type="text" id="company" name="company" value="{{ old('company') }}" 
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('company') border-red-500 @enderror">
              @error('company')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            
            <div>
              <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject *</label>
              <select id="subject" name="subject" required 
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('subject') border-red-500 @enderror">
                <option value="">Select a subject</option>
                <option value="sales" {{ old('subject') == 'sales' ? 'selected' : '' }}>Sales Inquiry</option>
                <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Technical Support</option>
                <option value="demo" {{ old('subject') == 'demo' ? 'selected' : '' }}>Request Demo</option>
                <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
              </select>
              @error('subject')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            
            <div>
              <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Message *</label>
              <textarea id="message" name="message" rows="6" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
              @error('message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            
            {{-- reCAPTCHA Widget --}}
            <div class="flex justify-center">
              <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            </div>
            @error('g-recaptcha-response')
              <p class="mt-1 text-sm text-red-600 text-center">{{ $message }}</p>
            @enderror
            
            <button type="submit" id="submitBtn"
                    class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-4 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed">
              <span id="submitText">Send Message</span>
              <span id="submitLoading" class="hidden">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Sending...
              </span>
            </button>
          </form>
        </div>

        {{-- Contact Information --}}
        <div>
          <h2 class="text-3xl font-bold text-gray-900 mb-8">Contact Information</h2>
          
          <div class="space-y-8">
            {{-- Office Address --}}
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Office Address</h3>
                <p class="text-gray-600 leading-relaxed">
                  DukaanTech<br>
                  Vaishnavi Tech Park, Sarjapur Rd<br>
                  Bellandur, Bengaluru, Karnataka 560103<br>
                  India
                </p>
              </div>
            </div>

            {{-- Phone Numbers --}}
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Phone Numbers</h3>
                <div class="space-y-1">
                  <p class="text-gray-600">Sales: <span class="font-semibold">+91 98765 43210</span></p>
                  <p class="text-gray-600">Support: <span class="font-semibold">+91 98765 43211</span></p>
                  <p class="text-gray-600">Emergency: <span class="font-semibold">+91 98765 43212</span></p>
                </div>
              </div>
            </div>

            {{-- Email Addresses --}}
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Email Addresses</h3>
                <div class="space-y-1">
                  <p class="text-gray-600">General: <span class="font-semibold">info@dukaantech.com</span></p>
                  <p class="text-gray-600">Sales: <span class="font-semibold">sales@dukaantech.com</span></p>
                  <p class="text-gray-600">Support: <span class="font-semibold">support@dukaantech.com</span></p>
                </div>
              </div>
            </div>

            {{-- Business Hours --}}
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Business Hours</h3>
                <div class="space-y-1">
                  <p class="text-gray-600">Monday - Friday: <span class="font-semibold">9:00 AM - 6:00 PM</span></p>
                  <p class="text-gray-600">Saturday: <span class="font-semibold">10:00 AM - 4:00 PM</span></p>
                  <p class="text-gray-600">Sunday: <span class="font-semibold">Closed</span></p>
                  <p class="text-sm text-orange-600 font-semibold">Email Support Available</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Support Options Section --}}
  <section class="py-20 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">How Can We Help?</h2>
        <p class="text-xl text-gray-600">Choose the support option that works best for you</p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-orange-200">
          <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Live Chat</h3>
          <p class="text-gray-600 mb-6">Get instant help from our support team. Available 24/7 for all customers.</p>
          <div class="flex items-center justify-between">
            <a href="#" class="text-orange-600 font-semibold hover:text-orange-700 transition-colors">
              Start Chat →
            </a>
            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
              Upcoming Features
            </span>
          </div>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
          <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Help Center</h3>
          <p class="text-gray-600 mb-6">Browse our comprehensive knowledge base and find answers to common questions.</p>
          <a href="http://localhost:8000/help-center" class="text-green-600 font-semibold hover:text-green-700 transition-colors">
            Visit Help Center →
          </a>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
          <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Video Tutorials</h3>
          <p class="text-gray-600 mb-6">Watch step-by-step video guides to master all features of Dukaantech POS.</p>
          <a href="http://localhost:8000/help-center" class="text-blue-600 font-semibold hover:text-blue-700 transition-colors">
            Watch Tutorials →
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
        Don't wait! Start your free trial today and experience the power of Dukaantech POS. 
        Our team is standing by to help you succeed.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
          Start Free Trial
        </a>
        <a href="#contact-form" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
          Schedule Demo
        </a>
      </div>
    </div>
  </section>

  {{-- Footer Component --}}
  <x-footer />
</div>

{{-- reCAPTCHA Script --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

{{-- Contact Form JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoading = document.getElementById('submitLoading');

    form.addEventListener('submit', function(e) {
        // Check if reCAPTCHA is completed
        const recaptchaResponse = grecaptcha.getResponse();
        if (!recaptchaResponse) {
            e.preventDefault();
            alert('Please complete the reCAPTCHA verification.');
            return false;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
    });

    // Reset reCAPTCHA on form errors
    @if($errors->any())
        grecaptcha.reset();
    @endif
});
</script>
@endsection
