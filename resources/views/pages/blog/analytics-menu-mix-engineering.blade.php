{{-- resources/views/pages/blog/analytics-menu-mix-engineering.blade.php --}}
@extends('layouts.page')

@section('title', 'Menu Mix and Engineering for Profit, Not Just Popularity - Dukaantech Blog')
@section('meta')
<meta name="description" content="Stars, puzzles, and dogs—use sales mix to promote what pays and fix what drags margin.">
@endsection

@section('page_content')
@php($catKey = 'analytics')
<article class="bg-white" itemscope itemtype="https://schema.org/BlogPosting">
  <section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-12 lg:py-16 border-b border-orange-100/60">
    <div class="mx-auto max-w-3xl px-4">
      <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('blog') }}" class="text-orange-600 hover:text-orange-700 font-medium">Blog</a>
        <span class="mx-2" aria-hidden="true">/</span>
        <a href="{{ route('blog.category', ['category' => $catKey]) }}" class="text-orange-600 hover:text-orange-700 font-medium">{{ config('blog.categories.'.$catKey.'.breadcrumb') }}</a>
      </nav>
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-01-05">January 5, 2024</time> · 8 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Menu Mix and Engineering for Profit, Not Just Popularity</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Best-sellers are not always best for the bank. Plot contribution margin against sales volume to find stars to feature, puzzles to repricing, and dogs to retire or rework.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Build the matrix monthly</h2>
        <p>Export item sales and item margin from your POS or BI tool. Color-code quadrants and share with kitchen and bar leads—pricing and plating decisions should be joint.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Nudge with placement</h2>
        <p>Stars belong in server spiels and menu eye-path. Puzzles might need better stories or smaller portions, not just a price hike.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Kill with kindness</h2>
        <p>Removing a dog frees line capacity and mental load. Do it with data, not drama.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'restaurant-sales-data-analytics']) }}" class="text-orange-600 hover:text-orange-700 font-medium">How to Increase Restaurant Sales with Data Analytics</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'analytics-cashier-kpis-daily-weekly']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Cashier and Service KPIs: Daily Pulse, Weekly Trends</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
