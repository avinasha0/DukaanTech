<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Setup - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
    <script>
        // Auto-generate slug from restaurant name
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('organization_name');
            const slugInput = document.getElementById('organization_slug');
            
            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function() {
                    const slug = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                        .replace(/\s+/g, '-') // Replace spaces with hyphens
                        .replace(/-+/g, '-') // Replace multiple hyphens with single
                        .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
                    
                    slugInput.value = slug;
                });
            }
            
            // Form validation enhancement
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Setting up your organization...
                        `;
                    }
                });
            }
        });
    </script>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-red-50 min-h-screen">
    {{-- Header Component --}}
    <div class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold text-gray-900">Dukaantech</span>
                    <span class="text-sm text-gray-600 ml-1">POS</span>
                </div>
            </a>

            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Step 1 of 2</span>
                    <span class="text-gray-400">•</span>
                    <span>Organization Setup</span>
                </div>
                <div class="w-24 bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-orange-500 to-red-600 h-2 rounded-full" style="width: 50%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="min-h-screen py-12">
        <div class="mx-auto max-w-4xl px-4">
            {{-- Header Section --}}
            <div class="text-center mb-12">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-bold text-gray-900 mb-4 font-dm">Complete Your Setup</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
                    Let's configure your restaurant details to get your POS system up and running in minutes.
                </p>
                
                @if (session('warning'))
                    <div class="max-w-2xl mx-auto mb-8">
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-semibold text-amber-800">Setup Required</h3>
                                    <p class="text-sm text-amber-700 mt-1">{{ session('warning') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="max-w-2xl mx-auto mb-8">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-semibold text-red-800">Setup Error</h3>
                                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="max-w-2xl mx-auto mb-8">
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-semibold text-green-800">Success!</h3>
                                    <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Setup Form --}}
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <form method="POST" action="{{ route('organization.setup') }}" class="space-y-0">
                    @csrf
                    
                    {{-- Organization Section --}}
                    <div class="p-8 border-b border-gray-100">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 font-dm">Restaurant Information</h3>
                                <p class="text-gray-600">Basic details about your business</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="organization_name" class="block text-sm font-semibold text-gray-700 mb-3">
                                    Restaurant Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           id="organization_name" 
                                           name="organization_name" 
                                           value="{{ old('organization_name') }}" 
                                           class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('organization_name') border-red-500 @enderror" 
                                           placeholder="Enter your restaurant name" 
                                           required>
                                </div>
                                @error('organization_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="organization_slug" class="block text-sm font-semibold text-gray-700 mb-3">
                                    Restaurant URL <span class="text-red-500">*</span>
                                </label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 py-4 rounded-l-xl border border-r-0 border-gray-200 bg-gray-50 text-gray-600 text-sm font-medium">
                                        {{ request()->getHost() }}/
                                    </span>
                                    <input type="text" 
                                           id="organization_slug" 
                                           name="organization_slug" 
                                           value="{{ old('organization_slug') }}" 
                                           class="flex-1 px-4 py-4 rounded-r-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('organization_slug') border-red-500 @enderror" 
                                           placeholder="your-restaurant" 
                                           required>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Only lowercase letters, numbers, and hyphens allowed</p>
                                @error('organization_slug')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Outlet Section --}}
                    <div class="p-8 border-b border-gray-100">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 font-dm">Outlet Details</h3>
                                <p class="text-gray-600">Information about your first location</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div>
                                <label for="outlet_name" class="block text-sm font-semibold text-gray-700 mb-3">
                                    Outlet Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           id="outlet_name" 
                                           name="outlet_name" 
                                           value="{{ old('outlet_name') }}" 
                                           class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('outlet_name') border-red-500 @enderror" 
                                           placeholder="Main Branch" 
                                           required>
                                </div>
                                @error('outlet_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="outlet_code" class="block text-sm font-semibold text-gray-700 mb-3">
                                    Outlet Code
                                </label>
                                <input type="text" 
                                       id="outlet_code" 
                                       name="outlet_code" 
                                       value="{{ old('outlet_code') }}" 
                                       class="w-full px-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('outlet_code') border-red-500 @enderror" 
                                       placeholder="MAIN">
                                @error('outlet_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="gstin" class="block text-sm font-semibold text-gray-700 mb-3">
                                    GSTIN Number
                                </label>
                                <input type="text" 
                                       id="gstin" 
                                       name="gstin" 
                                       value="{{ old('gstin') }}" 
                                       class="w-full px-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('gstin') border-red-500 @enderror" 
                                       placeholder="22AAAAA0000A1Z5">
                                @error('gstin')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Address Section --}}
                    <div class="p-8 border-b border-gray-100">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 font-dm">Address Information</h3>
                                <p class="text-gray-600">Complete address for your outlet</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="address" class="block text-sm font-semibold text-gray-700 mb-3">
                                    Street Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           id="address" 
                                           name="address" 
                                           value="{{ old('address') }}" 
                                           class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('address') border-red-500 @enderror" 
                                           placeholder="123 Business Street, Commercial Area" 
                                           required>
                                </div>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <label for="city" class="block text-sm font-semibold text-gray-700 mb-3">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="city" 
                                           name="city" 
                                           value="{{ old('city') }}" 
                                           class="w-full px-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('city') border-red-500 @enderror" 
                                           placeholder="Mumbai" 
                                           required>
                                    @error('city')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="state" class="block text-sm font-semibold text-gray-700 mb-3">
                                        State <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="state" 
                                           name="state" 
                                           value="{{ old('state') }}" 
                                           class="w-full px-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('state') border-red-500 @enderror" 
                                           placeholder="Maharashtra" 
                                           required>
                                    @error('state')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pincode" class="block text-sm font-semibold text-gray-700 mb-3">
                                        Pincode <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="pincode" 
                                           name="pincode" 
                                           value="{{ old('pincode') }}" 
                                           class="w-full px-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('pincode') border-red-500 @enderror" 
                                           placeholder="400001" 
                                           required>
                                    @error('pincode')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="country" class="block text-sm font-semibold text-gray-700 mb-3">
                                        Country <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="country" 
                                           name="country" 
                                           value="{{ old('country', 'India') }}" 
                                           class="w-full px-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('country') border-red-500 @enderror" 
                                           placeholder="India" 
                                           required>
                                    @error('country')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Settings Section --}}
                    <div class="p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 font-dm">Business Settings</h3>
                                <p class="text-gray-600">Configure your business preferences</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="currency" class="block text-sm font-semibold text-gray-700 mb-3">
                                    Default Currency <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </div>
                                    <select id="currency" 
                                            name="currency" 
                                            class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('currency') border-red-500 @enderror">
                                        <option value="INR" {{ old('currency', 'INR') == 'INR' ? 'selected' : '' }}>Indian Rupee (₹)</option>
                                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                        <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>British Pound (£)</option>
                                    </select>
                                </div>
                                @error('currency')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="timezone" class="block text-sm font-semibold text-gray-700 mb-3">
                                    Timezone <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <select id="timezone" 
                                            name="timezone" 
                                            class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-lg @error('timezone') border-red-500 @enderror">
                                        <option value="Asia/Kolkata" {{ old('timezone', 'Asia/Kolkata') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                                        <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC (GMT)</option>
                                        <option value="America/New_York" {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                        <option value="Europe/London" {{ old('timezone') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                        <option value="Asia/Dubai" {{ old('timezone') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                                    </select>
                                </div>
                                @error('timezone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Submit Section --}}
                    <div class="px-8 py-6 bg-gradient-to-r from-orange-50 to-red-50 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                <span class="text-red-500">*</span> Required fields
                            </div>
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-semibold rounded-xl text-white bg-gradient-to-r from-orange-500 to-red-600 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                Complete Setup
                                <svg class="ml-3 -mr-1 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Help Section --}}
            <div class="mt-8 text-center">
                <p class="text-gray-600 mb-4">Need help with setup?</p>
                <div class="flex justify-center gap-6">
                    <a href="#" class="text-orange-600 hover:text-orange-700 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Help Center
                    </a>
                    <a href="#" class="text-orange-600 hover:text-orange-700 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Component --}}
    <x-footer />
</body>
</html>