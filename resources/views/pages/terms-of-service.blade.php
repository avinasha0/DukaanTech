{{-- resources/views/pages/terms-of-service.blade.php --}}
@extends('layouts.page')

@section('title', 'Terms of Service - Dukaantech POS')
@section('meta')
<meta name="description" content="Terms of Service for Dukaantech POS. Read our terms and conditions for using our restaurant management platform.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Terms of
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Service
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Please read these terms carefully before using Dukaantech POS services.
      </p>
      <div class="text-sm text-gray-500">
        Last updated: December 20, 2023
      </div>
    </div>
  </div>
</section>

{{-- Terms Content --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-4xl px-4">
    <div class="prose prose-lg max-w-none">
      <h2 class="text-3xl font-bold text-gray-900 mb-6">1. Acceptance of Terms</h2>
      <p class="text-gray-600 mb-6">
        By accessing or using Dukaantech POS services, you agree to be bound by these 
        Terms of Service and all applicable laws and regulations. If you do not agree 
        with any of these terms, you are prohibited from using our services.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">2. Description of Service</h2>
      <p class="text-gray-600 mb-6">
        Dukaantech POS provides a comprehensive restaurant management platform including 
        point-of-sale, inventory management, staff management, reporting, and related services. 
        We reserve the right to modify, suspend, or discontinue any aspect of our services 
        at any time.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">3. User Accounts</h2>
      <p class="text-gray-600 mb-6">
        To access certain features of our service, you must create an account. You are 
        responsible for:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Providing accurate and complete information</li>
        <li>• Maintaining the security of your account credentials</li>
        <li>• All activities that occur under your account</li>
        <li>• Notifying us immediately of any unauthorized use</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">4. Acceptable Use</h2>
      <p class="text-gray-600 mb-6">
        You agree not to use our services to:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Violate any applicable laws or regulations</li>
        <li>• Infringe on intellectual property rights</li>
        <li>• Transmit harmful or malicious code</li>
        <li>• Attempt to gain unauthorized access to our systems</li>
        <li>• Interfere with the proper functioning of our services</li>
        <li>• Use our services for any illegal or unauthorized purpose</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">5. Payment Terms</h2>
      <p class="text-gray-600 mb-6">
        Our services are provided on a subscription basis. Payment terms include:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Subscription fees are billed in advance</li>
        <li>• All fees are non-refundable unless otherwise specified</li>
        <li>• We may change pricing with 30 days' notice</li>
        <li>• Failure to pay may result in service suspension</li>
        <li>• You are responsible for all applicable taxes</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">6. Data and Privacy</h2>
      <p class="text-gray-600 mb-6">
        Your use of our services is also governed by our Privacy Policy. You retain 
        ownership of your data, and we will not use your data except as necessary 
        to provide our services and as described in our Privacy Policy.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">7. Intellectual Property</h2>
      <p class="text-gray-600 mb-6">
        The Dukaantech POS platform, including its design, functionality, and content, 
        is protected by intellectual property laws. You may not:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Copy, modify, or distribute our software</li>
        <li>• Reverse engineer or attempt to extract source code</li>
        <li>• Remove or alter any proprietary notices</li>
        <li>• Use our trademarks without permission</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">8. Service Availability</h2>
      <p class="text-gray-600 mb-6">
        While we strive to maintain high service availability, we do not guarantee 
        uninterrupted access. We may perform maintenance, updates, or modifications 
        that temporarily affect service availability.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">9. Limitation of Liability</h2>
      <p class="text-gray-600 mb-6">
        To the maximum extent permitted by law, Dukaantech POS shall not be liable 
        for any indirect, incidental, special, consequential, or punitive damages, 
        including but not limited to loss of profits, data, or business opportunities.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">10. Termination</h2>
      <p class="text-gray-600 mb-6">
        Either party may terminate this agreement at any time. Upon termination:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Your access to our services will be suspended</li>
        <li>• You may export your data within 30 days</li>
        <li>• We may delete your data after the export period</li>
        <li>• Certain provisions will survive termination</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">11. Governing Law</h2>
      <p class="text-gray-600 mb-6">
        These terms are governed by the laws of India. Any disputes arising from 
        these terms or your use of our services will be subject to the exclusive 
        jurisdiction of the courts in Mumbai, Maharashtra.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">12. Changes to Terms</h2>
      <p class="text-gray-600 mb-6">
        We reserve the right to modify these terms at any time. We will notify you 
        of material changes via email or through our service. Your continued use 
        of our services constitutes acceptance of the modified terms.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">13. Contact Information</h2>
      <p class="text-gray-600 mb-6">
        If you have any questions about these Terms of Service, please contact us at:
      </p>
      <div class="bg-gray-50 rounded-lg p-6">
        <p class="text-gray-600 mb-2">
          <strong>Email:</strong> legal@dukaantech.com
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
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Join thousands of restaurants already using Dukaantech POS to streamline their operations.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Start Free Trial
      </a>
      <a href="/contact-us" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Contact Sales
      </a>
    </div>
  </div>
</section>
@endsection
