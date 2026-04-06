{{-- resources/views/pages/blog/staff-scheduling-labor-percent-sales.blade.php --}}
@extends('layouts.page')

@section('title', 'Scheduling to Labor Percent of Sales—Without Burning Out the Team - Dukaantech Blog')
@section('meta')
<meta name="description" content="Use forecasts and POS actuals to right-size shifts while keeping service standards intact.">
@endsection

@section('page_content')
@php($catKey = 'staff-management')
<article class="bg-white" itemscope itemtype="https://schema.org/BlogPosting">
  <section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-12 lg:py-16 border-b border-orange-100/60">
    <div class="mx-auto max-w-3xl px-4">
      <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('blog') }}" class="text-orange-600 hover:text-orange-700 font-medium">Blog</a>
        <span class="mx-2" aria-hidden="true">/</span>
        <a href="{{ route('blog.category', ['category' => $catKey]) }}" class="text-orange-600 hover:text-orange-700 font-medium">{{ config('blog.categories.'.$catKey.'.breadcrumb') }}</a>
      </nav>
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-01-15">January 15, 2024</time> · 8 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Scheduling to Labor Percent of Sales—Without Burning Out the Team</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Labor targets are useful guardrails, not excuses to run short-staffed. Pair sales forecasts with realistic coverage rules so guests and staff both feel the plan.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Anchor to net sales, not hope</h2>
        <p>Use trailing POS data by day-part and adjust for events, weather, and holidays. Build a base schedule, then flex with on-call or staggered cuts.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Minimum coverage</h2>
        <p>Define non-negotiable roles—one expo, two on the line, one host on Fridays. Labor percent should not drop below safe floors.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Communicate cuts early</h2>
        <p>Nothing erodes trust like sending people home five minutes before peak. If you must cut, do it with clear rules and consistent timing.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'staff-management-best-practices-restaurants']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Staff Management Best Practices for Restaurants</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'staff-feedback-metrics-from-pos']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Coaching From POS Signals: Voids, Comps, and Ticket Times</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
