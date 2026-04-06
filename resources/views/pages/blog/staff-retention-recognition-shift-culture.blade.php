{{-- resources/views/pages/blog/staff-retention-recognition-shift-culture.blade.php --}}
@extends('layouts.page')

@section('title', 'Retention, Recognition, and Shift Culture on Busy Nights - Dukaantech Blog')
@section('meta')
<meta name="description" content="Small habits—clear roles, shout-outs, predictable breaks—reduce churn where it hurts most.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-03-08">March 8, 2024</time> · 6 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Retention, Recognition, and Shift Culture on Busy Nights</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Pay matters, but so does predictability. Teams stay when they know who leads the floor, when breaks happen, and that good work gets noticed the same shift—not only at reviews.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Role clarity under fire</h2>
        <p>Who runs the pass? Who covers the door? Ambiguity during rush breeds resentment. Post a simple map for each service model.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Micro-recognition</h2>
        <p>Two genuine shout-outs per pre-shift meeting cost nothing and reinforce behaviors you want repeated.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Listen for exit signals</h2>
        <p>When strong performers pick up extra shifts elsewhere, schedule a conversation before they ghost. Sometimes the fix is a small schedule tweak.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'staff-onboarding-trainers-checklists']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Onboarding That Sticks: Trainers, Checklists, and First-Week Wins</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'staff-management-best-practices-restaurants']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Staff Management Best Practices for Restaurants</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
