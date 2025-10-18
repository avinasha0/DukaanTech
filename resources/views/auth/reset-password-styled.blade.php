{{-- resources/views/auth/reset-password-styled.blade.php --}}
@extends('layouts.app')

@section('title', 'Reset Password - Dukaantech POS')

@section('meta')
<meta name="description" content="Reset your Dukaantech POS password. Enter your new password to complete the reset process.">
@endsection

@section('content')
<div class="min-h-[100dvh] bg-white text-gray-900">
  {{-- Header Component --}}
  <x-header />

  {{-- Reset Password Section --}}
  <section class="relative bg-gradient-to-br from-orange-50 via-white to-red-50 py-20 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23F66C17" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
    
    <div class="mx-auto max-w-7xl px-4 relative">
      <div class="max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
          <!-- Logo and Title -->
          <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-3 mb-6">
              <img src="/images/logos/dukaantech-logo.png" alt="Dukaantech Logo" class="h-12 w-auto">
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Reset Your Password</h1>
            <p class="text-gray-600">Enter your new password below to complete the reset process.</p>
          </div>

          <!-- Reset Password Form -->
          <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
              </label>
              <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email', $request->email) }}" 
                required 
                autofocus
                autocomplete="username"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('email') border-red-500 @enderror"
                placeholder="Enter your email address"
              >
              @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                New Password
              </label>
              <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('password') border-red-500 @enderror"
                placeholder="Enter your new password"
              >
              @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Confirm Password -->
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                Confirm New Password
              </label>
              <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('password_confirmation') border-red-500 @enderror"
                placeholder="Confirm your new password"
              >
              @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password Requirements -->
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
              <h4 class="text-sm font-medium text-gray-900 mb-2">Password Requirements:</h4>
              <ul class="text-sm text-gray-600 space-y-1">
                <li>• At least 8 characters long</li>
                <li>• Mix of letters and numbers</li>
                <li>• Special characters recommended</li>
              </ul>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit"
              class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
            >
              Reset Password
            </button>
          </form>

          <!-- Back to Login -->
          <div class="mt-8 text-center">
            <p class="text-gray-600">
              Remember your password? 
              <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-medium transition-colors">
                Back to Login
              </a>
            </p>
          </div>

          <!-- Security Note -->
          <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <div>
                <h4 class="text-sm font-medium text-green-900 mb-1">Secure Reset Process</h4>
                <p class="text-sm text-green-700">Your password will be securely updated and you'll be automatically logged in with your new credentials.</p>
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
@endsection
