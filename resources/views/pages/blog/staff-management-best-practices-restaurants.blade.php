{{-- resources/views/pages/blog/staff-management-best-practices-restaurants.blade.php --}}
@extends('layouts.page')

@section('title', 'Staff Management Best Practices for Restaurants - Dukaantech Blog')
@section('meta')
<meta name="description" content="Hire for fit, train for consistency, and retain with clear roles—practical staff management ideas for busy restaurants.">
@endsection

@section('page_content')
<article class="bg-white" itemscope itemtype="https://schema.org/BlogPosting">
  <section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-12 lg:py-16 border-b border-orange-100/60">
    <div class="mx-auto max-w-3xl px-4">
      <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('blog') }}" class="text-orange-600 hover:text-orange-700 font-medium">Blog</a>
        <span class="mx-2" aria-hidden="true">/</span>
        <a href="{{ route('blog.category', ['category' => 'staff-management']) }}" class="text-orange-600 hover:text-orange-700 font-medium">{{ config('blog.categories.staff-management.breadcrumb') }}</a>
      </nav>
      <p class="text-sm text-gray-500 mb-3"><time datetime="2023-12-10">December 10, 2023</time> · 6 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">
        Staff Management Best Practices for Restaurants
      </h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Great service is a system: clear expectations, fair scheduling, and managers who coach in real time. These practices help you build a team that stays—and performs—when it gets busy.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6 mb-10">
      <p class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">On this page</p>
      <ul class="space-y-2 text-sm">
        <li><a href="#hiring-onboarding" class="text-orange-600 hover:text-orange-700 font-medium">Hiring &amp; onboarding</a></li>
        <li><a href="#roles-standards" class="text-orange-600 hover:text-orange-700 font-medium">Roles &amp; standards</a></li>
        <li><a href="#scheduling-communication" class="text-orange-600 hover:text-orange-700 font-medium">Scheduling &amp; communication</a></li>
        <li><a href="#feedback-retention" class="text-orange-600 hover:text-orange-700 font-medium">Feedback &amp; retention</a></li>
      </ul>
    </div>

    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section id="hiring-onboarding" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Hire for pace and values</h2>
        <p>
          Skills can be taught; reliability and calm under pressure are harder. Use short stage shifts or working interviews when possible. On day one, hand new hires a one-page “how we run service here” and walk the floor so terminology matches what guests hear.
        </p>
      </section>

      <section id="roles-standards" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">One standard for every station</h2>
        <p>
          Define what “done” looks like for sidework, line setup, and cash handling. When everyone uses the same checklists, training is faster and accountability is fair. Tie voids and comps to manager codes so coaching stays factual, not personal.
        </p>
      </section>

      <section id="scheduling-communication" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Publish schedules early, confirm swaps in writing</h2>
        <p>
          Surprise schedules burn trust fast. Give at least a week’s visibility where you can, and use a single channel for shift trades so nothing gets lost. Peak nights need your strongest closers—rotate fairly but protect service quality.
        </p>
      </section>

      <section id="feedback-retention" class="scroll-mt-24 mb-4">
        <h2 class="text-2xl text-gray-900 mb-4">Short feedback loops beat annual reviews</h2>
        <p>
          Five minutes after rush beats a thirty-minute meeting next month. Recognize specifics (“clear communication on table 12”) and address issues with one behavior to change. Small wage bumps tied to skills (wine, expo, trainer) keep people growing without guessing.
        </p>
      </section>
    </div>

    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
        <li><a href="{{ route('blog.show', ['slug' => 'restaurant-sales-data-analytics']) }}" class="text-orange-600 hover:text-orange-700 font-medium">How to increase restaurant sales with data analytics</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'essential-tips-restaurant-inventory-management']) }}" class="text-orange-600 hover:text-orange-700 font-medium">10 essential tips for restaurant inventory management</a></li>
        <li><a href="{{ route('blog.show', ['slug' => 'mobile-pos-future-restaurant-operations']) }}" class="text-orange-600 hover:text-orange-700 font-medium">Mobile POS: the future of restaurant operations</a></li>
      </ul>
      <p class="mt-8">
        <a href="{{ route('blog') }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">← Back to all articles</a>
      </p>
    </div>
  </div>
</article>
@endsection
