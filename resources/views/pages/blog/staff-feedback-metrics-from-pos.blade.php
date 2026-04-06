{{-- resources/views/pages/blog/staff-feedback-metrics-from-pos.blade.php --}}
@extends('layouts.page')

@section('title', 'Coaching From POS Signals: Voids, Comps, and Ticket Times - Dukaantech Blog')
@section('meta')
<meta name="description" content="Turn transaction patterns into fair, specific feedback instead of vague “be faster” talks.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-02-20">February 20, 2024</time> · 7 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Coaching From POS Signals: Voids, Comps, and Ticket Times</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Your POS already records storylines—voids after fire, comps after complaints, long ticket times on certain tables. Use those signals for coaching, not surveillance theater.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Pick one metric per week</h2>
        <p>Overloading staff with dashboards helps no one. Focus on void rate or average table time, share the team average, and celebrate movement.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Context beats blame</h2>
        <p>High comps might mean new menu items or training week. Ask what happened before assuming intent.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Private feedback, public wins</h2>
        <p>Correct in private; praise in line-up when someone improves a measurable habit.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'staff-scheduling-labor-percent-sales']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Scheduling to Labor Percent of Sales—Without Burning Out the Team</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'restaurant-sales-data-analytics']) }}" class="text-orange-600 hover:text-orange-700 font-medium">How to Increase Restaurant Sales with Data Analytics</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
