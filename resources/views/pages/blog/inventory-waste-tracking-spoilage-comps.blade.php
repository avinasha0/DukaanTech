{{-- resources/views/pages/blog/inventory-waste-tracking-spoilage-comps.blade.php --}}
@extends('layouts.page')

@section('title', 'Waste Tracking: Spoilage, Comps, and Training in One View - Dukaantech Blog')
@section('meta')
<meta name="description" content="Capture why product left the kitchen so you can coach the team and fix vendors or prep.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-01-22">January 22, 2024</time> · 7 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Waste Tracking: Spoilage, Comps, and Training in One View</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        “Shrink” is not one problem—it is spoilage, over-portioning, comps, and training plates. When you tag waste with reasons, patterns surface and fixes become obvious.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Keep reason codes short</h2>
        <p>Spoilage, prep error, line remakes, guest comp, staff meal, training. If you need more than six, roll up monthly and split categories then.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Connect to POS comps</h2>
        <p>Manager comps should mirror kitchen waste logs. Large gaps between comp dollars and logged waste often mean unrecorded spills or unapproved discounts.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Review with the team</h2>
        <p>Share weekly totals without blame. Ask “what changed?” before “who messed up?”</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'inventory-recipe-costing-menu-math']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Recipe Costing and Menu Math That Protect Margin</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'inventory-vendor-compliance-receiving']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Vendor Compliance and Receiving Discipline</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
