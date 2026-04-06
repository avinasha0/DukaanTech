{{-- resources/views/pages/blog/inventory-vendor-compliance-receiving.blade.php --}}
@extends('layouts.page')

@section('title', 'Vendor Compliance and Receiving Discipline - Dukaantech Blog')
@section('meta')
<meta name="description" content="Standard checks at the dock stop invoice surprises and keep your POS counts aligned with reality.">
@endsection

@section('page_content')
@php($catKey = 'inventory')
<article class="bg-white" itemscope itemtype="https://schema.org/BlogPosting">
  <section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-12 lg:py-16 border-b border-orange-100/60">
    <div class="mx-auto max-w-3xl px-4">
      <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('blog') }}" class="text-orange-600 hover:text-orange-700 font-medium">Blog</a>
        <span class="mx-2" aria-hidden="true">/</span>
        <a href="{{ route('blog.category', ['category' => $catKey]) }}" class="text-orange-600 hover:text-orange-700 font-medium">{{ config('blog.categories.'.$catKey.'.breadcrumb') }}</a>
      </nav>
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-02-28">February 28, 2024</time> · 7 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Vendor Compliance and Receiving Discipline</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        The invoice is not reality until someone counts cases, checks temps, and notes shorts. A calm receiving ritual keeps your inventory module and your bank account aligned.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">One door, one clipboard</h2>
        <p>Route all deliveries through the same check-in. Photos of damage and short picks save disputes later.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Match PO to invoice</h2>
        <p>Before signing, confirm quantities and prices. If your system supports PO matching, use it—catching price creep early saves hours at month-end.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Credit notes and follow-up</h2>
        <p>Unresolved credits should live on a weekly list with owners. Silence is expensive.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'inventory-waste-tracking-spoilage-comps']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Waste Tracking: Spoilage, Comps, and Training in One View</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'inventory-par-levels-dynamic-reordering']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Par Levels and Dynamic Reordering That Match Real Sales</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
