{{-- resources/views/pages/blog/pos-device-security-updates-backups.blade.php --}}
@extends('layouts.page')

@section('title', 'POS Device Hygiene: Security, Updates, and Backups - Dukaantech Blog')
@section('meta')
<meta name="description" content="Keep terminals patched, roles tight, and recovery plans simple so outages never own your night.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-03-14">March 14, 2024</time> · 6 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">POS Device Hygiene: Security, Updates, and Backups</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Your POS is both a cash register and a network device. Treating it like both—updates, least-privilege logins, and a tested offline plan—keeps Friday night predictable.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Patch on a schedule</h2>
        <p>Pick a low-traffic window weekly for app and OS updates. Document the last successful version so support has context if something regresses.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Accounts and roles</h2>
        <p>Shared “admin” logins are a liability. Issue named accounts, revoke promptly on turnover, and require manager codes for sensitive actions.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">When the internet blinks</h2>
        <p>Know what works offline, for how long, and who approves manual tickets. Rehearse once a quarter—panic is optional.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'pos-custom-buttons-shortcuts-floor-speed']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Custom Buttons and Shortcuts That Speed Up Floor Service</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'pos-shift-close-discipline-cash-card']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Shift Close Discipline: Cash, Card, and Tip Reconciliation</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
