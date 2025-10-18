{{-- resources/views/layouts/page.blade.php --}}
@extends('layouts.app')

@section('title', $pageTitle ?? 'Dukaantech POS')

@section('meta')
<meta name="description" content="{{ $pageDescription ?? 'Complete restaurant management solution with POS, inventory, staff management, and analytics.' }}">
@endsection

@section('content')
<div class="min-h-screen bg-white">
  {{-- Header Component --}}
  <x-header />

  {{-- Page Content --}}
  <main>
    @yield('page_content')
  </main>

  {{-- Footer Component --}}
  <x-footer />
</div>
@endsection
