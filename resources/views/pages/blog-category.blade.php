{{-- resources/views/pages/blog-category.blade.php --}}
@extends('layouts.page')

@section('title', $categoryMeta['label'].' - Dukaantech Blog')
@section('meta')
<meta name="description" content="{{ $categoryMeta['description'] }} — {{ $categoryArticles->count() }} {{ Str::plural('article', $categoryArticles->count()) }}.">
@endsection

@section('page_content')
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-16">
  <div class="mx-auto max-w-7xl px-4">
    <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
      <a href="{{ route('blog') }}" class="text-orange-600 hover:text-orange-700 font-medium">Blog</a>
      <span class="mx-2" aria-hidden="true">/</span>
      <span class="text-gray-900">{{ $categoryMeta['label'] }}</span>
    </nav>
    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">{{ $categoryMeta['label'] }}</h1>
    <p class="text-xl text-gray-600 max-w-3xl">{{ $categoryMeta['description'] }}</p>
    <p class="mt-4 text-sm text-gray-500">{{ $categoryArticles->count() }} {{ Str::plural('article', $categoryArticles->count()) }}</p>
  </div>
</section>

<section class="py-16 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach ($categoryArticles as $article)
        <article class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 flex flex-col">
          <div class="h-40 bg-gradient-to-br {{ $categoryMeta['card_gradient'] ?? 'from-orange-500 to-red-600' }} flex items-center justify-center shrink-0">
            <svg class="w-12 h-12 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <div class="p-6 flex flex-col flex-1">
            <div class="text-sm text-gray-500 mb-2">{{ \Illuminate\Support\Carbon::parse($article['date'])->format('F j, Y') }}</div>
            <h2 class="text-xl font-bold text-gray-900 mb-3">{{ $article['title'] }}</h2>
            <p class="text-gray-600 mb-4 flex-1">{{ $article['excerpt'] }}</p>
            <a href="{{ route('blog.show', ['slug' => $article['slug']]) }}" class="text-orange-600 font-semibold hover:text-orange-700">Read more →</a>
          </div>
        </article>
      @endforeach
    </div>

    <p class="mt-12 text-center">
      <a href="{{ route('blog') }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← Back to blog</a>
    </p>
  </div>
</section>
@endsection
