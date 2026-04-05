{{-- resources/views/components/dashboard-footer.blade.php --}}
<footer class="bg-gray-900 text-white py-16">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid md:grid-cols-4 gap-8 mb-12">
            <div>
                <a href="/" class="inline-block mb-6">
                    <img
                        src="{{ asset('images/logos/dukaantech-pos-logo-footer.png') }}"
                        alt="Dukaantech POS"
                        class="h-12 w-auto max-w-[220px] object-contain object-left"
                        width="220"
                        height="48"
                    />
                </a>
                <p class="text-gray-400 mb-6">Complete restaurant management solution trusted by 10,000+ restaurants across India.</p>
                <div class="flex gap-4">
                    <a
                        href="https://www.facebook.com/dukaantechpos/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-[#1877F2] transition-colors"
                        aria-label="Facebook"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold mb-4">Product</h3>
                <ul class="space-y-3">
                    <li><a href="/product" class="text-gray-400 hover:text-white transition-colors">Product Overview</a></li>
                    <li><a href="/features" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                    <li><a href="/pricing" class="text-gray-400 hover:text-white transition-colors">Pricing</a></li>
                    <li><a href="/integrations" class="text-gray-400 hover:text-white transition-colors">Integrations</a></li>
                    <li><a href="/api-documentation" class="text-gray-400 hover:text-white transition-colors">API Documentation</a></li>
                    <li><a href="/mobile-app" class="text-gray-400 hover:text-white transition-colors">Mobile App</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold mb-4">Company</h3>
                <ul class="space-y-3">
                    <li><a href="/about-us" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                    <li><a href="/careers" class="text-gray-400 hover:text-white transition-colors">Careers</a></li>
                    <li><a href="/blog" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                    <li><a href="/press" class="text-gray-400 hover:text-white transition-colors">Press</a></li>
                    <li><a href="/partners" class="text-gray-400 hover:text-white transition-colors">Partners</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold mb-4">Support</h3>
                <ul class="space-y-3">
                    <li><a href="/help-center" class="text-gray-400 hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="/contact-us" class="text-gray-400 hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="/training" class="text-gray-400 hover:text-white transition-colors">Training</a></li>
                    <li><a href="/system-status" class="text-gray-400 hover:text-white transition-colors">System Status</a></li>
                    <li><a href="/community" class="text-gray-400 hover:text-white transition-colors">Community</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-800 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p class="text-gray-400 text-sm">© {{ date('Y') }} Dukaantech POS. All rights reserved.</p>
                    <p class="text-gray-500 text-xs mt-1">Made with ❤️ for Indian restaurants</p>
                </div>
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex gap-6">
                        <a href="/privacy-policy" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                        <a href="/terms-of-service" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                        <a href="/cookie-policy" class="text-gray-400 hover:text-white text-sm transition-colors">Cookie Policy</a>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Bangalore, India</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
