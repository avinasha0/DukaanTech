{{-- resources/views/components/header.blade.php --}}
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
  <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
    <a href="/" class="flex items-center gap-3">
      <img src="/images/logos/dukaantech-logo.png" alt="Dukaantech Logo" class="h-10 w-auto">
    </a>
    
    {{-- Desktop Navigation --}}
    <nav class="hidden md:flex items-center gap-8">
      <a href="/" class="text-gray-700 hover:text-orange-600 transition-colors">Home</a>
      <a href="/about-us" class="text-gray-700 hover:text-orange-600 transition-colors">About Us</a>
      <a href="/pricing" class="text-gray-700 hover:text-orange-600 transition-colors">Pricing</a>
      <a href="/contact-us" class="text-gray-700 hover:text-orange-600 transition-colors">Contact Us</a>
    </nav>
    
    {{-- Desktop Auth Buttons --}}
    <div class="hidden md:flex items-center gap-4">
      <a href="/login" class="text-gray-700 hover:text-orange-600 transition-colors">Login</a>
      <a href="/register" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
        Start Free Trial
      </a>
    </div>

    {{-- Mobile Menu Button --}}
    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500">
      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  {{-- Mobile Menu --}}
  <div x-show="mobileMenuOpen" 
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 scale-95"
       x-transition:enter-end="opacity-100 scale-100"
       x-transition:leave="transition ease-in duration-75"
       x-transition:leave-start="opacity-100 scale-100"
       x-transition:leave-end="opacity-0 scale-95"
       class="md:hidden">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-200">
      <a href="/" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-gray-50 rounded-md transition-colors">Home</a>
      <a href="/about-us" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-gray-50 rounded-md transition-colors">About Us</a>
      <a href="/pricing" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-gray-50 rounded-md transition-colors">Pricing</a>
      <a href="/contact-us" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-gray-50 rounded-md transition-colors">Contact Us</a>
      
      {{-- Mobile Auth Buttons --}}
      <div class="pt-4 pb-3 border-t border-gray-200">
        <div class="px-3 space-y-2">
          <a href="/login" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-gray-50 rounded-md transition-colors">Login</a>
          <a href="/register" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-md hover:shadow-lg transition-all text-center">
            Start Free Trial
          </a>
        </div>
      </div>
    </div>
  </div>
</header>
