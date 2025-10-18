{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Register - Dukaantech POS')

@section('meta')
<meta name="description" content="Create your Dukaantech POS account and start managing your restaurant operations today.">
@endsection

@section('content')
<div class="min-h-[100dvh] bg-gradient-to-br from-orange-50 via-white to-red-50">
  {{-- Header Component --}}
  <x-header />

  {{-- Register Section --}}
  <section class="py-20">
    <div class="mx-auto max-w-7xl px-4">
      <div class="max-w-md mx-auto">
        {{-- Register Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
          <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl mx-auto mb-6 flex items-center justify-center">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
              </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h1>
            <p class="text-gray-600">Start your free trial with Dukaantech POS</p>
          </div>

          {{-- Register Form --}}
          <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            
            {{-- Name Field --}}
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
              <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autocomplete="name" 
                autofocus
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('name') border-red-500 @enderror"
                placeholder="Enter your full name"
              >
              @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Email Field --}}
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
              <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autocomplete="email"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('email') border-red-500 @enderror"
                placeholder="Enter your email"
              >
              @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Restaurant Name Field --}}
            <div>
              <label for="restaurant_name" class="block text-sm font-medium text-gray-700 mb-2">Restaurant Name</label>
              <input 
                id="restaurant_name" 
                type="text" 
                name="restaurant_name" 
                value="{{ old('restaurant_name') }}" 
                required
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('restaurant_name') border-red-500 @enderror"
                placeholder="Enter your restaurant name"
              >
              @error('restaurant_name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Phone Field --}}
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
              <input 
                id="phone" 
                type="tel" 
                name="phone" 
                value="{{ old('phone') }}" 
                required
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('phone') border-red-500 @enderror"
                placeholder="Enter your phone number"
              >
              @error('phone')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Password Field --}}
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
              <div class="relative">
                <input 
                  id="password" 
                  type="password" 
                  name="password" 
                  required 
                  autocomplete="new-password"
                  class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('password') border-red-500 @enderror"
                  placeholder="Create a password"
                >
                <button 
                  type="button" 
                  onclick="togglePassword('password')"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                  <svg id="password-eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  <svg id="password-eye-slash" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                  </svg>
                </button>
              </div>
              @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Confirm Password Field --}}
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
              <div class="relative">
                <input 
                  id="password_confirmation" 
                  type="password" 
                  name="password_confirmation" 
                  required 
                  autocomplete="new-password"
                  class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                  placeholder="Confirm your password"
                >
                <button 
                  type="button" 
                  onclick="togglePassword('password_confirmation')"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                  <svg id="password_confirmation-eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  <svg id="password_confirmation-eye-slash" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                  </svg>
                </button>
              </div>
            </div>

            {{-- Terms and Conditions --}}
            <div class="flex items-start">
              <div class="flex items-center h-5">
                <input 
                  id="terms" 
                  name="terms" 
                  type="checkbox" 
                  required
                  class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                >
              </div>
              <div class="ml-3 text-sm">
                <label for="terms" class="text-gray-700">
                  I agree to the 
                  <a href="#" class="text-orange-600 hover:text-orange-500 transition-colors">Terms of Service</a> 
                  and 
                  <a href="#" class="text-orange-600 hover:text-orange-500 transition-colors">Privacy Policy</a>
                </label>
              </div>
            </div>

            {{-- reCAPTCHA Widget --}}
            <div class="flex justify-center">
              <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            </div>
            @error('g-recaptcha-response')
              <p class="mt-1 text-sm text-red-600 text-center">{{ $message }}</p>
            @enderror

            {{-- Register Button --}}
            <button 
              type="submit" 
              class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-4 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
            >
              Create Account
            </button>
          </form>


          {{-- Sign In Link --}}
          <div class="mt-8 text-center">
            <p class="text-gray-600">
              Already have an account? 
              <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-500 font-semibold transition-colors">
                Sign in here
              </a>
            </p>
          </div>
        </div>

        {{-- Benefits --}}
        <div class="mt-8">
          <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">What you get with your free account:</h3>
            <div class="grid grid-cols-1 gap-4">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm text-gray-700">Forever free plan</span>
              </div>
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm text-gray-700">Complete POS system</span>
              </div>
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm text-gray-700">Advanced reporting</span>
              </div>
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm text-gray-700">Advanced analytics</span>
              </div>
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

{{-- Register Form JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('register') }}"]');
    
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

function togglePassword(fieldId) {
  const input = document.getElementById(fieldId);
  const eyeIcon = document.getElementById(fieldId + '-eye');
  const eyeSlashIcon = document.getElementById(fieldId + '-eye-slash');
  
  if (input.type === 'password') {
    input.type = 'text';
    eyeIcon.classList.add('hidden');
    eyeSlashIcon.classList.remove('hidden');
  } else {
    input.type = 'password';
    eyeIcon.classList.remove('hidden');
    eyeSlashIcon.classList.add('hidden');
  }
}
</script>
@endsection