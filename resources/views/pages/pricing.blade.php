@extends('layouts.app')

@section('title', 'Pricing - Dukaantech POS | Affordable Restaurant Management Solutions')

@section('meta')
<meta name="description" content="Choose the perfect Dukaantech POS plan for your restaurant. Transparent pricing with no hidden fees. Start free or upgrade to Professional/Enterprise plans.">
@endsection

@section('content')
<div class="min-h-[100dvh] bg-white text-gray-900">
  {{-- Header Component --}}
  <x-header />

  {{-- Hero Section --}}
  <section class="relative bg-gradient-to-br from-orange-50 via-white to-red-50 pt-20 pb-16">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
          Simple, Transparent
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
            Pricing
          </span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          Choose the plan that fits your restaurant size. No hidden fees, no surprises. 
          Start free and scale as you grow.
        </p>
      </div>
    </div>
  </section>

  {{-- Pricing Toggle --}}
  <section class="py-8 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
      <div class="flex items-center justify-center mb-8">
        <div class="bg-white rounded-lg p-1 shadow-lg">
          <button id="monthly-btn" class="px-6 py-3 rounded-md font-semibold text-white bg-gradient-to-r from-orange-500 to-red-600">
            Monthly
          </button>
          <button id="yearly-btn" class="px-6 py-3 rounded-md font-semibold text-gray-600 hover:text-gray-900">
            Yearly
            <span class="ml-2 text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full">Save 20%</span>
          </button>
        </div>
      </div>
    </div>
  </section>

  {{-- Pricing Cards --}}
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4">
      <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        {{-- Starter Plan --}}
        <div class="bg-white rounded-2xl p-8 border-2 border-gray-200 hover:border-orange-300 transition-all duration-300 hover:shadow-xl">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
            <p class="text-gray-600 mb-4">Perfect for small cafes & food trucks</p>
            <div class="text-5xl font-bold text-gray-900 mb-2">₹0</div>
            <div class="text-gray-600 pricing-period">per month</div>
            <div class="text-sm text-green-600 font-semibold mt-2">Always Free</div>
          </div>
          
          <ul class="space-y-4 mb-8">
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Basic POS billing</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Up to 2 terminals</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Basic menu management</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Email support</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Basic reports</span>
            </li>
          </ul>
          
          <a href="/register" class="w-full bg-gray-900 text-white py-4 rounded-lg font-semibold text-center block hover:bg-gray-800 transition-colors">
            Start Free
          </a>
        </div>

