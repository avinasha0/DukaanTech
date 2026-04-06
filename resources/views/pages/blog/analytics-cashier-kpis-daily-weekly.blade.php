{{-- resources/views/pages/blog/analytics-cashier-kpis-daily-weekly.blade.php --}}
@extends('layouts.page')

@section('title', 'Cashier and Service KPIs: Daily Pulse, Weekly Trends - Dukaantech Blog')
@section('meta')
<meta name="description" content="A short list of metrics your POS already knows—so you review what moves the needle.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-01-28">January 28, 2024</time> · 7 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Cashier and Service KPIs: Daily Pulse, Weekly Trends</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        You do not need twenty dashboards. Pick a handful of service metrics—ticket time, average check, void rate—and review them on a rhythm the team can expect.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Daily: exceptions only</h2>
        <p>Scan for outliers—sudden void spikes, payment errors, unusually long table times. Fix process before people.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Weekly: trends</h2>
        <p>Compare same weekday to last month. Seasonality and local events show up here.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Tie KPIs to actions</h2>
        <p>Each metric should have an owner and a next step. If nobody owns it, stop reporting it.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'analytics-seasonality-events-planning']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Seasonality, Events, and Demand Planning From Historical POS Data</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'restaurant-sales-data-analytics']) }}" class="text-orange-600 hover:text-orange-700 font-medium">How to Increase Restaurant Sales with Data Analytics</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
