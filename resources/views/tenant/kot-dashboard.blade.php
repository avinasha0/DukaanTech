<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KOT Dashboard - {{ $tenant->name }} - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-red-50 min-h-screen">
    {{-- Simple Kitchen Header --}}
    <div class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-3">
            <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-2xl font-bold text-gray-900">{{ $tenant->name }}</span>
                    <span class="text-lg text-gray-600 ml-2">Kitchen</span>
                </div>
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="min-h-screen py-6">
        <div class="mx-auto max-w-7xl px-4">
            {{-- KOT Dashboard --}}
            <div x-data="kotDashboard()" x-init="loadKotTickets()" class="space-y-6">
                
                {{-- KOT Disabled Message --}}
                <div x-show="!kotEnabled" class="bg-red-50 border border-red-200 rounded-xl p-8 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-red-800 mb-2">KOT Functionality Disabled</h3>
                    <p class="text-red-600 mb-4">Kitchen Order Ticket functionality is currently disabled. Please enable KOT in the dashboard header to use this feature.</p>
                    <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Go to Dashboard
                    </a>
                </div>
                {{-- KOT Enabled Content --}}
                <div x-show="kotEnabled" class="space-y-6">
                {{-- Filter Controls --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Station</label>
                        <select x-model="filters.station" @change="loadKotTickets()" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">All Stations</option>
                            <option value="hot-kitchen">Hot Kitchen</option>
                            <option value="cold-kitchen">Cold Kitchen</option>
                            <option value="beverages">Beverages</option>
                            <option value="desserts">Desserts</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select x-model="filters.status" @change="loadKotTickets()" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">All Status</option>
                            <option value="SENT" selected>Sent</option>
                            <option value="READY">Ready</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-1">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button @click="toggleAutoRefresh()" 
                                    :class="autoRefresh ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-300 hover:bg-gray-400'"
                                    class="flex-1 px-4 py-2 rounded-lg text-white text-sm font-medium transition-colors">
                                <span x-text="autoRefresh ? 'Auto-Refresh ON' : 'Auto-Refresh OFF'"></span>
                            </button>
                            <button @click="loadKotTickets()" 
                                    :disabled="loading"
                                    class="flex-1 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!loading">Refresh</span>
                                <span x-show="loading">Loading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

                {{-- KOT Tickets Desktop Table --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    {{-- Desktop Table --}}
                    <div class="hidden lg:block">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-orange-500 to-red-600">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">KOT #</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">Order #</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">Station</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">Time</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">Items</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="ticket in kotTickets" :key="ticket.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-2xl font-bold text-gray-900" x-text="ticket.id"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-lg font-semibold text-gray-700" x-text="ticket.order_id"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800" x-text="ticket.station"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600" x-text="formatTime(ticket.created_at)"></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-1">
                                                <template x-for="(line, index) in ticket.lines" :key="line.id">
                                                    <div class="flex items-center justify-between text-sm">
                                                        <span class="text-gray-700 truncate" x-text="line.order_item.item.name"></span>
                                                        <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-bold ml-2" x-text="'x' + line.qty"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="ticket.status === 'SENT' ? 'bg-orange-100 text-orange-800 border-orange-200' : 'bg-green-100 text-green-800 border-green-200'"
                                                  class="px-3 py-1 rounded-full text-sm font-bold border-2"
                                                  x-text="ticket.status"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button x-show="ticket.status === 'SENT'"
                                                    @click="confirmMarkReady(ticket.id)"
                                                    :data-ticket-id="ticket.id"
                                                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm font-bold disabled:opacity-50 disabled:cursor-not-allowed">
                                                ✓ READY
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        
                        {{-- Empty State for Desktop --}}
                        <div x-show="kotTickets.length === 0 && !loading" class="text-center py-16">
                            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">No Pending Orders</h3>
                            <p class="text-xl text-gray-600" x-text="filters.status === 'SENT' ? 'No orders waiting to be prepared' : 'No orders match the current filter'"></p>
                        </div>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="lg:hidden divide-y divide-gray-200">
                        <template x-for="ticket in kotTickets" :key="ticket.id">
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="space-y-3">
                                    {{-- Header Row --}}
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="text-2xl font-bold text-gray-900" x-text="'KOT #' + ticket.id"></div>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800" x-text="ticket.station"></span>
                                        </div>
                                        <span :class="ticket.status === 'SENT' ? 'bg-orange-100 text-orange-800 border-orange-200' : 'bg-green-100 text-green-800 border-green-200'"
                                              class="px-3 py-1 rounded-full text-sm font-bold border-2"
                                              x-text="ticket.status"></span>
                                    </div>
                                    
                                    {{-- Order Info --}}
                                    <div class="text-sm text-gray-600">
                                        <div>Order #<span x-text="ticket.order_id"></span></div>
                                        <div x-text="formatTime(ticket.created_at)"></div>
                                    </div>
                                    
                                    {{-- Items Preview --}}
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-gray-700">Items:</div>
                                        <template x-for="(line, index) in ticket.lines" :key="line.id">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-700 truncate" x-text="line.order_item.item.name"></span>
                                                <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-bold ml-2" x-text="'x' + line.qty"></span>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    {{-- Action Button --}}
                                    <div class="pt-2">
                                        <button x-show="ticket.status === 'SENT'"
                                                @click="confirmMarkReady(ticket.id)"
                                                :data-ticket-id="ticket.id"
                                                class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm font-bold disabled:opacity-50 disabled:cursor-not-allowed">
                                            ✓ MARK AS READY
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        {{-- Empty State for Mobile --}}
                        <div x-show="kotTickets.length === 0 && !loading" class="text-center py-16">
                            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">No Pending Orders</h3>
                            <p class="text-xl text-gray-600" x-text="filters.status === 'SENT' ? 'No orders waiting to be prepared' : 'No orders match the current filter'"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        function kotToggle() {
            return {
                kotEnabled: false,
                loading: false,
                
                async loadKotStatus() {
                    try {
                        const response = await fetch(`/{{ $tenant->slug }}/kot/status`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.kotEnabled = data.kot_enabled;
                        }
                    } catch (error) {
                        console.error('Error loading KOT status:', error);
                    }
                },
                
                async toggleKot() {
                    try {
                        this.loading = true;
                        const response = await fetch(`/{{ $tenant->slug }}/kot/toggle-public`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                enabled: !this.kotEnabled
                            })
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.kotEnabled = data.kot_enabled;
                            this.showNotification(data.message, 'success');
                        } else {
                            const error = await response.json();
                            this.showNotification(error.message || 'Failed to update KOT settings', 'error');
                        }
                    } catch (error) {
                        console.error('Error toggling KOT:', error);
                        this.showNotification('Failed to update KOT settings', 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                
                showNotification(message, type) {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                    }`;
                    notification.textContent = message;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            }
        }

        function kotDashboard() {
            return {
                kotTickets: [],
                loading: false,
                kotEnabled: false,
                expandedTickets: [],
                autoRefresh: true,
                refreshInterval: null,
                filters: {
                    station: '',
                    status: 'SENT'  // Default to show only SENT status
                },
                
                init() {
                    this.loadKotTickets();
                    this.startAutoRefresh();
                },
                
                destroy() {
                    this.stopAutoRefresh();
                },
                
                startAutoRefresh() {
                    if (this.refreshInterval) {
                        clearInterval(this.refreshInterval);
                    }
                    
                    if (this.autoRefresh) {
                        this.refreshInterval = setInterval(() => {
                            this.loadKotTickets();
                        }, 5000); // Refresh every 5 seconds
                    }
                },
                
                stopAutoRefresh() {
                    if (this.refreshInterval) {
                        clearInterval(this.refreshInterval);
                        this.refreshInterval = null;
                    }
                },
                
                toggleAutoRefresh() {
                    this.autoRefresh = !this.autoRefresh;
                    if (this.autoRefresh) {
                        this.startAutoRefresh();
                    } else {
                        this.stopAutoRefresh();
                    }
                },
                
                async loadKotTickets() {
                    try {
                        this.loading = true;
                        
                        // First load KOT status
                        const statusResponse = await fetch(`/{{ $tenant->slug }}/kot/status-public`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        if (statusResponse.ok) {
                            const statusData = await statusResponse.json();
                            this.kotEnabled = statusData.kot_enabled;
                        }
                        
                        // Only load tickets if KOT is enabled
                        if (this.kotEnabled) {
                            const params = new URLSearchParams();
                            if (this.filters.station) params.append('station', this.filters.station);
                            if (this.filters.status) params.append('status', this.filters.status);
                            
                            const response = await fetch(`/{{ $tenant->slug }}/api/kot?${params}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            });
                            
                            if (response.ok) {
                                this.kotTickets = await response.json();
                            }
                        }
                    } catch (error) {
                        console.error('Error loading KOT tickets:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                
                async markReady(ticketId) {
                    try {
                        // Show loading state
                        const button = document.querySelector(`[data-ticket-id="${ticketId}"]`);
                        if (button) {
                            button.disabled = true;
                            button.innerHTML = '<svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
                        }
                        
                        const response = await fetch(`/{{ $tenant->slug }}/kot/${ticketId}/ready`, {
                            method: 'PUT',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok) {
                            this.showNotification(data.message, 'success');
                            
                            // Show additional notification if entire order is ready
                            if (data.order_ready) {
                                this.showNotification('All items for this order are ready!', 'success');
                            }
                            
                            // Play success sound
                            this.playSound('success');
                            
                            // Refresh the list
                            await this.loadKotTickets();
                        } else {
                            this.showNotification(data.error || 'Failed to mark KOT as ready', 'error');
                            this.playSound('error');
                        }
                    } catch (error) {
                        console.error('Error marking KOT as ready:', error);
                        this.showNotification('Network error. Please try again.', 'error');
                        this.playSound('error');
                    } finally {
                        // Reset button state
                        const button = document.querySelector(`[data-ticket-id="${ticketId}"]`);
                        if (button) {
                            button.disabled = false;
                            button.innerHTML = '✓ READY';
                        }
                    }
                },
                
                playSound(type) {
                    try {
                        // Create audio context for sound notifications
                        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);
                        
                        if (type === 'success') {
                            // Success sound: ascending tone
                            oscillator.frequency.setValueAtTime(523.25, audioContext.currentTime); // C5
                            oscillator.frequency.setValueAtTime(659.25, audioContext.currentTime + 0.1); // E5
                            oscillator.frequency.setValueAtTime(783.99, audioContext.currentTime + 0.2); // G5
                            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                            oscillator.start(audioContext.currentTime);
                            oscillator.stop(audioContext.currentTime + 0.3);
                        } else if (type === 'error') {
                            // Error sound: descending tone
                            oscillator.frequency.setValueAtTime(523.25, audioContext.currentTime); // C5
                            oscillator.frequency.setValueAtTime(392.00, audioContext.currentTime + 0.1); // G4
                            oscillator.frequency.setValueAtTime(261.63, audioContext.currentTime + 0.2); // C4
                            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                            oscillator.start(audioContext.currentTime);
                            oscillator.stop(audioContext.currentTime + 0.3);
                        }
                    } catch (error) {
                        console.log('Audio not supported or blocked');
                    }
                },
                
                async confirmMarkReady(ticketId) {
                    // Simple confirmation dialog
                    const confirmed = confirm('Are you sure you want to mark this KOT as ready?');
                    if (confirmed) {
                        await this.markReady(ticketId);
                    }
                },
                
                viewDetails(ticketId) {
                    // You can implement a modal or redirect to detailed view
                    console.log('View details for ticket:', ticketId);
                },
                
                toggleExpanded(ticketId) {
                    const index = this.expandedTickets.indexOf(ticketId);
                    if (index > -1) {
                        this.expandedTickets.splice(index, 1);
                    } else {
                        this.expandedTickets.push(ticketId);
                    }
                },
                
                formatTime(timestamp) {
                    if (!timestamp) return 'Unknown';
                    const date = new Date(timestamp);
                    return date.toLocaleTimeString();
                },
                
                showNotification(message, type) {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                    }`;
                    notification.textContent = message;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            }
        }
    </script>
</body>
</html>
