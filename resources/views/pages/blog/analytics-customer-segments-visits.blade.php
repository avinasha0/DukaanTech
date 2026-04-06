{{-- resources/views/pages/blog/analytics-customer-segments-visits.blade.php --}}
@extends('layouts.page')

@section('title', 'Customer Frequency and Segments You Can Actually Use - Dukaantech Blog')
@section('meta')
<meta name="description" content="From walk-ins to regulars, segment lightly and act on one campaign at a time.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-02-12">February 12, 2024</time> · 7 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Customer Frequency and Segments You Can Actually Use</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Fancy CRM segments fail when nobody acts on them. Start with visit frequency and average ticket—simple buckets your front line can recognize and your marketing can test.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">New, occasional, regular</h2>
        <p>Tag guests by visits in the last ninety days. New guests get welcome offers; lapsing regulars get a nudge before they churn silently.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Privacy and respect</h2>
        <p>Collect only what you will use. Clear opt-in for SMS beats spray-and-pray email lists.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">One experiment at a time</h2>
        <p>Change a single variable—Tuesday lunch promo, not five campaigns at once—so results mean something.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'analytics-menu-mix-engineering']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Menu Mix and Engineering for Profit, Not Just Popularity</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'analytics-seasonality-events-planning']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Seasonality, Events, and Demand Planning From Historical POS Data</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
