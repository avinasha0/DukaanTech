{{-- resources/views/pages/help-center.blade.php --}}
@extends('layouts.page')

@section('title', 'Help Center - Dukaantech POS')
@section('meta')
<meta name="description" content="Complete guide to Dukaantech POS. Learn all features, step-by-step tutorials, and get expert support for your restaurant management.">
@endsection

@section('page_content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-orange-50 via-white to-red-50 py-20">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
        Complete
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
          Help Center
        </span>
      </h1>
      <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
        Master Dukaantech POS with comprehensive tutorials, step-by-step guides, and expert support for every feature
      </p>
      <div class="max-w-2xl mx-auto">
        <div class="relative">
          <input type="text" placeholder="Search tutorials, features, or help topics..." class="w-full px-6 py-4 border border-gray-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
          <button class="absolute right-2 top-2 bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transition-all">
            Search
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Getting Started Section --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Getting Started</h2>
      <p class="text-xl text-gray-600">Complete beginner's guide to Dukaantech POS</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">1. Account Setup</h3>
        <p class="text-gray-600 mb-4">Create your restaurant account and configure basic settings</p>
        <div class="text-sm text-gray-500 space-y-2">
          <div class="font-semibold">Step-by-step:</div>
          <div>• Register at dukaantech.com</div>
          <div>• Verify your email address</div>
          <div>• Complete restaurant profile</div>
          <div>• Add your first outlet</div>
          <div>• Configure business hours</div>
        </div>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">2. Menu Setup</h3>
        <p class="text-gray-600 mb-4">Add your restaurant's menu items and categories</p>
        <div class="text-sm text-gray-500 space-y-2">
          <div class="font-semibold">Step-by-step:</div>
          <div>• Create food categories</div>
          <div>• Add menu items with prices</div>
          <div>• Set up item variants (size, type)</div>
          <div>• Configure modifiers (extras)</div>
          <div>• Upload item images</div>
        </div>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">3. First Order</h3>
        <p class="text-gray-600 mb-4">Process your first customer order</p>
        <div class="text-sm text-gray-500 space-y-2">
          <div class="font-semibold">Step-by-step:</div>
          <div>• Open a new shift</div>
          <div>• Create a new order</div>
          <div>• Add items to cart</div>
          <div>• Process payment</div>
          <div>• Generate receipt</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- POS Operations Tutorial --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">POS Operations Tutorial</h2>
      <p class="text-xl text-gray-600">Master the core POS functions step by step</p>
    </div>
    
    <div class="grid lg:grid-cols-2 gap-12">
      {{-- Order Management --}}
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center mb-6">
          <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">Order Management</h3>
        </div>
        
        <div class="space-y-6">
          <div class="border-l-4 border-orange-500 pl-4">
            <h4 class="font-semibold text-gray-900 mb-2">Creating a New Order</h4>
            <div class="text-sm text-gray-600 space-y-1">
              <div>1. Click "New Order" button</div>
              <div>2. Select order type (Dine-in, Takeaway, Delivery)</div>
              <div>3. Choose table number (for dine-in)</div>
              <div>4. Add customer details (optional)</div>
              <div>5. Start adding menu items</div>
            </div>
          </div>
          
          <div class="border-l-4 border-green-500 pl-4">
            <h4 class="font-semibold text-gray-900 mb-2">Adding Items to Order</h4>
            <div class="text-sm text-gray-600 space-y-1">
              <div>1. Browse categories or search items</div>
              <div>2. Click on item to add to cart</div>
              <div>3. Select variants (size, type) if available</div>
              <div>4. Add modifiers (extras, special requests)</div>
              <div>5. Adjust quantity if needed</div>
            </div>
          </div>
          
          <div class="border-l-4 border-blue-500 pl-4">
            <h4 class="font-semibold text-gray-900 mb-2">Order States</h4>
            <div class="text-sm text-gray-600 space-y-1">
              <div>• <strong>NEW:</strong> Order just created</div>
              <div>• <strong>IN_KITCHEN:</strong> KOT sent to kitchen</div>
              <div>• <strong>SERVED:</strong> Food served to customer</div>
              <div>• <strong>BILLED:</strong> Bill generated</div>
            </div>
          </div>
        </div>
      </div>
      
      {{-- Payment Processing --}}
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center mb-6">
          <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">Payment Processing</h3>
        </div>
        
        <div class="space-y-6">
          <div class="border-l-4 border-orange-500 pl-4">
            <h4 class="font-semibold text-gray-900 mb-2">Generating Bill</h4>
            <div class="text-sm text-gray-600 space-y-1">
              <div>1. Ensure order is in "SERVED" state</div>
              <div>2. Click "Generate Bill"</div>
              <div>3. Review order total and taxes</div>
              <div>4. Apply discounts if needed</div>
              <div>5. Bill is created in "OPEN" state</div>
            </div>
          </div>
          
          <div class="border-l-4 border-green-500 pl-4">
            <h4 class="font-semibold text-gray-900 mb-2">Payment Methods</h4>
            <div class="text-sm text-gray-600 space-y-1">
              <div>• <strong>Cash:</strong> Enter amount received</div>
              <div>• <strong>Card:</strong> Process card payment</div>
              <div>• <strong>UPI:</strong> Scan QR or enter UPI ID</div>
              <div>• <strong>Wallet:</strong> Digital wallet payment</div>
              <div>• <strong>Other:</strong> Custom payment method</div>
            </div>
          </div>
          
          <div class="border-l-4 border-blue-500 pl-4">
            <h4 class="font-semibold text-gray-900 mb-2">Split Payments</h4>
            <div class="text-sm text-gray-600 space-y-1">
              <div>1. Select "Split Payment" option</div>
              <div>2. Choose payment methods</div>
              <div>3. Enter amounts for each method</div>
              <div>4. Confirm total matches bill amount</div>
              <div>5. Process all payments</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Inventory Management Tutorial --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Inventory Management</h2>
      <p class="text-xl text-gray-600">Complete guide to managing your restaurant's inventory</p>
    </div>
    
    <div class="grid lg:grid-cols-3 gap-8">
      {{-- Categories --}}
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Categories</h3>
        <div class="text-sm text-gray-600 space-y-2">
          <div class="font-semibold">Creating Categories:</div>
          <div>1. Go to Menu → Categories</div>
          <div>2. Click "Add Category"</div>
          <div>3. Enter category name</div>
          <div>4. Add description (optional)</div>
          <div>5. Upload category image</div>
          <div>6. Set display order</div>
          <div>7. Save category</div>
        </div>
      </div>
      
      {{-- Menu Items --}}
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Menu Items</h3>
        <div class="text-sm text-gray-600 space-y-2">
          <div class="font-semibold">Adding Items:</div>
          <div>1. Go to Menu → Items</div>
          <div>2. Click "Add Item"</div>
          <div>3. Select category</div>
          <div>4. Enter item name & description</div>
          <div>5. Set base price</div>
          <div>6. Mark as Veg/Non-Veg</div>
          <div>7. Upload item photo</div>
          <div>8. Set availability</div>
        </div>
      </div>
      
      {{-- Modifiers --}}
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Modifiers & Variants</h3>
        <div class="text-sm text-gray-600 space-y-2">
          <div class="font-semibold">Setting Up Modifiers:</div>
          <div>1. Go to Menu → Modifiers</div>
          <div>2. Create modifier groups</div>
          <div>3. Add individual modifiers</div>
          <div>4. Set prices for each modifier</div>
          <div>5. Assign to menu items</div>
          <div>6. Set minimum/maximum selection</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Shift Management Tutorial --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Shift Management</h2>
      <p class="text-xl text-gray-600">Learn how to manage daily shifts and track sales</p>
    </div>
    
    <div class="grid lg:grid-cols-2 gap-12">
      {{-- Opening Shift --}}
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center mb-6">
          <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">Opening a Shift</h3>
        </div>
        
        <div class="space-y-4">
          <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <h4 class="font-semibold text-green-900 mb-2">Step-by-step Process:</h4>
            <div class="text-sm text-green-800 space-y-1">
              <div>1. Login to your POS system</div>
              <div>2. Select your outlet</div>
              <div>3. Click "Open Shift"</div>
              <div>4. Enter opening cash amount</div>
              <div>5. Confirm shift opening</div>
              <div>6. Start taking orders</div>
            </div>
          </div>
          
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-semibold text-blue-900 mb-2">Important Notes:</h4>
            <div class="text-sm text-blue-800 space-y-1">
              <div>• Only one shift can be open per outlet</div>
              <div>• Opening cash is tracked for reconciliation</div>
              <div>• All orders are linked to the current shift</div>
              <div>• Shift data is used for daily reports</div>
            </div>
          </div>
        </div>
      </div>
      
      {{-- Closing Shift --}}
      <div class="bg-white rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center mb-6">
          <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">Closing a Shift</h3>
        </div>
        
        <div class="space-y-4">
          <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <h4 class="font-semibold text-red-900 mb-2">Step-by-step Process:</h4>
            <div class="text-sm text-red-800 space-y-1">
              <div>1. Complete all pending orders</div>
              <div>2. Count actual cash in drawer</div>
              <div>3. Click "Close Shift"</div>
              <div>4. Enter actual cash amount</div>
              <div>5. Review shift summary</div>
              <div>6. Confirm shift closure</div>
            </div>
          </div>
          
          <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h4 class="font-semibold text-yellow-900 mb-2">Cash Reconciliation:</h4>
            <div class="text-sm text-yellow-800 space-y-1">
              <div>• System calculates expected cash</div>
              <div>• Compare with actual cash counted</div>
              <div>• Variance is automatically calculated</div>
              <div>• All transactions are recorded</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Kitchen Operations (KOT) --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Kitchen Operations (KOT)</h2>
      <p class="text-xl text-gray-600">Streamline your kitchen workflow with Kitchen Order Tickets</p>
    </div>
    
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl p-8 text-white">
      <div class="grid lg:grid-cols-2 gap-8">
        <div>
          <h3 class="text-2xl font-bold mb-4">What is KOT?</h3>
          <p class="text-orange-100 mb-6">
            Kitchen Order Ticket (KOT) is a system that automatically sends order details to your kitchen staff, 
            ensuring accurate and timely food preparation.
          </p>
          
          <div class="space-y-4">
            <div class="flex items-start">
              <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center mr-3 mt-1">
                <span class="text-orange-600 text-sm font-bold">1</span>
              </div>
              <div>
                <h4 class="font-semibold">Order Created</h4>
                <p class="text-sm text-orange-100">Customer places order at POS</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center mr-3 mt-1">
                <span class="text-orange-600 text-sm font-bold">2</span>
              </div>
              <div>
                <h4 class="font-semibold">KOT Generated</h4>
                <p class="text-sm text-orange-100">System creates kitchen ticket</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center mr-3 mt-1">
                <span class="text-orange-600 text-sm font-bold">3</span>
              </div>
              <div>
                <h4 class="font-semibold">Kitchen Receives</h4>
                <p class="text-sm text-orange-100">Printed ticket sent to kitchen</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center mr-3 mt-1">
                <span class="text-orange-600 text-sm font-bold">4</span>
              </div>
              <div>
                <h4 class="font-semibold">Food Prepared</h4>
                <p class="text-sm text-orange-100">Kitchen staff prepares order</p>
              </div>
            </div>
          </div>
        </div>
        
        <div>
          <h3 class="text-2xl font-bold mb-4">KOT Features</h3>
          <div class="space-y-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
              <h4 class="font-semibold mb-2">Automatic Printing</h4>
              <p class="text-sm text-orange-100">KOTs are automatically printed when orders are confirmed</p>
            </div>
            
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
              <h4 class="font-semibold mb-2">Station Routing</h4>
              <p class="text-sm text-orange-100">Route orders to specific kitchen stations</p>
            </div>
            
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
              <h4 class="font-semibold mb-2">Order Tracking</h4>
              <p class="text-sm text-orange-100">Track order status from kitchen to service</p>
            </div>
            
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
              <h4 class="font-semibold mb-2">Custom Templates</h4>
              <p class="text-sm text-orange-100">Customize KOT layout and information</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Reports & Analytics --}}
