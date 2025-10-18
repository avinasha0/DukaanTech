{{-- resources/views/auth/login-simple.blade.php --}}
@extends('layouts.app')

@section('title', 'Login - Dukaantech POS')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center">
  <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold text-center mb-6">Login</h1>
    <form>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <input type="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
      </div>
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
        <input type="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
      </div>
      <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition-colors">
        Login
      </button>
    </form>
  </div>
</div>
@endsection
