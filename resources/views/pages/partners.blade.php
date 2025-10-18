{{-- resources/views/pages/partners.blade.php --}}
@extends('layouts.page')

@section('title', 'Partners - Dukaantech POS')
@section('meta')
<meta name="description" content="Join Dukaantech POS partner ecosystem. Integration partners, resellers, and technology partners helping restaurants succeed.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Partner
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Ecosystem
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Join our growing network of partners and help restaurants succeed with integrated solutions
      </p>
    </div>
  </div>
</section>

{{-- Partner Types --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Partner Types</h2>
      <p class="text-xl text-gray-600">Choose the partnership that fits your business</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Integration Partners</h3>
        <p class="text-gray-600 mb-4">Connect your solution with Dukaantech POS through our robust API and integration platform.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Payment gateways</li>
          <li>• Accounting software</li>
          <li>• Food delivery platforms</li>
          <li>• Marketing tools</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Reseller Partners</h3>
        <p class="text-gray-600 mb-4">Sell Dukaantech POS to restaurants in your region and earn attractive commissions.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Competitive commissions</li>
          <li>• Sales training & support</li>
          <li>• Marketing materials</li>
          <li>• Dedicated account manager</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Technology Partners</h3>
        <p class="text-gray-600 mb-4">Collaborate with us to develop innovative solutions for the restaurant industry.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Co-development opportunities</li>
          <li>• Technical collaboration</li>
          <li>• Joint go-to-market</li>
          <li>• Innovation labs</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- Current Partners --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Partners</h2>
      <p class="text-xl text-gray-600">Trusted by leading companies in the industry</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center hover:shadow-lg transition-all">
        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <span class="text-2xl font-bold text-blue-600">R</span>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Razorpay</h3>
        <p class="text-gray-600 text-sm">Payment Gateway Partner</p>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center hover:shadow-lg transition-all">
        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <span class="text-2xl font-bold text-green-600">T</span>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Tally Solutions</h3>
        <p class="text-gray-600 text-sm">Accounting Integration</p>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center hover:shadow-lg transition-all">
        <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <span class="text-2xl font-bold text-orange-600">S</span>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Swiggy</h3>
        <p class="text-gray-600 text-sm">Food Delivery Partner</p>
      </div>
      
      <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center hover:shadow-lg transition-all">
        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <span class="text-2xl font-bold text-purple-600">Z</span>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Zomato</h3>
        <p class="text-gray-600 text-sm">Food Delivery Partner</p>
      </div>
    </div>
  </div>
</section>

{{-- Partner Benefits --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Partner Benefits</h2>
      <p class="text-xl text-gray-600">Why partner with Dukaantech POS?</p>
    </div>
    
    <div class="grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h3 class="text-3xl font-bold text-gray-900 mb-6">Grow Your Business Together</h3>
        <p class="text-lg text-gray-600 mb-8">
          Partner with Dukaantech POS and tap into the growing restaurant technology market. 
          We provide comprehensive support, training, and resources to help you succeed.
        </p>
        
        <div class="space-y-6">
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Competitive Commissions</h4>
              <p class="text-gray-600">Earn attractive commissions on every successful sale</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Dedicated Support</h4>
              <p class="text-gray-600">Get dedicated account management and technical support</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Marketing Resources</h4>
              <p class="text-gray-600">Access to marketing materials, case studies, and co-marketing opportunities</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="relative">
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
          <div class="text-center mb-6">
            <h4 class="text-xl font-bold text-gray-900 mb-2">Partner Portal</h4>
            <p class="text-gray-600">Access your partner resources</p>
          </div>
          
          <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div>
                <div class="font-medium text-gray-900">Sales Dashboard</div>
                <div class="text-sm text-gray-600">Track your performance</div>
              </div>
              <span class="text-sm text-orange-600 font-semibold">Access</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div>
                <div class="font-medium text-gray-900">Training Materials</div>
                <div class="text-sm text-gray-600">Learn about our products</div>
              </div>
              <span class="text-sm text-orange-600 font-semibold">Access</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div>
                <div class="font-medium text-gray-900">Marketing Kit</div>
                <div class="text-sm text-gray-600">Download resources</div>
              </div>
              <span class="text-sm text-orange-600 font-semibold">Access</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Become a Partner --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-4xl px-4">
    <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Become a Partner</h2>
      <p class="text-xl text-gray-600 mb-8">
        Ready to join our partner ecosystem? Get in touch with our partnerships team to learn more about opportunities.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/contact-us" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-lg transition-all">
          Contact Partnerships Team
        </a>
        <a href="mailto:partners@Dukaantechpos.com" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg text-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
          Email Us
        </a>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Partner With Us?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Join our growing network of partners and help restaurants succeed with integrated solutions.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/contact-us" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Become a Partner
      </a>
      <a href="/integrations" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        View Integrations
      </a>
    </div>
  </div>
</section>
@endsection
