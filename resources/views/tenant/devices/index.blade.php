<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Management - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Fix dropdown z-index issues */
        select {
            z-index: 10000 !important;
        }
        
        select:focus {
            z-index: 10001 !important;
        }
        
        /* Ensure modal content is above everything */
        .modal-content {
            z-index: 10000 !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-red-50 min-h-screen">
    {{-- Header Component --}}
    <x-dashboard-header />

    {{-- Page Header Section --}}
    <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white">
        <div class="mx-auto max-w-7xl px-4 py-12">
            <div class="text-center">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-3xl lg:text-4xl font-bold mb-4 font-dm">Terminal Management</h1>
                <p class="text-lg lg:text-xl text-orange-100 max-w-2xl mx-auto mb-6">
                    Set up and manage multiple POS terminals for different areas of your restaurant
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <div class="flex items-center gap-2 text-orange-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm">Multi-terminal billing</span>
                    </div>
                    <div class="flex items-center gap-2 text-orange-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="text-sm">Real-time sync</span>
                    </div>
                    <div class="flex items-center gap-2 text-orange-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-sm">Detailed reporting</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="min-h-screen py-8">
        <div class="mx-auto max-w-7xl px-4">
            <div x-data="deviceManager()" x-init="loadOutlets()">
                {{-- Quick Stats Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Devices</p>
                                <p class="text-2xl font-bold text-gray-900" x-text="devices.length"></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">POS Terminals</p>
                                <p class="text-2xl font-bold text-gray-900" x-text="devices.filter(d => d.type === 'POS').length"></p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 12v-1m-6.5-2.5l-1.406 1.406M17.5 6.5l1.406-1.406M12 12.25c-1.11 0-2.08-.402-2.599-1M12 12.25V12m0 12.25v-1C10.89 21.25 10 20.36 10 19.25S10.89 17.25 12 17.25s2 .89 2 2-1.343 2-3 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Kitchen Displays</p>
                                <p class="text-2xl font-bold text-gray-900" x-text="devices.filter(d => d.type === 'KITCHEN').length"></p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Device List -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-6 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Terminal Devices</h2>
                                <p class="text-sm text-gray-600 mt-1">Manage your POS terminals and billing counters</p>
                            </div>
                            <button @click="showCreateModal = true" 
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white text-sm font-medium rounded-lg hover:from-orange-600 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add New Device
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Device Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Outlet</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">URL To Access</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">API Key</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="device in devices" :key="device.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-4"
                                                     :class="device.type === 'POS' ? 'bg-blue-100' : device.type === 'KITCHEN' ? 'bg-orange-100' : 'bg-purple-100'">
                                                    <svg class="w-5 h-5" 
                                                         :class="device.type === 'POS' ? 'text-blue-600' : device.type === 'KITCHEN' ? 'text-orange-600' : 'text-purple-600'"
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-900" x-text="device.name"></div>
                                                    <div class="text-xs text-gray-500">ID: <span x-text="device.id"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full"
                                                  :class="device.type === 'POS' ? 'bg-blue-100 text-blue-800' : device.type === 'KITCHEN' ? 'bg-orange-100 text-orange-800' : 'bg-purple-100 text-purple-800'"
                                                  x-text="device.type"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="device.outlet?.name || 'N/A'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <a :href="getDeviceUrl(device)" 
                                                   target="_blank"
                                                   class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-lg font-mono hover:bg-blue-200 transition-colors"
                                                   x-text="getDeviceUrl(device)"></a>
                                                <button @click="copyDeviceUrl(device)" 
                                                        class="ml-2 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition-colors"
                                                        title="Copy URL">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <code class="text-xs bg-gray-100 px-3 py-1 rounded-lg font-mono" x-text="device.api_key"></code>
                                                <button @click="copyApiKey(device.api_key)" 
                                                        class="ml-2 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition-colors"
                                                        title="Copy API Key">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(device.created_at)"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="deleteDevice(device.id)" 
                                                    class="text-red-600 hover:text-red-900 hover:bg-red-50 px-3 py-1 rounded-lg transition-colors">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="devices.length === 0">
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No devices found</h3>
                                            <p class="text-gray-500 mb-4">Create your first terminal device to get started</p>
                                            <button @click="showCreateModal = true" 
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white text-sm font-medium rounded-lg hover:from-orange-600 hover:to-red-700 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                                Add New Device
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create Device Modal -->
                <div x-show="showCreateModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full"
                     style="z-index: 9998; display: none;">
                    <div class="relative top-20 mx-auto p-5 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white modal-content"
                         style="z-index: 9999;">
                        <div class="mt-3">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Create New Device</h3>
                                <p class="text-gray-600">Set up a new terminal for your restaurant</p>
                            </div>
                            <form @submit.prevent="createDevice()">
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Device Name</label>
                                        <input type="text" x-model="newDevice.name" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                               placeholder="e.g., Counter 1, Kitchen Terminal" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                                        <select x-model="newDevice.type" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" required>
                                            <option value="POS">POS Terminal</option>
                                            <option value="KITCHEN">Kitchen Display</option>
                                            <option value="TOKEN">Token Counter</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Outlet</label>
                                        <div class="text-xs text-gray-500 mb-2" x-text="'Available outlets: ' + outlets.length"></div>
                                        <select x-model="newDevice.outlet_id" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all relative z-50" 
                                                style="z-index: 9999;"
                                                required>
                                            <option value="">Select an outlet</option>
                                            <template x-for="outlet in outlets" :key="outlet.id">
                                                <option :value="outlet.id" x-text="outlet.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="flex gap-3 mt-8">
                                    <button type="button" @click="showCreateModal = false" 
                                            class="flex-1 px-4 py-3 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 rounded-xl transition-all shadow-lg hover:shadow-xl">
                                        Create Device
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div x-show="successMessage" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl z-50 shadow-lg"
                     style="display: none;">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="successMessage"></span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function deviceManager() {
            return {
                devices: @json($devices),
                outlets: @json($outlets),
                showCreateModal: false,
                successMessage: '',
                newDevice: {
                    name: '',
                    type: 'POS',
                    outlet_id: @json($outlets->first()?->id ?? null)
                },


                async loadOutlets() {
                    try {
                        const response = await fetch(`/{{ $tenant->slug }}/api/outlets`);
                        this.outlets = await response.json();
                        console.log('Loaded outlets:', this.outlets);
                        if (this.outlets.length > 0) {
                            this.newDevice.outlet_id = this.outlets[0].id;
                        }
                    } catch (error) {
                        console.error('Error loading outlets:', error);
                        console.log('Using outlets from backend:', this.outlets);
                    }
                },

                async createDevice() {
                    try {
                        console.log('Creating device with data:', this.newDevice);
                        const deviceData = {
                            ...this.newDevice,
                            outlet_id: parseInt(this.newDevice.outlet_id)
                        };
                        console.log('Processed device data:', deviceData);
                        
                        const response = await fetch(`/{{ $tenant->slug }}/api/devices`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(deviceData)
                        });

                        console.log('Response status:', response.status);
                        
                        if (response.ok) {
                            this.showCreateModal = false;
                            this.newDevice = { name: '', type: 'POS', outlet_id: this.outlets.length > 0 ? this.outlets[0].id : null };
                            // Refresh the page to show the new device
                            window.location.reload();
                        } else {
                            const error = await response.json();
                            console.error('Error response:', error);
                            alert('Error: ' + (error.message || 'Failed to create device'));
                        }
                    } catch (error) {
                        console.error('Error creating device:', error);
                        alert('Error creating device: ' + error.message);
                    }
                },

                async deleteDevice(deviceId) {
                    if (!confirm('Are you sure you want to delete this device?')) return;
                    
                    try {
                        const response = await fetch(`/{{ $tenant->slug }}/api/devices/${deviceId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.ok) {
                            // Refresh the page to show updated device list
                            window.location.reload();
                        } else {
                            alert('Failed to delete device');
                        }
                    } catch (error) {
                        console.error('Error deleting device:', error);
                        alert('Error deleting device');
                    }
                },

                copyApiKey(apiKey) {
                    navigator.clipboard.writeText(apiKey).then(() => {
                        this.showSuccess('API Key copied to clipboard!');
                    });
                },

                getDeviceUrl(device) {
                    const baseUrl = window.location.origin;
                    const tenantSlug = '{{ $tenant->slug }}';
                    
                    switch(device.type) {
                        case 'POS':
                            return `${baseUrl}/${tenantSlug}/pos/register?device_id=${device.id}&device_key=${device.api_key}`;
                        case 'KITCHEN':
                            return `${baseUrl}/${tenantSlug}/kot?device_id=${device.id}&device_key=${device.api_key}`;
                        case 'TOKEN':
                            return `${baseUrl}/${tenantSlug}/pos/register?device_id=${device.id}&device_key=${device.api_key}&mode=token`;
                        default:
                            return `${baseUrl}/${tenantSlug}/pos/register?device_id=${device.id}&device_key=${device.api_key}`;
                    }
                },

                copyDeviceUrl(device) {
                    const url = this.getDeviceUrl(device);
                    navigator.clipboard.writeText(url).then(() => {
                        this.showSuccess('Device URL copied to clipboard!');
                    });
                },

                showSuccess(message) {
                    this.successMessage = message;
                    setTimeout(() => {
                        this.successMessage = '';
                    }, 3000);
                },

                formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString();
                }
            }
        }
    </script>

    {{-- Footer Component --}}
    <x-dashboard-footer />
</body>
</html>
