{{-- resources/views/auth/forgot-password-styled.blade.php --}}
@extends('layouts.app')

@section('title', 'Forgot Password - Dukaantech POS')

@section('meta')
<meta name="description" content="Reset your Dukaantech POS password. Enter your email address and we'll send you a password reset link.">
@endsection

@section('content')
<div class="min-h-[100dvh] bg-white text-gray-900">
  {{-- Header Component --}}
  <x-header />

  {{-- Forgot Password Section --}}
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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Forgot Password?</h1>
            <p class="text-gray-600">No problem! Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
          </div>

          <!-- Session Status -->
          @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-green-800 font-medium">{{ session('status') }}</span>
              </div>
            </div>
          @endif

          <!-- Forgot Password Form -->
          <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
              </label>
              <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('email') border-red-500 @enderror"
                placeholder="Enter your email address"
              >
              @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Submit Button -->
            <button 
              type="submit"
              class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
            >
              Send Password Reset Link
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
          <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
              <div>
                <h4 class="text-sm font-medium text-blue-900 mb-1">Secure Password Reset</h4>
                <p class="text-sm text-blue-700">We'll send you a secure link to reset your password. The link will expire in 60 minutes for your security.</p>
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
