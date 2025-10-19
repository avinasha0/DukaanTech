<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Shift - {{ $tenant->name ?? 'POS Terminal' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <link rel="apple-touch-icon" href="/favicon.png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
<div x-data="shiftOpen()" x-init="init()" class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-40 h-24 mx-auto mb-6 flex items-center justify-center">
                <img src="/images/logos/dukaantech-logo-new.png" alt="DukaanTech Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Open Shift</h1>
            <p class="text-gray-600">Welcome, {{ $terminalUser->name }} ({{ $terminalUser->terminal_id }})</p>
        </div>

        <!-- Shift Opening Form -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <div class="space-y-6">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Outlet</label>
                        <select 
                            x-model="formData.outlet_id" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-lg"
                        >
                            <option value="">Choose an outlet</option>
                            @foreach($outlets as $outlet)
                                <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Opening Float -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opening Float (₹)</label>
                        <input 
                            type="number" 
                            x-model="formData.opening_float" 
                            step="0.01" 
                            min="0"
                            placeholder="0.00"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-2xl font-mono tracking-widest"
                        >
                        <p class="mt-1 text-xs text-gray-500 text-center">
                            Enter the amount of cash in the drawer at shift start
                        </p>
                    </div>

                    <!-- Current Time Display -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-900">Current Time</p>
                                <p class="text-lg text-gray-600 font-mono" x-text="currentTime"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div x-show="error" class="text-red-600 text-sm text-center bg-red-50 p-3 rounded-lg"></div>

                    <!-- Success Popup -->
                    <div x-show="showSuccessPopup" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-2xl p-8 max-w-sm mx-4 text-center shadow-2xl">
                            <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Success!</h3>
                            <p class="text-gray-600 mb-6" x-text="successMessage"></p>
                            <div class="flex justify-center">
                                <div class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="loadingMessage"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="text-center">
                        <div class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Opening shift...
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button 
                            type="submit" 
                            :disabled="loading || !formData.outlet_id"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                        >
                            <span x-show="!loading">Open Shift</span>
                            <span x-show="loading">Opening...</span>
                        </button>
                        
                        <button 
                            type="button" 
                            @click="logout()"
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200"
                        >
                            Logout
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-500 text-sm">
            <p>Enter shift details to start your work session</p>
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
                showSuccessPopup: false,
                successMessage: '',
                loadingMessage: '',

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                    
                    // Check if we have required session data
                    const sessionToken = localStorage.getItem('terminal_session_token');
                    const terminalUser = localStorage.getItem('terminal_user');
                    
                    if (!sessionToken || !terminalUser) {
                        alert('Session expired. Please login again.');
                        window.location.href = `/{{ $tenant->slug }}/terminal/login`;
                        return;
                    }
                    
                    // Auto-select first outlet if only one is available
                    const outlets = @json($outlets);
                    if (outlets.length === 1) {
                        this.formData.outlet_id = outlets[0].id;
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



                async openShift() {
                    this.loading = true;
                    this.error = '';

                    // Validate form data
                    if (!this.formData.outlet_id) {
                        this.error = 'Please select an outlet';
                        this.loading = false;
                        return;
                    }


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

                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            this.error = 'Server error: ' + response.status + ' - ' + response.statusText;
                            this.loading = false;
                            return;
                        }

                        const data = await response.json();

                        if (response.ok) {
                            // Store shift data
                            localStorage.setItem('pos_shift_data', JSON.stringify({
                                shift: data,
                                timestamp: Date.now(),
                                outletId: data.outlet_id
                            }));

                            // Show success popup
                            this.showSuccessPopup = true;
                            this.successMessage = 'Shift opened successfully!';
                            this.loadingMessage = 'Redirecting to POS...';

                            // Redirect to POS terminal after showing popup
                            setTimeout(() => {
                                window.location.href = `/{{ $tenant->slug }}/pos/terminal`;
                            }, 2000);
                        } else {
                            this.error = data.error || 'Failed to open shift';
                        }
                    } catch (error) {
                        this.error = 'Network error. Please try again.';
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
                    } finally {
                        this.loading = false;
                    }
                },

                async closeAndOpenNewShift() {
                    this.showSuccessPopup = true;
                    this.successMessage = 'Closing existing shift...';
                    this.loadingMessage = 'Opening new shift...';

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
                            // Update popup message
                            this.successMessage = 'Shift closed successfully!';
                            this.loadingMessage = 'Opening new shift...';
                            
                            // Now open a new shift
                            await this.openShift();
                        } else {
                            const closeData = await closeResponse.json();
                            this.showSuccessPopup = false;
                            this.error = closeData.error || 'Failed to close existing shift';
                        }
                    } catch (error) {
                        this.showSuccessPopup = false;
                        this.error = 'Network error. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                },

                logout() {
                    this.showSuccessPopup = true;
                    this.successMessage = 'Shift closed successfully!';
                    this.loadingMessage = 'Logging out...';
                    
                    // Auto logout after showing the popup
                    setTimeout(() => {
                        this.logoutWithoutClosingShift();
                    }, 2000);
                },

                async logoutWithoutClosingShift() {
                    try {
                        // Call the logout endpoint to properly clean up the session
                        const sessionToken = localStorage.getItem('terminal_session_token');
                        const response = await fetch(`/{{ $tenant->slug }}/terminal/logout`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Terminal-Session-Token': sessionToken || ''
                            },
                            credentials: 'include'
                        });
                    } catch (error) {
                        // Continue with logout even if API call fails
                    }
                    
                    // Clear session data and logout without trying to close shift
                    localStorage.removeItem('terminal_user');
                    localStorage.removeItem('terminal_session_token');
                    localStorage.removeItem('pos_shift_data');
                    
                    // Redirect to login
                    window.location.href = `/{{ $tenant->slug }}/terminal/login`;
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
                            // Shift closed successfully
                        } else {
                            // Failed to close shift, but proceeding with logout
                        }
                    } catch (error) {
                        // Error closing shift, but proceeding with logout
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
