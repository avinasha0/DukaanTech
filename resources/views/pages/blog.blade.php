{{-- resources/views/pages/blog.blade.php --}}
@extends('layouts.page')

@section('title', 'Blog - Dukaantech POS')
@section('meta')
<meta name="description" content="Read the latest insights, tips, and updates from Dukaantech POS. Restaurant management tips, industry trends, and product updates.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Dukaantech
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Blog
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Insights, tips, and updates to help you run a successful restaurant business
      </p>
    </div>
  </div>
</section>

{{-- Featured Post --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl p-8 text-white">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <div>
          <div class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full text-sm font-medium mb-4">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Featured Article
          </div>
          <h2 class="text-3xl font-bold mb-4">{{ $featuredArticle['title'] }}</h2>
          <p class="text-orange-100 mb-6">
            {{ $featuredArticle['excerpt'] }}
          </p>
          <a href="{{ route('blog.show', ['slug' => $featuredArticle['slug']]) }}" class="bg-white text-orange-600 px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
            Read More
          </a>
        </div>
        <div class="relative">
          <div class="bg-white/10 rounded-xl p-6 backdrop-blur">
            <div class="text-center">
              <div class="w-16 h-16 bg-white/20 rounded-xl mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold mb-2">{{ config('blog.categories.'.$featuredArticle['category'].'.label', 'Featured') }}</h3>
              <p class="text-orange-100 text-sm">{{ config('blog.categories.'.$featuredArticle['category'].'.description', '') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Blog Posts --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Latest Articles</h2>
      <p class="text-xl text-gray-600">Stay updated with the latest insights</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach ($latestArticles as $article)
        @php
          $cat = config('blog.categories')[$article['category']] ?? null;
          $grad = $cat['card_gradient'] ?? 'from-orange-500 to-red-600';
        @endphp
        <article class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
          <div class="h-48 bg-gradient-to-br {{ $grad }} flex items-center justify-center">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <div class="p-6">
            <div class="text-sm text-gray-500 mb-2">{{ \Illuminate\Support\Carbon::parse($article['date'])->format('F j, Y') }}</div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $article['title'] }}</h3>
            <p class="text-gray-600 mb-4">{{ $article['excerpt'] }}</p>
            <a href="{{ route('blog.show', ['slug' => $article['slug']]) }}" class="text-orange-600 font-semibold hover:text-orange-700">Read More →</a>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

{{-- Categories --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Browse by Category</h2>
      <p class="text-xl text-gray-600">Find articles that interest you most</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      @foreach ($blogCategories as $cat)
        <a href="{{ route('blog.category', ['category' => $cat['key']]) }}" class="block bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
          <div class="w-16 h-16 bg-gradient-to-br {{ $cat['card_gradient'] }} rounded-2xl flex items-center justify-center mx-auto mb-6">
            @switch($cat['icon'])
              @case('inventory')
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                @break
              @case('staff')
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @break
              @case('analytics')
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                @break
              @default
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            @endswitch
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $cat['label'] }}</h3>
          <p class="text-gray-600 mb-4">{{ $cat['description'] }}</p>
          <div class="text-sm text-gray-500">{{ $cat['article_count'] }} {{ Str::plural('article', $cat['article_count']) }}</div>
        </a>
      @endforeach
    </div>
  </div>
</section>

{{-- Newsletter --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-4xl px-4">
    <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Stay Updated</h2>
      <p class="text-xl text-gray-600 mb-8">
        Subscribe to our newsletter and get the latest restaurant management tips delivered to your inbox.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
        <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
        <button class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
          Subscribe
        </button>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Transform Your Restaurant?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Put these insights into practice with Dukaantech POS. Get started free today and see the difference—no platform fees in this phase.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Start Free
      </a>
      <a href="/features" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Explore Features
      </a>
    </div>
  </div>
</section>
@endsection
