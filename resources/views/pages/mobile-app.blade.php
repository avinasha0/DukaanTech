{{-- resources/views/pages/mobile-app.blade.php --}}
@extends('layouts.page')

@section('title', 'Mobile App - Dukaantech POS')
@section('meta')
<meta name="description" content="Download the Dukaantech POS mobile app for iOS and Android. Manage your restaurant on the go with our powerful mobile application.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Manage Your Restaurant
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          On the Go
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Download the Dukaantech POS mobile app and take control of your restaurant from anywhere
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="#" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
          </svg>
          Download for Android
        </a>
        <a href="#" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg text-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all flex items-center justify-center gap-3">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
          </svg>
          Download for iOS
        </a>
      </div>
    </div>
  </div>
</section>

{{-- App Features --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Mobile App Features</h2>
      <p class="text-xl text-gray-600">Everything you need in your pocket</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Mobile POS</h3>
        <p class="text-gray-600 mb-4">Take orders and process payments directly from your mobile device.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Touch-friendly interface</li>
          <li>• Quick order entry</li>
          <li>• Multiple payment modes</li>
          <li>• Receipt printing</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Manager Dashboard</h3>
        <p class="text-gray-600 mb-4">Monitor your restaurant performance with real-time analytics.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Sales analytics</li>
          <li>• Staff performance</li>
          <li>• Inventory alerts</li>
          <li>• Revenue tracking</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5L19.5 4.5M19.5 4.5L19.5 9M19.5 4.5L15 4.5"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Real-time Sync</h3>
        <p class="text-gray-600 mb-4">All data syncs instantly across all your devices and terminals.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Instant synchronization</li>
          <li>• Offline mode support</li>
          <li>• Auto-sync when online</li>
          <li>• Conflict resolution</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5L19.5 4.5M19.5 4.5L19.5 9M19.5 4.5L15 4.5"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Push Notifications</h3>
        <p class="text-gray-600 mb-4">Stay updated with instant notifications for important events.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Order notifications</li>
          <li>• Low stock alerts</li>
          <li>• Payment confirmations</li>
          <li>• System updates</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Offline Mode</h3>
        <p class="text-gray-600 mb-4">Continue working even when internet connection is poor.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Offline order taking</li>
          <li>• Local data storage</li>
          <li>• Auto-sync when online</li>
          <li>• No data loss</li>
        </ul>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Staff Management</h3>
        <p class="text-gray-600 mb-4">Manage your team with mobile staff management tools.</p>
        <ul class="text-sm text-gray-500 space-y-1">
          <li>• Shift scheduling</li>
          <li>• Attendance tracking</li>
          <li>• Performance monitoring</li>
          <li>• Role management</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- App Screenshots --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">See It In Action</h2>
      <p class="text-xl text-gray-600">Beautiful, intuitive interface designed for mobile</p>
    </div>
    
    <div class="grid md:grid-cols-3 gap-8">
      <div class="text-center">
        <div class="bg-white rounded-2xl p-4 shadow-xl mb-6 mx-auto max-w-xs">
          <div class="bg-gray-900 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between mb-4">
              <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
              </div>
              <div class="text-sm">Dukaantech POS</div>
            </div>
            <div class="space-y-3">
              <div class="bg-gray-800 rounded-lg p-3">
                <div class="text-sm text-gray-300">Today's Sales</div>
                <div class="text-xl font-bold">₹45,230</div>
              </div>
              <div class="bg-gray-800 rounded-lg p-3">
                <div class="text-sm text-gray-300">Orders</div>
                <div class="text-xl font-bold">127</div>
              </div>
              <div class="bg-gray-800 rounded-lg p-3">
                <div class="text-sm text-gray-300">Efficiency</div>
                <div class="text-xl font-bold">89%</div>
              </div>
            </div>
          </div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Manager Dashboard</h3>
        <p class="text-gray-600">Monitor performance at a glance</p>
      </div>
      
      <div class="text-center">
        <div class="bg-white rounded-2xl p-4 shadow-xl mb-6 mx-auto max-w-xs">
          <div class="bg-gray-900 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between mb-4">
              <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
              </div>
              <div class="text-sm">POS Terminal</div>
            </div>
            <div class="space-y-3">
              <div class="bg-gray-800 rounded-lg p-3">
                <div class="text-sm text-gray-300">Table 5</div>
                <div class="text-lg font-bold">2 Guests</div>
              </div>
              <div class="space-y-2">
                <div class="bg-gray-800 rounded-lg p-2 flex justify-between">
                  <span class="text-sm">Chicken Biryani</span>
                  <span class="text-sm">₹180</span>
                </div>
                <div class="bg-gray-800 rounded-lg p-2 flex justify-between">
                  <span class="text-sm">Coca Cola</span>
                  <span class="text-sm">₹30</span>
                </div>
              </div>
              <div class="bg-orange-500 rounded-lg p-3 text-center">
                <div class="text-sm">Total: ₹210</div>
              </div>
            </div>
          </div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Mobile POS</h3>
        <p class="text-gray-600">Take orders on the go</p>
      </div>
      
      <div class="text-center">
        <div class="bg-white rounded-2xl p-4 shadow-xl mb-6 mx-auto max-w-xs">
          <div class="bg-gray-900 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between mb-4">
              <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
              </div>
              <div class="text-sm">Inventory</div>
            </div>
            <div class="space-y-3">
              <div class="bg-gray-800 rounded-lg p-3">
                <div class="text-sm text-gray-300">Low Stock Alert</div>
                <div class="text-sm text-red-400">⚠️ Chicken - 5 kg</div>
              </div>
              <div class="space-y-2">
                <div class="bg-gray-800 rounded-lg p-2 flex justify-between">
                  <span class="text-sm">Rice</span>
                  <span class="text-sm text-green-400">25 kg</span>
                </div>
                <div class="bg-gray-800 rounded-lg p-2 flex justify-between">
                  <span class="text-sm">Onions</span>
                  <span class="text-sm text-yellow-400">8 kg</span>
                </div>
                <div class="bg-gray-800 rounded-lg p-2 flex justify-between">
                  <span class="text-sm">Tomatoes</span>
                  <span class="text-sm text-green-400">15 kg</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Inventory Management</h3>
        <p class="text-gray-600">Track stock levels instantly</p>
      </div>
    </div>
  </div>
</section>

{{-- Download Section --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Download Now</h2>
      <p class="text-xl text-gray-600">Available for iOS and Android devices</p>
    </div>
    
    <div class="grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h3 class="text-3xl font-bold text-gray-900 mb-6">Get Started in Minutes</h3>
        <p class="text-lg text-gray-600 mb-8">Download the app, sign in with your Dukaantech POS account, and start managing your restaurant from your mobile device.</p>
        
        <div class="space-y-6">
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Download from App Store</h4>
              <p class="text-gray-600">Available for iPhone and iPad</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Download from Google Play</h4>
              <p class="text-gray-600">Available for Android devices</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Sign in with Existing Account</h4>
              <p class="text-gray-600">Use your Dukaantech POS credentials</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="text-center">
        <div class="grid grid-cols-2 gap-8">
          <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">iOS App</h3>
            <p class="text-gray-600 mb-4">Download from App Store</p>
            <a href="#" class="bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
              Download
            </a>
          </div>
          
          <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Android App</h3>
            <p class="text-gray-600 mb-4">Download from Google Play</p>
            <a href="#" class="bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
              Download
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Take Control Anywhere</h2>
    <p class="text-xl text-orange-100 mb-8 max-w-3xl mx-auto">
      Download the Dukaantech POS mobile app and manage your restaurant from anywhere in the world.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="#" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
        </svg>
        Download for iOS
      </a>
      <a href="#" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all flex items-center justify-center gap-3">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
        </svg>
        Download for Android
      </a>
    </div>
  </div>
</section>
@endsection