<section class="py-20 bg-gray-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Reports & Analytics</h2>
      <p class="text-xl text-gray-600">Understand your business performance with detailed reports</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Sales Reports</h3>
        <div class="text-sm text-gray-600 space-y-2">
          <div class="font-semibold">Available Reports:</div>
          <div>• Daily sales summary</div>
          <div>• Hourly sales breakdown</div>
          <div>• Payment method analysis</div>
          <div>• Tax collection report</div>
          <div>• Discount analysis</div>
          <div>• Export to PDF/Excel</div>
        </div>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Top Selling Items</h3>
        <div class="text-sm text-gray-600 space-y-2">
          <div class="font-semibold">Item Analysis:</div>
          <div>• Best selling products</div>
          <div>• Revenue per item</div>
          <div>• Quantity sold</div>
          <div>• Category performance</div>
          <div>• Seasonal trends</div>
          <div>• Profit margins</div>
        </div>
      </div>
      
      <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all duration-300">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Shift Reports</h3>
        <div class="text-sm text-gray-600 space-y-2">
          <div class="font-semibold">Shift Analysis:</div>
          <div>• Shift performance</div>
          <div>• Cash reconciliation</div>
          <div>• Staff productivity</div>
          <div>• Order count per shift</div>
          <div>• Average order value</div>
          <div>• Variance tracking</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Troubleshooting Section --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Troubleshooting</h2>
      <p class="text-xl text-gray-600">Common issues and their solutions</p>
    </div>
    
    <div class="grid lg:grid-cols-2 gap-12">
      {{-- Common Issues --}}
      <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-3">Printer Not Working</h3>
          <div class="text-sm text-gray-600 space-y-2">
            <div class="font-semibold">Solutions:</div>
            <div>• Check printer connection</div>
            <div>• Verify printer settings</div>
            <div>• Restart printer service</div>
            <div>• Check paper and ink levels</div>
            <div>• Test print function</div>
          </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-3">Payment Processing Error</h3>
          <div class="text-sm text-gray-600 space-y-2">
            <div class="font-semibold">Solutions:</div>
            <div>• Check internet connection</div>
            <div>• Verify payment gateway settings</div>
            <div>• Try different payment method</div>
            <div>• Contact payment provider</div>
            <div>• Process as cash payment</div>
          </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-3">Slow Performance</h3>
          <div class="text-sm text-gray-600 space-y-2">
            <div class="font-semibold">Solutions:</div>
            <div>• Clear browser cache</div>
            <div>• Close unnecessary tabs</div>
            <div>• Check internet speed</div>
            <div>• Restart the application</div>
            <div>• Contact support if persistent</div>
          </div>
        </div>
      </div>
      
      {{-- FAQ --}}
      <div class="space-y-6">
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-3">Frequently Asked Questions</h3>
          
          <div class="space-y-4">
            <div>
              <h4 class="font-semibold text-gray-900 mb-2">Can I use Dukaantech POS offline?</h4>
              <p class="text-sm text-gray-600">Yes! Dukaantech POS works offline. You can continue taking orders even without internet connection. All data will sync automatically when you're back online.</p>
            </div>
            
            <div>
              <h4 class="font-semibold text-gray-900 mb-2">How do I backup my data?</h4>
              <p class="text-sm text-gray-600">Your data is automatically backed up to secure cloud servers. You can also export reports and data manually from the Reports section.</p>
            </div>
            
            <div>
              <h4 class="font-semibold text-gray-900 mb-2">Can I customize the receipt format?</h4>
              <p class="text-sm text-gray-600">Yes! You can customize receipt templates, add your logo, change fonts, and modify the information displayed on receipts.</p>
            </div>
            
            <div>
              <h4 class="font-semibold text-gray-900 mb-2">How do I add multiple outlets?</h4>
              <p class="text-sm text-gray-600">Go to Settings → Outlets → Add Outlet. Each outlet can have its own menu, pricing, and settings while sharing the same account.</p>
            </div>
            
            <div>
              <h4 class="font-semibold text-gray-900 mb-2">Is my data secure?</h4>
              <p class="text-sm text-gray-600">Absolutely. We use bank-grade encryption and secure cloud infrastructure to protect your data. All transactions are encrypted and we never share your data with third parties.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Contact Support --}}
