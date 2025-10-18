{{-- resources/views/pages/cookie-policy.blade.php --}}
@extends('layouts.page')

@section('title', 'Cookie Policy - Dukaantech POS')
@section('meta')
<meta name="description" content="Cookie Policy for Dukaantech POS. Learn about how we use cookies and similar technologies on our website.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Cookie
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Policy
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Learn about how we use cookies and similar technologies to enhance your experience.
      </p>
      <div class="text-sm text-gray-500">
        Last updated: December 20, 2023
      </div>
    </div>
  </div>
</section>

{{-- Cookie Policy Content --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-4xl px-4">
    <div class="prose prose-lg max-w-none">
      <h2 class="text-3xl font-bold text-gray-900 mb-6">What Are Cookies?</h2>
      <p class="text-gray-600 mb-6">
        Cookies are small text files that are stored on your device when you visit our website. 
        They help us provide you with a better experience by remembering your preferences and 
        enabling certain functionality.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">How We Use Cookies</h2>
      <p class="text-gray-600 mb-6">
        Dukaantech POS uses cookies and similar technologies for several purposes:
      </p>

      <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Essential Cookies</h3>
        <p class="text-gray-600 mb-4">
          These cookies are necessary for the website to function properly. They enable basic 
          functions like page navigation, access to secure areas, and remembering your login status.
        </p>
        <ul class="text-gray-600 space-y-2">
          <li>• Session management</li>
          <li>• Security and authentication</li>
          <li>• Load balancing</li>
          <li>• Basic website functionality</li>
        </ul>
      </div>

      <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Analytics Cookies</h3>
        <p class="text-gray-600 mb-4">
          These cookies help us understand how visitors interact with our website by collecting 
          and reporting information anonymously.
        </p>
        <ul class="text-gray-600 space-y-2">
          <li>• Page views and user behavior</li>
          <li>• Website performance metrics</li>
          <li>• Popular content and features</li>
          <li>• Error tracking and debugging</li>
        </ul>
      </div>

      <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Functional Cookies</h3>
        <p class="text-gray-600 mb-4">
          These cookies enable enhanced functionality and personalization, such as remembering 
          your preferences and settings.
        </p>
        <ul class="text-gray-600 space-y-2">
          <li>• Language preferences</li>
          <li>• Theme and display settings</li>
          <li>• Form data and preferences</li>
          <li>• User interface customizations</li>
        </ul>
      </div>

      <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Marketing Cookies</h3>
        <p class="text-gray-600 mb-4">
          These cookies are used to track visitors across websites to display relevant and 
          engaging advertisements.
        </p>
        <ul class="text-gray-600 space-y-2">
          <li>• Ad targeting and personalization</li>
          <li>• Campaign performance tracking</li>
          <li>• Social media integration</li>
          <li>• Remarketing and retargeting</li>
        </ul>
      </div>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">Third-Party Cookies</h2>
      <p class="text-gray-600 mb-6">
        We may also use third-party services that set their own cookies. These include:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Google Analytics for website analytics</li>
        <li>• Social media platforms for sharing and integration</li>
        <li>• Payment processors for secure transactions</li>
        <li>• Customer support tools for live chat functionality</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">Managing Your Cookie Preferences</h2>
      <p class="text-gray-600 mb-6">
        You have several options for managing cookies:
      </p>

      <div class="bg-blue-50 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-blue-900 mb-4">Browser Settings</h3>
        <p class="text-blue-800 mb-4">
          Most web browsers allow you to control cookies through their settings. You can:
        </p>
        <ul class="text-blue-800 space-y-2">
          <li>• Block all cookies</li>
          <li>• Block third-party cookies only</li>
          <li>• Delete existing cookies</li>
          <li>• Set preferences for specific websites</li>
        </ul>
      </div>

      <div class="bg-green-50 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-green-900 mb-4">Cookie Consent Banner</h3>
        <p class="text-green-800 mb-4">
          When you first visit our website, you'll see a cookie consent banner where you can:
        </p>
        <ul class="text-green-800 space-y-2">
          <li>• Accept all cookies</li>
          <li>• Reject non-essential cookies</li>
          <li>• Customize your preferences</li>
          <li>• Learn more about each cookie category</li>
        </ul>
      </div>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">Impact of Disabling Cookies</h2>
      <p class="text-gray-600 mb-6">
        Please note that disabling certain cookies may affect the functionality of our website:
      </p>
      <ul class="text-gray-600 mb-8 space-y-2">
        <li>• Some features may not work properly</li>
        <li>• You may need to re-enter information more frequently</li>
        <li>• Personalized content may not be available</li>
        <li>• Analytics data may be incomplete</li>
      </ul>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">Updates to This Policy</h2>
      <p class="text-gray-600 mb-6">
        We may update this Cookie Policy from time to time to reflect changes in our practices 
        or for other operational, legal, or regulatory reasons. We will notify you of any 
        material changes by posting the updated policy on this page.
      </p>

      <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Us</h2>
      <p class="text-gray-600 mb-6">
        If you have any questions about our use of cookies or this Cookie Policy, 
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
    <h2 class="text-4xl font-bold text-white mb-4">Questions About Cookies?</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      We're transparent about our data practices. Contact us if you have any questions 
      about our cookie usage.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/contact-us" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Contact Us
      </a>
      <a href="/privacy-policy" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Privacy Policy
      </a>
    </div>
  </div>
</section>
@endsection
