{{-- resources/views/pages/blog/pos-custom-buttons-shortcuts-floor-speed.blade.php --}}
@extends('layouts.page')

@section('title', 'Custom Buttons and Shortcuts That Speed Up Floor Service - Dukaantech Blog')
@section('meta')
<meta name="description" content="Design your POS layout so common actions take one tap—fewer errors during rush and faster table turns.">
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
      <p class="text-sm text-gray-500 mb-3"><time datetime="2024-02-10">February 10, 2024</time> · 7 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">Custom Buttons and Shortcuts That Speed Up Floor Service</h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        During peak hours, every tap matters. A layout built around your actual menu—not the default grid—cuts path length for modifiers, common comps, and payment flows so servers stay in the room instead of fighting the terminal.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section id="map-real-flows" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Map buttons to real service flows</h2>
        <p>Watch three busy shifts and list the top twenty actions your team repeats—split check, add guest, repeat last item, common modifiers. Promote those to primary tiles or a single “quick” layer. Depress or hide rarely used items so fat-finger mistakes drop.</p>
      </section>
      <section id="shortcuts-training" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Shortcuts only work with muscle memory</h2>
        <p>Document a one-page cheat sheet and drill it in pre-shift for one week. Pair veterans with new hires until shortcuts feel automatic. If only one person knows the layout, you still have a bottleneck.</p>
      </section>
      <section id="iterate" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Review quarterly</h2>
        <p>Menus change; so should your POS. After each menu update, revisit button placement with the team that rings tickets every night—not only management.</p>
      </section>
    </div>
    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'mobile-pos-future-restaurant-operations']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Mobile POS: The Future of Restaurant Operations</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'pos-shift-close-discipline-cash-card']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Shift Close Discipline: Cash, Card, and Tip Reconciliation</a></li>
      </ul>
      <p class="mt-8"><a href="{{ route('blog.category', ['category' => $catKey]) }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← More in {{ config('blog.categories.'.$catKey.'.label') }}</a></p>
    </div>
  </div>
</article>
@endsection
