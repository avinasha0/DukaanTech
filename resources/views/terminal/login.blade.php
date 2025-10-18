<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Login - {{ $tenant->name ?? 'POS Terminal' }}</title>
    
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
        .keypad-button {
            transition: all 0.2s ease;
        }
        .keypad-button:active {
            transform: scale(0.95);
            background-color: #e5e7eb;
        }
        .input-focus {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body class="bg-gray-100">
<div x-data="terminalLogin()" x-init="init()" class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl mx-auto mb-6 flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Terminal Login</h1>
            <p class="text-gray-600">{{ $tenant->name ?? 'POS Terminal' }}</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <form @submit.prevent="login" class="space-y-6">
                <!-- Terminal ID Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Terminal ID</label>
                    <input 
                        type="text" 
                        x-model="form.terminal_id"
                        @input="clearError"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-lg font-mono tracking-wider"
                        placeholder="Enter Terminal ID"
                        maxlength="20"
                        autocomplete="off"
                        :class="{ 'border-red-500': error }"
                    >
                </div>

                <!-- PIN Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PIN</label>
                    <input 
                        type="password" 
                        x-model="form.pin"
                        @input="clearError"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-2xl font-mono tracking-widest"
                        placeholder="••••"
                        maxlength="6"
                        autocomplete="off"
                        :class="{ 'border-red-500': error }"
                    >
                </div>

                <!-- reCAPTCHA Widget -->
                <div class="flex justify-center">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>
                <div x-show="recaptchaError" x-text="recaptchaError" class="text-red-600 text-sm text-center bg-red-50 p-3 rounded-lg"></div>

                <!-- Device Selection (if multiple devices) -->
                <div x-show="devices.length > 1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Device</label>
                    <select x-model="form.device_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Auto-select</option>
                        <template x-for="device in devices" :key="device.id">
                            <option :value="device.id" x-text="device.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Server Error Message -->
                @if(session('error'))
                    <div class="text-red-600 text-sm text-center bg-red-50 p-3 rounded-lg mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Client Error Message -->
                <div x-show="error" x-text="error" class="text-red-600 text-sm text-center bg-red-50 p-3 rounded-lg"></div>


                <!-- Loading State -->
                <div x-show="loading" class="text-center">
                    <div class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Logging in...
                    </div>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    :disabled="loading || !form.terminal_id || !form.pin"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                >
                    <span x-show="!loading">Login to Terminal</span>
                    <span x-show="loading">Logging in...</span>
                </button>
            </form>

            <!-- Numeric Keypad -->
            <div class="mt-8">
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="i in [1,2,3,4,5,6,7,8,9,0]" :key="i">
                        <button 
                            @click="addDigit(i)"
                            class="keypad-button bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-4 px-4 rounded-lg text-xl"
                        >
                            <span x-text="i"></span>
                        </button>
                    </template>
                    <button 
                        @click="clearInput"
                        class="keypad-button bg-red-100 hover:bg-red-200 text-red-800 font-bold py-4 px-4 rounded-lg text-xl"
                    >
                        Clear
                    </button>
                    <button 
                        @click="backspace"
                        class="keypad-button bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-bold py-4 px-4 rounded-lg text-xl"
                    >
                        ⌫
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-500 text-sm">
            <p>Enter your Terminal ID and PIN to access the POS system</p>
        </div>
    </div>
</div>

<script>
function terminalLogin() {
    return {
        form: {
            terminal_id: '',
            pin: '',
            device_id: ''
        },
        devices: @json($devices),
        error: '',
        recaptchaError: '',
        loading: false,
        currentInput: 'terminal_id',

        init() {
            // Focus on terminal ID input
            this.$nextTick(() => {
                this.focusInput('terminal_id');
            });
        },

        focusInput(field) {
            this.currentInput = field;
            this.$nextTick(() => {
                const input = this.$el.querySelector(`input[name="${field}"], input[type="${field === 'pin' ? 'password' : 'text'}"]`);
                if (input) {
                    input.focus();
                }
            });
        },

        addDigit(digit) {
            if (this.currentInput === 'terminal_id') {
                if (this.form.terminal_id.length < 20) {
                    this.form.terminal_id += digit;
                }
            } else if (this.currentInput === 'pin') {
                if (this.form.pin.length < 6) {
                    this.form.pin += digit;
                }
            }
            this.clearError();
        },

        clearInput() {
            if (this.currentInput === 'terminal_id') {
                this.form.terminal_id = '';
            } else if (this.currentInput === 'pin') {
                this.form.pin = '';
            }
            this.clearError();
        },

        backspace() {
            if (this.currentInput === 'terminal_id') {
                this.form.terminal_id = this.form.terminal_id.slice(0, -1);
            } else if (this.currentInput === 'pin') {
                this.form.pin = this.form.pin.slice(0, -1);
            }
            this.clearError();
        },

        clearError() {
            this.error = '';
            this.recaptchaError = '';
        },


        async login() {
            if (!this.form.terminal_id || !this.form.pin) {
                this.error = 'Please enter both Terminal ID and PIN';
                return;
            }

            // Check reCAPTCHA
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                this.recaptchaError = 'Please complete the reCAPTCHA verification';
                return;
            }

            this.loading = true;
            this.error = '';
            this.recaptchaError = '';

            try {
                const formData = {
                    ...this.form,
                    'g-recaptcha-response': recaptchaResponse
                };

                const response = await fetch(`/{{ $tenant->slug }}/terminal/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    // Store session info
                    localStorage.setItem('terminal_user', JSON.stringify(data.user));
                    localStorage.setItem('terminal_session_token', data.session_token);
                    
                    // Debug: Log the stored data
                    console.log('=== LOGIN SUCCESS DEBUG ===');
                    console.log('User data:', data.user);
                    console.log('Session token:', data.session_token);
                    console.log('Stored in localStorage:', {
                        user: localStorage.getItem('terminal_user'),
                        token: localStorage.getItem('terminal_session_token')
                    });
                    
                    // Redirect to shift opening screen
                    window.location.href = `/{{ $tenant->slug }}/pos/shift-open`;
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        if (data.errors.terminal_id) {
                            this.error = data.errors.terminal_id[0];
                        }
                        if (data.errors['g-recaptcha-response']) {
                            this.recaptchaError = data.errors['g-recaptcha-response'][0];
                            grecaptcha.reset();
                        }
                    } else {
                        this.error = data.message || 'Login failed';
                    }
                }
            } catch (error) {
                console.error('Login error:', error);
                this.error = 'Network error. Please try again.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

{{-- reCAPTCHA Script --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