<section class="py-20 bg-gradient-to-r from-orange-500 to-red-600">
  <div class="mx-auto max-w-4xl px-4 text-center">
    <h2 class="text-4xl font-bold text-white mb-4">Need More Help?</h2>
    <p class="text-xl text-orange-100 mb-8">
      Our expert support team is available 24/7 to help you succeed with Dukaantech POS
    </p>
    
    <div class="grid md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white bg-opacity-20 rounded-2xl p-6">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mx-auto mb-4">
          <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Phone Support</h3>
        <p class="text-orange-100 text-sm">Call us for immediate assistance</p>
        <p class="text-white font-semibold">+91 98765 43210</p>
      </div>
      
      <div class="bg-white bg-opacity-20 rounded-2xl p-6">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mx-auto mb-4">
          <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Email Support</h3>
        <p class="text-orange-100 text-sm">Send us detailed queries</p>
        <p class="text-white font-semibold">support@dukaantech.com</p>
      </div>
      
      <div class="bg-white bg-opacity-20 rounded-2xl p-6">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mx-auto mb-4">
          <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Live Chat</h3>
        <p class="text-orange-100 text-sm">Chat with our support team</p>
        <p class="text-white font-semibold">Available 24/7</p>
      </div>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/contact-us" class="bg-white text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Contact Support
      </a>
      <a href="/training" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
        Schedule Training
      </a>
    </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <h2 class="text-4xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
    <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
      Start your free trial today and experience the power of Dukaantech POS with comprehensive support and training.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition-all transform hover:-translate-y-1">
        Start Free Trial
      </a>
      <a href="/contact-us" class="border-2 border-orange-500 text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-orange-500 hover:text-white transition-all">
        Contact Sales
      </a>
    </div>
  </div>
</section>
@endsection
