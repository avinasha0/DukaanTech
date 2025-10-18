{{-- resources/views/components/header.blade.php --}}
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
  <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
    <a href="/" class="flex items-center gap-3">
      <img src="/images/logos/dukaantech-logo.png" alt="Dukaantech Logo" class="h-10 w-auto">
    </a>
    
    <nav class="hidden md:flex items-center gap-8">
      <a href="/" class="text-gray-700 hover:text-orange-600 transition-colors">Home</a>
      <a href="/about-us" class="text-gray-700 hover:text-orange-600 transition-colors">About Us</a>
      <a href="/pricing" class="text-gray-700 hover:text-orange-600 transition-colors">Pricing</a>
      <a href="/contact-us" class="text-gray-700 hover:text-orange-600 transition-colors">Contact Us</a>
    </nav>
    
    <div class="flex items-center gap-4">
      <a href="/login" class="text-gray-700 hover:text-orange-600 transition-colors">Login</a>
      <a href="/register" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
        Start Free Trial
      </a>
    </div>
  </div>
</header>
