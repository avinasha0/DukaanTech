{{-- resources/views/pages/blog/pos-split-payments-refunds-audit-trail.blade.php --}}
@extends('layouts.page')

@section('title', 'Split Payments, Refunds, and a Clean Audit Trail - Dukaantech Blog')
@section('meta')
<meta name="description" content="Handle splits and refunds with roles, reasons, and receipts so every adjustment is explainable later.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-02-18">February 18, 2024</time> · 8 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Split Payments, Refunds, and a Clean Audit Trail</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Guests split checks and change their minds—that is normal. What should not be normal is mystery discounts at month-end. Tie every adjustment to a person, a reason code, and a receipt so finance and the floor share the same story.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Roles and approvals</h2>
        <p>Limit who can comp, void, or refund above a threshold. Managers should authenticate in view of the guest when exceptions happen—transparency beats suspicion.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Reason codes you actually review</h2>
        <p>Use a short list: kitchen error, guest dissatisfaction, training, marketing. Monthly, scan counts by code. If “other” grows, your list is wrong.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Receipts and signatures</h2>
        <p>Card refunds should print or email proof. For cash, note the drawer and shift. Audits become boring—which is the point.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'pos-device-security-updates-backups']) }}" class="text-orange-600 hover:text-orange-700 font-medium">POS Device Hygiene: Security, Updates, and Backups</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'pos-custom-buttons-shortcuts-floor-speed']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Custom Buttons and Shortcuts That Speed Up Floor Service</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
