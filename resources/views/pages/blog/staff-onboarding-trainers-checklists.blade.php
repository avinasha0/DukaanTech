{{-- resources/views/pages/blog/staff-onboarding-trainers-checklists.blade.php --}}
@extends('layouts.page')

@section('title', 'Onboarding That Sticks: Trainers, Checklists, and First-Week Wins - Dukaantech Blog')
@section('meta')
<meta name="description" content="Structure the first shifts so new hires can ring, run food, and handle voids with confidence.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-02-01">February 1, 2024</time> · 7 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Onboarding That Sticks: Trainers, Checklists, and First-Week Wins</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        The first week sets expectations for the next year. Assign a named trainer, publish a checklist, and celebrate small wins so new hires feel progress—not fog.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Shadow, then solo with guardrails</h2>
        <p>Start with observation, then limited POS permissions, then full role. Add one complexity per shift—modifiers before split checks, for example.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Checklists beat memory</h2>
        <p>Open, mid, close—each with five bullets. Laminate them; update when the menu changes.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Feedback after day three</h2>
        <p>Short, specific, kind. “You nailed greet times; next focus on modifier prompts.”</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'staff-retention-recognition-shift-culture']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Retention, Recognition, and Shift Culture on Busy Nights</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'staff-management-best-practices-restaurants']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Staff Management Best Practices for Restaurants</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
