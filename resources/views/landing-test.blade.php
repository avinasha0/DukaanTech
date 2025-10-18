{{-- resources/views/landing.blade.php --}}
@extends('layouts.app')

@section('title', 'Test Landing Page')

@section('meta')
<meta name="description" content="Test page">
@endsection

@section('content')
<div class="min-h-screen bg-white">
    <h1 class="text-4xl font-bold text-center py-20">Welcome to Dukaantech POS</h1>
    <p class="text-center text-gray-600">Lightning-fast POS SaaS for modern restaurants</p>
</div>
@endsection
