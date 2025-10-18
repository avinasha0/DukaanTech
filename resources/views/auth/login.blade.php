{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login - Dukaantech POS')

@section('meta')
<meta name="description" content="Login to your Dukaantech POS account to manage your restaurant operations.">
@endsection

@section('content')
<div class="min-h-[100dvh] bg-gradient-to-br from-orange-50 via-white to-red-50">
  {{-- Header Component --}}
  <x-header />

  {{-- Login Section --}}
  <section class="py-20">
    <div class="mx-auto max-w-7xl px-4">
      <div class="max-w-md mx-auto">
        {{-- Login Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
          <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl mx-auto mb-6 flex items-center justify-center">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
              </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
            <p class="text-gray-600">Sign in to your Dukaantech POS account</p>
          </div>

          {{-- Login Form --}}
          <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            {{-- Email Field --}}
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                  </svg>
                </div>
                <input 
                  id="email" 
                  type="email" 
                  name="email" 
                  value="{{ old('email') }}" 
                  required 
                  autocomplete="email" 
                  autofocus
                  class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('email') border-red-500 @enderror"
                  placeholder="Enter your email"
                >
              </div>
              @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Password Field --}}
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
                <input 
                  id="password" 
                  type="password" 
                  name="password" 
                  required 
                  autocomplete="current-password"
                  class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('password') border-red-500 @enderror"
                  placeholder="Enter your password"
                >
              </div>
              @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Remember Me & Forgot Password --}}
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input 
                  id="remember" 
                  name="remember" 
                  type="checkbox" 
                  class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                  Remember me
                </label>
              </div>
              <a href="{{ route('password.request') }}" class="text-sm text-orange-600 hover:text-orange-500 transition-colors">
                Forgot password?
              </a>
            </div>

            {{-- reCAPTCHA Widget --}}
            <div class="flex justify-center">
              <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            </div>
            @error('g-recaptcha-response')
              <p class="mt-1 text-sm text-red-600 text-center">{{ $message }}</p>
            @enderror

            {{-- Login Button --}}
            <button 
              type="submit" 
              class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-4 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
            >
              Sign In
            </button>
          </form>


          {{-- Sign Up Link --}}
          <div class="mt-8 text-center">
            <p class="text-gray-600">
              Don't have an account? 
              <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-500 font-semibold transition-colors">
                Sign up for free
              </a>
            </p>
          </div>
        </div>

        {{-- Trust Indicators --}}
        <div class="mt-8 text-center">
          <div class="flex items-center justify-center gap-8 text-sm text-gray-600">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Secure Login</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>SSL Encrypted</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Real-Time Analytics</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Footer Component --}}
  <x-footer />
</div>

{{-- reCAPTCHA Script --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

{{-- Login Form JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('login') }}"]');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if reCAPTCHA is completed
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                e.preventDefault();
                alert('Please complete the reCAPTCHA verification.');
                return false;
            }
        });

        // Reset reCAPTCHA on form errors
        @if($errors->any())
            grecaptcha.reset();
        @endif
    }
});
</script>
@endsection