{{-- Enterprise Plan --}}
        <div class="bg-white rounded-2xl p-8 border-2 border-gray-200 hover:border-purple-300 transition-all duration-300 hover:shadow-xl">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
            <p class="text-gray-600 mb-4">For restaurant chains & franchises</p>
            <div class="text-5xl font-bold text-gray-900 mb-2" data-monthly="₹299" data-yearly="₹2,870">₹299</div>
            <div class="text-gray-600 pricing-period">per month</div>
            <div class="text-sm text-purple-600 font-semibold mt-2 pricing-savings" data-monthly="" data-yearly="(Save ₹718)"></div>
          </div>
          
          <ul class="space-y-4 mb-8">
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Unlimited terminals</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Advanced Analytics</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Advanced Reporting</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">KOT (Kitchen Order Tickets)</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Inventory Management</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">QR Menu</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Dedicated Account Manager</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-700">Email Support</span>
            </li>
          </ul>
          
          <a href="/contact-us" class="w-full bg-gray-900 text-white py-4 rounded-lg font-semibold text-center block hover:bg-gray-800 transition-colors">
            Contact Sales
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- Features Comparison --}}
  <section class="py-20 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Compare All Features</h2>
        <p class="text-xl text-gray-600">See what's included in each plan</p>
      </div>
      
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Features</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Starter</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-purple-600">Enterprise</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4 text-sm text-gray-900">POS Billing</td>
                <td class="px-6 py-4 text-center"><svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></td>
                <td class="px-6 py-4 text-center"><svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></td>
              </tr>
              <tr class="bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">Terminals</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Up to 2</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Unlimited</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-sm text-gray-900">Inventory Management</td>
                <td class="px-6 py-4 text-center"><span class="text-gray-400">-</span></td>
                <td class="px-6 py-4 text-center"><svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></td>
              </tr>
              <tr class="bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">Analytics & Reports</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Basic</td>
                <td class="px-6 py-4 text-center"><svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></td>
              </tr>
              <tr class="bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">Support</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Email</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">Email</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ Section --}}
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-4xl px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
        <p class="text-xl text-gray-600">Everything you need to know about our pricing</p>
      </div>
      
      <div class="space-y-8">
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Can I change my plan anytime?</h3>
          <p class="text-gray-600">Yes! You can upgrade or downgrade your plan at any time. Changes take effect immediately, and we'll prorate any billing differences.</p>
        </div>
        
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Is there a setup fee?</h3>
          <p class="text-gray-600">No setup fees, no hidden costs. What you see is what you pay. We also provide free training and onboarding for all new customers.</p>
        </div>
        
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">What payment methods do you accept?</h3>
          <p class="text-gray-600">We accept all major credit cards, UPI, net banking, and NEFT transfers. Enterprise customers can also pay via invoice.</p>
        </div>
        
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Do you offer refunds?</h3>
          <p class="text-gray-600">We offer a 30-day money-back guarantee. If you're not satisfied with our service, we'll refund your payment in full.</p>
        </div>
        
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Can I try before I buy?</h3>
          <p class="text-gray-600">Absolutely! Start with our free plan and upgrade when you're ready. All paid plans come with a 14-day free trial.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
    <div class="mx-auto max-w-7xl px-4 text-center">
      <h2 class="text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
      <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
        Join thousands of restaurants already using Dukaantech POS. 
        Start your free trial today - no credit card required.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
          Start Free Trial
        </a>
        <a href="/contact-us" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
          Talk to Sales
        </a>
      </div>
    </div>
  </section>

  {{-- Footer Component --}}
  <x-footer />
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const monthlyBtn = document.getElementById('monthly-btn');
  const yearlyBtn = document.getElementById('yearly-btn');
  const pricingElements = document.querySelectorAll('[data-monthly][data-yearly]');
  const periodElements = document.querySelectorAll('.pricing-period');
  const savingsElements = document.querySelectorAll('.pricing-savings');

  function updatePricing(isYearly) {
    // Update button styles
    if (isYearly) {
      monthlyBtn.className = 'px-6 py-3 rounded-md font-semibold text-gray-600 hover:text-gray-900';
      yearlyBtn.className = 'px-6 py-3 rounded-md font-semibold text-white bg-gradient-to-r from-orange-500 to-red-600';
    } else {
      monthlyBtn.className = 'px-6 py-3 rounded-md font-semibold text-white bg-gradient-to-r from-orange-500 to-red-600';
      yearlyBtn.className = 'px-6 py-3 rounded-md font-semibold text-gray-600 hover:text-gray-900';
    }

    // Update pricing values
    pricingElements.forEach(element => {
      const monthlyPrice = element.getAttribute('data-monthly');
      const yearlyPrice = element.getAttribute('data-yearly');
      element.textContent = isYearly ? yearlyPrice : monthlyPrice;
    });

    // Update period text
    periodElements.forEach(element => {
      element.textContent = isYearly ? 'per year' : 'per month';
    });

    // Update savings text
    savingsElements.forEach(element => {
      const monthlySavings = element.getAttribute('data-monthly');
      const yearlySavings = element.getAttribute('data-yearly');
      element.textContent = isYearly ? yearlySavings : monthlySavings;
    });
  }

  // Event listeners
  monthlyBtn.addEventListener('click', () => updatePricing(false));
  yearlyBtn.addEventListener('click', () => updatePricing(true));

  // Initialize with monthly pricing
  updatePricing(false);
});
</script>
@endsection
