{{-- resources/views/pages/privacy-policy.blade.php --}}
@extends('layouts.page')

@section('title', 'Privacy Policy - Dukaantech POS')
@section('meta')
<meta name="description" content="Privacy Policy for Dukaantech POS. Learn how we collect, use, and protect your personal information.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Privacy
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Policy
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Your privacy is important to us. Learn how we collect, use, and protect your information.
      </p>
      <div class="text-sm text-gray-500">
        Last updated: December 20, 2023
      </div>
    </div>
  </div>
</section>

{{-- Privacy Policy Content --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-4xl px-4">
    <div class="prose prose-lg max-w-none">
      <h2 class="text-3xl font-bold text-gray-900 mb-6">1. Information We Collect</h2>
      <p class="text-gray-600 mb-6">
        We collect information you provide directly to us, such as when you create an account, 
        use our services, or contact us for support. This may include:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Personal information (name, email address, phone number)</li>
        <li>• Business information (restaurant name, address, business registration details)</li>
        <li>• Payment information (processed securely through third-party payment processors)</li>
        <li>• Usage data (how you interact with our services)</li>
        <li>• Device information (IP address, browser type, operating system)</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">2. How We Use Your Information</h2>
      <p class="text-gray-600 mb-6">
        We use the information we collect to:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Provide, maintain, and improve our services</li>
        <li>• Process transactions and send related information</li>
        <li>• Send technical notices, updates, and support messages</li>
        <li>• Respond to your comments and questions</li>
        <li>• Monitor and analyze usage and trends</li>
        <li>• Personalize your experience</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">3. Information Sharing</h2>
      <p class="text-gray-600 mb-6">
        We do not sell, trade, or otherwise transfer your personal information to third parties 
        without your consent, except in the following circumstances:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• With service providers who assist us in operating our services</li>
        <li>• When required by law or to protect our rights</li>
        <li>• In connection with a business transfer or acquisition</li>
        <li>• With your explicit consent</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">4. Data Security</h2>
      <p class="text-gray-600 mb-6">
        We implement appropriate security measures to protect your personal information against 
        unauthorized access, alteration, disclosure, or destruction. This includes:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Encryption of data in transit and at rest</li>
        <li>• Regular security assessments and updates</li>
        <li>• Access controls and authentication</li>
        <li>• Secure data centers and infrastructure</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">5. Your Rights</h2>
      <p class="text-gray-600 mb-6">
        You have the right to:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Access and update your personal information</li>
        <li>• Request deletion of your personal information</li>
        <li>• Opt out of certain communications</li>
        <li>• Data portability</li>
        <li>• Withdraw consent where applicable</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">6. Cookies and Tracking</h2>
      <p class="text-gray-600 mb-6">
        We use cookies and similar tracking technologies to enhance your experience, 
        analyze usage patterns, and provide personalized content. You can control 
        cookie settings through your browser preferences.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">7. Data Retention</h2>
      <p class="text-gray-600 mb-6">
        We retain your personal information for as long as necessary to provide our services 
        and fulfill the purposes outlined in this privacy policy, unless a longer retention 
        period is required by law.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">8. Changes to This Policy</h2>
      <p class="text-gray-600 mb-6">
        We may update this privacy policy from time to time. We will notify you of any 
        material changes by posting the new policy on this page and updating the 
        "Last updated" date.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">9. Contact Us</h2>
      <p class="text-gray-600 mb-6">
        If you have any questions about this privacy policy or our data practices, 
        please contact us at:
      </p>
      <div class="bg-gray-50 rounded-lg p-6">
        <p class="text-gray-600 mb-2">
          <strong>Email:</strong> privacy@dukaantech.com
        </p>
        <p class="text-gray-600 mb-2">
          <strong>Address:</strong> Dukaantech Technologies Pvt Ltd<br>
          Level 5, Tower A, Cyber City<br>
          Mumbai, Maharashtra 400001<br>
          India
        </p>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Questions About Privacy?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      We're committed to protecting your privacy. Contact us if you have any questions 
      about our privacy practices.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/contact-us" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Contact Us
      </a>
      <a href="/register" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Start Free Trial
      </a>
    </div>
  </div>
</section>
@endsection
