<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Open Shift - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .bg-royal-purple { background-color: #6E46AE; }
        .text-royal-purple { color: #6E46AE; }
        .border-royal-purple { border-color: #6E46AE; }
        .bg-tiffany-blue { background-color: #00B6B4; }
        .text-tiffany-blue { color: #00B6B4; }
        .border-tiffany-blue { border-color: #00B6B4; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-royal-purple rounded-full flex items-center justify-center">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Open Shift
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Welcome, {{ $terminalUser->name }} ({{ $terminalUser->terminal_id }})
                </p>
            </div>

            <!-- Shift Opening Form -->
            <div class="bg-white py-8 px-6 shadow rounded-lg">
                <div x-data="shiftOpen()" class="space-y-6">
                    <!-- Existing Shift Warning -->
                    <div x-show="existingShift" class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.725-1.36 3.49 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Existing Shift Found
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>You already have an open shift. Choose an action:</p>
                                </div>
                                <div class="mt-3 flex space-x-3">
                                    <button 
                                        @click="continueExistingShift()"
                                        class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-150 ease-in-out"
                                    >
                                        Continue Existing Shift
                                    </button>
                                    <button 
                                        @click="closeAndOpenNewShift()"
                                        class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-150 ease-in-out"
                                    >
                                        Close & Open New Shift
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="openShift()" class="space-y-6">
                        <!-- Outlet Selection -->
                        <div>
                            <label for="outlet_id" class="block text-sm font-medium text-gray-700">
                                Select Outlet
                            </label>
                            <select 
                                id="outlet_id" 
                                x-model="formData.outlet_id" 
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple sm:text-sm"
                            >
                                <option value="">Choose an outlet</option>
                                @foreach($outlets as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Opening Float -->
                        <div>
                            <label for="opening_float" class="block text-sm font-medium text-gray-700">
                                Opening Float (₹)
                            </label>
                            <input 
                                type="number" 
                                id="opening_float" 
                                x-model="formData.opening_float" 
                                step="0.01" 
                                min="0"
                                placeholder="0.00"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple sm:text-sm"
                            >
                            <p class="mt-1 text-xs text-gray-500">
                                Enter the amount of cash in the drawer at shift start
                            </p>
                        </div>

                        <!-- Current Time Display -->
                        <div class="bg-gray-50 rounded-md p-4">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Current Time</p>
                                    <p class="text-lg text-gray-600" x-text="currentTime"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            <button 
                                type="button" 
                                @click="logout()"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
                            >
                                Logout
                            </button>
                            
                            <button 
                                type="button" 
                                @click="testSession()"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
                            >
                                Test Session
                            </button>
                            
                            <button 
                                type="submit" 
                                :disabled="loading"
                                class="flex-1 bg-royal-purple hover:bg-purple-700 disabled:bg-gray-400 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
                            >
                                <span x-show="!loading">Open Shift</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Opening...
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Error Message -->
                    <div x-show="error" class="bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800" x-text="error"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500">
                <p>{{ $tenant->name }} - POS Terminal</p>
            </div>
        </div>
    </div>

    <script>
        function shiftOpen() {
            return {
                loading: false,
                error: '',
                existingShift: @json($existingShift ? true : false),
                formData: {
                    outlet_id: '',
                    opening_float: 0
                },
                currentTime: '',

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                    
                    // Debug: Check session data on page load
                    console.log('=== SHIFT OPEN DEBUG ===');
                    console.log('Terminal user:', localStorage.getItem('terminal_user'));
                    console.log('Session token:', localStorage.getItem('terminal_session_token'));
                    console.log('Document cookies:', document.cookie);
                    
                    // Check if we have required session data
                    const sessionToken = localStorage.getItem('terminal_session_token');
                    const terminalUser = localStorage.getItem('terminal_user');
                    
                    if (!sessionToken || !terminalUser) {
                        console.error('Missing session data, redirecting to login');
                        alert('Session expired. Please login again.');
                        window.location.href = `/{{ $tenant->slug }}/terminal/login`;
                        return;
                    }
                },

                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                },

                async testSession() {
                    this.loading = true;
                    this.error = '';

                    try {
                        const response = await fetch(`/{{ $tenant->slug }}/pos/api/shifts/test-session`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Terminal-Session-Token': localStorage.getItem('terminal_session_token')
                            },
                            credentials: 'include'
                        });

                        const data = await response.json();
                        console.log('Session test result:', data);
                        
                        if (data.session_found) {
                            alert('✅ Session is working! User: ' + data.terminal_user.name);
                        } else {
                            alert('❌ Session not found. Token: ' + data.session_token);
                        }
                    } catch (error) {
                        console.error('Session test error:', error);
                        alert('❌ Session test failed: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },

                async openShift() {
                    this.loading = true;
                    this.error = '';

                    // Debug: Check session token
                    const sessionToken = localStorage.getItem('terminal_session_token');
                    console.log('Session token:', sessionToken);
                    console.log('Form data:', this.formData);

                    try {
                        const response = await fetch(`/{{ $tenant->slug }}/pos/api/shifts/open`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Terminal-Session-Token': localStorage.getItem('terminal_session_token')
                            },
                            credentials: 'include',
                            body: JSON.stringify(this.formData)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Store shift data
                            localStorage.setItem('pos_shift_data', JSON.stringify({
                                shift: data,
                                timestamp: Date.now(),
                                outletId: data.outlet_id
                            }));

                            // Redirect to POS terminal
                            window.location.href = `/{{ $tenant->slug }}/pos/terminal`;
                        } else {
                            this.error = data.error || 'Failed to open shift';
                        }
                    } catch (error) {
                        this.error = 'Network error. Please try again.';
                        console.error('Error opening shift:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async continueExistingShift() {
                    this.loading = true;
                    this.error = '';

                    try {
                        // Get existing shift data
                        const response = await fetch(`/{{ $tenant->slug }}/pos/api/shifts/current`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Terminal-Session-Token': localStorage.getItem('terminal_session_token')
                            },
                            credentials: 'include'
                        });

                        const data = await response.json();

                        if (response.ok && data.shift) {
                            // Store shift data
                            localStorage.setItem('pos_shift_data', JSON.stringify({
                                shift: data.shift,
                                timestamp: Date.now(),
                                outletId: data.shift.outlet_id
                            }));

                            // Redirect to POS terminal
                            window.location.href = `/{{ $tenant->slug }}/pos/terminal`;
                        } else {
                            this.error = 'Failed to load existing shift';
                        }
                    } catch (error) {
                        this.error = 'Network error. Please try again.';
                        console.error('Error loading existing shift:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async closeAndOpenNewShift() {
                    if (!confirm('Are you sure you want to close the existing shift and open a new one?')) {
                        return;
                    }

                    this.loading = true;
                    this.error = '';

                    try {
                        // First close the existing shift
                        const closeResponse = await fetch(`/{{ $tenant->slug }}/pos/api/shifts/close`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Terminal-Session-Token': localStorage.getItem('terminal_session_token')
                            },
                            credentials: 'include',
                            body: JSON.stringify({
                                actual_cash: 0 // Default value for closing
                            })
                        });

                        if (closeResponse.ok) {
                            // Now open a new shift
                            await this.openShift();
                        } else {
                            const closeData = await closeResponse.json();
                            this.error = closeData.error || 'Failed to close existing shift';
                        }
                    } catch (error) {
                        this.error = 'Network error. Please try again.';
                        console.error('Error closing and opening new shift:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                logout() {
                    if (confirm('Are you sure you want to logout? This will close your current shift.')) {
                        this.closeShiftAndLogout();
                    }
                },

                async closeShiftAndLogout() {
                    this.loading = true;
                    
                    try {
                        // Try to close the shift before logout
                        const response = await fetch(`/{{ $tenant->slug }}/pos/api/shifts/close`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Terminal-Session-Token': localStorage.getItem('terminal_session_token')
                            },
                            credentials: 'include',
                            body: JSON.stringify({
                                actual_cash: 0 // Default value for closing
                            })
                        });

                        if (response.ok) {
                            console.log('Shift closed successfully');
                        } else {
                            console.warn('Failed to close shift, but proceeding with logout');
                        }
                    } catch (error) {
                        console.warn('Error closing shift, but proceeding with logout:', error);
                    } finally {
                        // Clear session data regardless of shift close result
                        localStorage.removeItem('terminal_user');
                        localStorage.removeItem('terminal_session_token');
                        localStorage.removeItem('pos_shift_data');
                        
                        // Redirect to login
                        window.location.href = `/{{ $tenant->slug }}/terminal/login`;
                    }
                }
            }
        }
    </script>
</body>
</html>
