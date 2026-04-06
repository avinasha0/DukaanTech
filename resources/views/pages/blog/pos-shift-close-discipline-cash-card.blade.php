{{-- resources/views/pages/blog/pos-shift-close-discipline-cash-card.blade.php --}}
@extends('layouts.page')

@section('title', 'Shift Close Discipline: Cash, Card, and Tip Reconciliation - Dukaantech Blog')
@section('meta')
<meta name="description" content="A repeatable close process reduces variance and builds trust between managers and owners.">
@endsection

@section('page_content')
@php($catKey = 'pos-tips')
<article class="bg-white" itemscope itemtype="https://schema.org/BlogPosting">
  <section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-12 lg:py-16 border-b border-orange-100/60">
    <div class="mx-auto max-w-3xl px-4">
      <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('blog') }}" class="text-orange-600 hover:text-orange-700 font-medium">Blog</a>
        <span class="mx-2" aria-hidden="true">/</span>
        <a href="{{ route('blog.category', ['category' => $catKey]) }}" class="text-orange-600 hover:text-orange-700 font-medium">{{ config('blog.categories.'.$catKey.'.breadcrumb') }}</a>
      </nav>
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-03-02">March 2, 2024</time> · 7 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Shift Close Discipline: Cash, Card, and Tip Reconciliation</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        The last hour of service sets the tone for tomorrow’s numbers. A simple close checklist—aligned to what your POS already reports—stops small drifts from becoming trust issues.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Start from the POS report, not the drawer</h2>
        <p>Run the expected cash, card, and tip totals from the system first. Count physical cash second. Investigate any gap before signing off—waiting until morning hides who was on the floor.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Tips and payouts</h2>
        <p>Document tip pool rules and stick to them. When payouts do not match policy, fix the policy or the process—not both quietly.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Handoff notes</h2>
        <p>One line on voids, refunds, or large comps saves the next manager from replaying the whole shift.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'pos-split-payments-refunds-audit-trail']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Split Payments, Refunds, and a Clean Audit Trail</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'mobile-pos-future-restaurant-operations']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Mobile POS: The Future of Restaurant Operations</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
