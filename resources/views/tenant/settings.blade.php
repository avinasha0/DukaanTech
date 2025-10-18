@extends('layouts.tenant')

@section('title', 'Settings')

@section('content')
                    <div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
                                        <div>
                <h1 class="text-2xl font-bold text-gray-900 font-dm">Settings</h1>
                <p class="text-gray-600 mt-2">Configure your restaurant settings and preferences</p>
                                        </div>
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Settings Tabs --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('general')" id="general-tab" class="tab-button active py-4 px-1 border-b-2 border-purple-500 font-medium text-sm text-purple-600">
                    General Settings
                </button>
                <button onclick="showTab('outlets')" id="outlets-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Outlets
                </button>
                <button onclick="showTab('billing')" id="billing-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Billing
                </button>
                <button onclick="showTab('notifications')" id="notifications-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Notifications
                </button>
            </nav>
        </div>

        {{-- General Settings Tab --}}
        <div id="general-content" class="tab-content p-6">
            <form id="general-settings-form" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Basic Information --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Restaurant Name</label>
                            <input type="text" id="name" name="name" value="{{ $tenant->name ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">URL Slug</label>
                            <input type="text" id="slug" name="slug" value="{{ $tenant->slug ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed" 
                                   readonly disabled>
                            <p class="text-xs text-gray-500 mt-1">Used in your restaurant's URL: {{ url('/') }}/<span id="slug-preview">{{ $tenant->slug ?? '' }}</span></p>
                            <p class="text-xs text-gray-600 mt-1">
                                🔒 URL slug cannot be changed after setup
                            </p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ $tenant->description ?? '' }}</textarea>
                        </div>

                        <div>
                            <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                            <select id="industry" name="industry" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Select Industry</option>
                                <option value="restaurant" {{ ($tenant->industry ?? '') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                                <option value="cafe" {{ ($tenant->industry ?? '') == 'cafe' ? 'selected' : '' }}>Cafe</option>
                                <option value="bar" {{ ($tenant->industry ?? '') == 'bar' ? 'selected' : '' }}>Bar</option>
                                <option value="food-truck" {{ ($tenant->industry ?? '') == 'food-truck' ? 'selected' : '' }}>Food Truck</option>
                                <option value="bakery" {{ ($tenant->industry ?? '') == 'bakery' ? 'selected' : '' }}>Bakery</option>
                                <option value="other" {{ ($tenant->industry ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    {{-- Contact Information --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email" value="{{ $tenant->email ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" id="phone" name="phone" value="{{ $tenant->phone ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                            <input type="url" id="website" name="website" value="{{ $tenant->website ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>

                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                            <div class="flex items-center space-x-4">
                                @if($tenant->logo_url ?? false)
                                    <img src="{{ $tenant->logo_url }}" alt="Current logo" class="w-16 h-16 object-cover rounded-lg border">
                                @else
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg border flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" id="logo" name="logo" accept="image/*" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Business Hours --}}
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Hours</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            $businessHours = $tenant->business_hours ?? [];
                        @endphp
                        @foreach($days as $day)
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 capitalize">{{ $day }}</label>
                                <div class="flex space-x-2">
                                    <input type="time" name="business_hours[{{ $day }}][open]" 
                                           value="{{ $businessHours[$day]['open'] ?? '09:00' }}"
                                           class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <span class="text-gray-500 self-center">to</span>
                                    <input type="time" name="business_hours[{{ $day }}][close]" 
                                           value="{{ $businessHours[$day]['close'] ?? '21:00' }}"
                                           class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="business_hours[{{ $day }}][closed]" value="1" 
                                           {{ ($businessHours[$day]['closed'] ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2 text-sm text-gray-600">Closed</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" onclick="resetForm()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Reset
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Outlets Tab --}}
        <div id="outlets-content" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Manage Outlets</h3>
                <button onclick="openAddOutletModal()" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Add Outlet
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($outlets as $outlet)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h4 class="font-semibold text-gray-900">{{ $outlet->name }}</h4>
                        @if(is_array($outlet->address))
                            <p class="text-sm text-gray-600 mt-1">
                                @if(isset($outlet->address['street']))
                                    {{ $outlet->address['street'] }}<br>
                                @endif
                                @if(isset($outlet->address['city']))
                                    {{ $outlet->address['city'] }}, 
                                @endif
                                @if(isset($outlet->address['state']))
                                    {{ $outlet->address['state'] }} 
                                @endif
                                @if(isset($outlet->address['zip']))
                                    {{ $outlet->address['zip'] }}
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-600 mt-1">{{ $outlet->address ?? 'No address provided' }}</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-1">{{ $outlet->phone ?? 'No phone provided' }}</p>
                        <div class="mt-3 flex space-x-2">
                            <button onclick="editOutlet({{ $outlet->id }})" 
                                    class="text-sm text-purple-600 hover:text-purple-700">Edit</button>
                            <button onclick="deleteOutlet({{ $outlet->id }})" 
                                    class="text-sm text-red-600 hover:text-red-700">Delete</button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        No outlets found. Add your first outlet to get started.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Billing Tab --}}
        <div id="billing-content" class="tab-content p-6 hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Settings</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bill Template</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option>Default Template</option>
                        <option>Minimal Template</option>
                        <option>Detailed Template</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tax Settings</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-600">Include tax in prices</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-600">Show tax breakdown on bills</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifications Tab --}}
        <div id="notifications-content" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Notification Settings</h3>
                <button onclick="openAddSettingModal()" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Add Setting
                    </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Notifications</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-600">New orders</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-600">Low inventory alerts</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-600">Daily reports</span>
                        </label>
                    </div>
                </div>
                
                {{-- Custom Settings --}}
                <div class="mt-8">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Custom Settings</h4>
                    <div id="custom-settings-list" class="space-y-3">
                        <!-- Custom settings will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Outlet Modal --}}
<div id="add-outlet-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Outlet</h3>
                <form id="add-outlet-form">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="outlet_name" class="block text-sm font-medium text-gray-700 mb-1">Outlet Name</label>
                            <input type="text" id="outlet_name" name="name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label for="outlet_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea id="outlet_address" name="address" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                        </div>
                        <div>
                            <label for="outlet_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" id="outlet_phone" name="phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" onclick="closeAddOutletModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Add Outlet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add Setting Modal --}}
<div id="add-setting-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Custom Setting</h3>
                <form id="add-setting-form">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="setting_key" class="block text-sm font-medium text-gray-700 mb-1">Setting Key</label>
                            <input type="text" id="setting_key" name="key" required
                                   placeholder="e.g., custom_notification_email"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label for="setting_value" class="block text-sm font-medium text-gray-700 mb-1">Setting Value</label>
                            <input type="text" id="setting_value" name="value" required
                                   placeholder="e.g., admin@restaurant.com"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label for="setting_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="setting_description" name="description" rows="2"
                                      placeholder="Brief description of this setting"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" onclick="closeAddSettingModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Add Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching functionality
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-purple-500', 'text-purple-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-purple-500', 'text-purple-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

// Slug field is disabled - no change detection needed

// General settings form submission
document.getElementById('general-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Debug: Log form data
    console.log('Form data being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    fetch('{{ route("tenant.settings", ["tenant" => $tenant->slug]) }}/general', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response received:', data);
        if (data.error) {
            console.error('Server error:', data.error);
            alert('Error: ' + data.error);
        } else {
            alert('Settings updated successfully!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating settings. Please check the console for details.');
    });
});

// Reset form
function resetForm() {
    document.getElementById('general-settings-form').reset();
    document.getElementById('slug-preview').textContent = '{{ $tenant->slug ?? "" }}';
}

// Outlet modal functions
function openAddOutletModal() {
    document.getElementById('add-outlet-modal').classList.remove('hidden');
}

function closeAddOutletModal() {
    document.getElementById('add-outlet-modal').classList.add('hidden');
    document.getElementById('add-outlet-form').reset();
}

// Setting modal functions
function openAddSettingModal() {
    document.getElementById('add-setting-modal').classList.remove('hidden');
}

function closeAddSettingModal() {
    document.getElementById('add-setting-modal').classList.add('hidden');
    document.getElementById('add-setting-form').reset();
}

// Add outlet form submission
document.getElementById('add-outlet-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("tenant.settings", ["tenant" => $tenant->slug]) }}/outlets', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
        } else {
            alert('Outlet added successfully!');
            closeAddOutletModal();
            location.reload(); // Refresh to show new outlet
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding outlet.');
    });
});

// Add setting form submission
document.getElementById('add-setting-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // For now, we'll just add it to the UI as a demo
    const key = formData.get('key');
    const value = formData.get('value');
    const description = formData.get('description');
    
    // Add to custom settings list
    const customSettingsList = document.getElementById('custom-settings-list');
    const settingDiv = document.createElement('div');
    settingDiv.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';
    settingDiv.innerHTML = `
        <div class="flex-1">
            <div class="font-medium text-gray-900">${key}</div>
            <div class="text-sm text-gray-600">${value}</div>
            ${description ? `<div class="text-xs text-gray-500 mt-1">${description}</div>` : ''}
        </div>
        <div class="flex space-x-2">
            <button onclick="editCustomSetting('${key}')" class="text-sm text-purple-600 hover:text-purple-700">Edit</button>
            <button onclick="deleteCustomSetting('${key}')" class="text-sm text-red-600 hover:text-red-700">Delete</button>
        </div>
    `;
    customSettingsList.appendChild(settingDiv);
    
    alert('Custom setting added successfully!');
    closeAddSettingModal();
});

// Outlet management functions
function editOutlet(id) {
    alert('Edit outlet functionality will be implemented');
}

function deleteOutlet(id) {
    if (confirm('Are you sure you want to delete this outlet?')) {
        fetch(`{{ route("tenant.settings", ["tenant" => $tenant->slug]) }}/outlets/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                alert('Outlet deleted successfully!');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting outlet.');
        });
    }
}

// Custom setting management functions
function editCustomSetting(key) {
    const newValue = prompt(`Edit value for "${key}":`);
    if (newValue !== null) {
        // Find and update the setting in the UI
        const customSettingsList = document.getElementById('custom-settings-list');
        const settings = customSettingsList.querySelectorAll('div');
        settings.forEach(setting => {
            const keyElement = setting.querySelector('.font-medium');
            if (keyElement && keyElement.textContent === key) {
                const valueElement = setting.querySelector('.text-sm.text-gray-600');
                if (valueElement) {
                    valueElement.textContent = newValue;
                }
            }
        });
        alert('Setting updated successfully!');
    }
}

function deleteCustomSetting(key) {
    if (confirm(`Are you sure you want to delete the setting "${key}"?`)) {
        const customSettingsList = document.getElementById('custom-settings-list');
        const settings = customSettingsList.querySelectorAll('div');
        settings.forEach(setting => {
            const keyElement = setting.querySelector('.font-medium');
            if (keyElement && keyElement.textContent === key) {
                setting.remove();
            }
        });
        alert('Setting deleted successfully!');
    }
}
</script>

<style>
.tab-button.active {
    border-bottom-color: #6E46AE !important;
    color: #6E46AE !important;
}
</style>
@endsection