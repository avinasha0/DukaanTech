{{-- resources/views/pages/blog/restaurant-sales-data-analytics.blade.php --}}
@extends('layouts.page')

@section('title', 'How to Increase Restaurant Sales with Data Analytics - Dukaantech Blog')
@section('meta')
<meta name="description" content="Use sales velocity, menu mix, and shift-level data to tune pricing, promotions, and labor—without drowning in spreadsheets.">
@endsection

@section('page_content')
<article class="bg-white" itemscope itemtype="https://schema.org/BlogPosting">
  <section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-12 lg:py-16 border-b border-orange-100/60">
    <div class="mx-auto max-w-3xl px-4">
      <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('blog') }}" class="text-orange-600 hover:text-orange-700 font-medium">Blog</a>
        <span class="mx-2" aria-hidden="true">/</span>
        <a href="{{ route('blog.category', ['category' => 'analytics']) }}" class="text-orange-600 hover:text-orange-700 font-medium">{{ config('blog.categories.analytics.breadcrumb') }}</a>
      </nav>
      <p class="text-sm text-gray-500 mb-3"><time datetime="2023-12-15">December 15, 2023</time> · 7 min read</p>
      <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4" itemprop="headline">
        How to Increase Restaurant Sales with Data Analytics
      </h1>
      <p class="text-lg text-gray-600 leading-relaxed" itemprop="description">
        Analytics is not about more reports—it is about fewer guesses. When you connect what sells, when it sells, and at what margin, you can lift revenue without guessing on discounts or overstaffing.
      </p>
    </div>
  </section>

  <div class="mx-auto max-w-3xl px-4 py-10 lg:py-14">
    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6 mb-10">
      <p class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">On this page</p>
      <ul class="space-y-2 text-sm">
        <li><a href="#start-with-baselines" class="text-orange-600 hover:text-orange-700 font-medium">Start with baselines</a></li>
        <li><a href="#menu-mix-margin" class="text-orange-600 hover:text-orange-700 font-medium">Menu mix &amp; margin</a></li>
        <li><a href="#time-and-shifts" class="text-orange-600 hover:text-orange-700 font-medium">Time patterns &amp; shifts</a></li>
        <li><a href="#promotions-that-pay" class="text-orange-600 hover:text-orange-700 font-medium">Promotions that pay</a></li>
        <li><a href="#action-checklist" class="text-orange-600 hover:text-orange-700 font-medium">Action checklist</a></li>
      </ul>
    </div>

    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600">
      <section id="start-with-baselines" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Start with baselines you trust</h2>
        <p>
          Pick a small set of metrics and stick to them: average ticket, items per ticket, top categories by revenue and by margin, and void/discount rates. When your POS rolls these up by day and shift, you can spot real change instead of noise.
        </p>
      </section>

      <section id="menu-mix-margin" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Menu mix beats raw sales</h2>
        <p>
          High revenue on a dish that costs too much to produce still hurts the month. Rank items by <strong>contribution margin</strong> and look for “stars” (high volume, solid margin) versus “dogs” (low margin, low volume). Adjust placement, bundles, or prep before you cut a popular item.
        </p>
      </section>

      <section id="time-and-shifts" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Read the clock, not just the total</h2>
        <p>
          Lunch compression, late-night drink lift, and weekend brunch spikes behave differently. Compare the same day last week and the same week last year when seasonality matters. That is how you schedule labor and prep pars without burning food or staff.
        </p>
      </section>

      <section id="promotions-that-pay" class="scroll-mt-24 mb-12">
        <h2 class="text-2xl text-gray-900 mb-4">Design promotions you can measure</h2>
        <p>
          Tie every offer to a code or button in the POS so redemption shows up in reporting. If a promo lifts covers but crushes margin, you will see it in the same week—not after inventory closes.
        </p>
      </section>

      <section id="action-checklist" class="scroll-mt-24 mb-4">
        <h2 class="text-2xl text-gray-900 mb-4">Action checklist</h2>
        <ul class="list-disc pl-6 space-y-2">
          <li>Review ticket size and top categories every Monday.</li>
          <li>Flag items with rising cost % for recipe or price review.</li>
          <li>Match labor to covers by hour using last month’s curves.</li>
          <li>Retire or rework one low-performing menu row per quarter.</li>
        </ul>
      </section>
    </div>

    <div class="mt-12 pt-10 border-t border-gray-200">
      <p class="text-sm font-semibold text-gray-900 mb-3">Related articles</p>
      <ul class="space-y-2">
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
