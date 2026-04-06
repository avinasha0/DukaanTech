{{-- resources/views/pages/blog/inventory-recipe-costing-menu-math.blade.php --}}
@extends('layouts.page')

@section('title', 'Recipe Costing and Menu Math That Protect Margin - Dukaantech Blog')
@section('meta')
<meta name="description" content="Tie every plate to ingredients and yield so price changes and specials stay profitable.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-02-04">February 4, 2024</time> · 8 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Recipe Costing and Menu Math That Protect Margin</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Menu price is not just vibes—it is yield, trim, oil life, and garnish. Build recipes in your system so theoretical food cost stays visible when commodity prices jump.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Build from weights, not cups</h2>
        <p>Grams scale; eyeballs drift. Update yields when you change suppliers—fat content and trim change the math.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Target margin by category</h2>
        <p>Starters and drinks often subsidize proteins. Know your blended target and mark items that drift for repricing or portion tweaks.</p>
      </section>
      <section class="mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Specials and LTOs</h2>
        <p>Cost specials before service, not after Instagram. If the plate cannot clear the bar, adjust the feature or the price before tickets print.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'essential-tips-restaurant-inventory-management']) }}" class="text-orange-600 hover:text-orange-700 font-medium">10 Essential Tips for Restaurant Inventory Management</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'inventory-par-levels-dynamic-reordering']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Par Levels and Dynamic Reordering That Match Real Sales</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
