{{-- resources/views/auth/register-styled.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            
            <nav class="hidden md:flex items-center gap-8">
                <a href="/" class="text-gray-700 hover:text-orange-600 transition-colors">Home</a>
                <a href="/#about" class="text-gray-700 hover:text-orange-600 transition-colors">About Us</a>
                <a href="/#pricing" class="text-gray-700 hover:text-orange-600 transition-colors">Pricing</a>
                <a href="/#contact" class="text-gray-700 hover:text-orange-600 transition-colors">Contact Us</a>
            </nav>
            
            <div class="flex items-center gap-4">
                <a href="/login" class="text-gray-700 hover:text-orange-600 transition-colors">Login</a>
                <a href="/register" class="text-orange-600 font-semibold">Start Free Trial</a>
            </div>
        </div>
    </div>

    {{-- Register Form Section --}}
    <div class="flex items-center justify-center py-20">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl mx-auto mb-6 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h1>
            <p class="text-gray-600">Start your free trial with Dukaantech POS</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block mb-4">
                    <span class="block mb-1 font-semibold text-gray-700">Full Name</span>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full h-11 px-3 border border-gray-300 rounded-xl bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-colors @error('name') border-red-500 @enderror text-[15px] placeholder:text-gray-400" placeholder="Enter your full name">
                </label>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-4">
                    <span class="block mb-1 font-semibold text-gray-700">Email Address</span>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full h-11 px-3 border border-gray-300 rounded-xl bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-colors @error('email') border-red-500 @enderror text-[15px] placeholder:text-gray-400" placeholder="Enter your email">
                </label>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-4">
                    <span class="block mb-1 font-semibold text-gray-700">Restaurant Name</span>
                    <input type="text" name="restaurant_name" value="{{ old('restaurant_name') }}" required class="w-full h-11 px-3 border border-gray-300 rounded-xl bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-colors @error('restaurant_name') border-red-500 @enderror text-[15px] placeholder:text-gray-400" placeholder="Enter your restaurant name">
                </label>
                @error('restaurant_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-4">
                    <span class="block mb-1 font-semibold text-gray-700">Phone Number</span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full h-11 px-3 border border-gray-300 rounded-xl bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-colors @error('phone') border-red-500 @enderror text-[15px] placeholder:text-gray-400" placeholder="Enter your phone number">
                </label>
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-4">
                    <span class="block mb-1 font-semibold text-gray-700">Password</span>
                    <input type="password" name="password" required class="w-full h-11 px-3 border border-gray-300 rounded-xl bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-colors @error('password') border-red-500 @enderror text-[15px] placeholder:text-gray-400" placeholder="Create a password">
                </label>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-4">
                    <span class="block mb-1 font-semibold text-gray-700">Confirm Password</span>
                    <input type="password" name="password_confirmation" required class="w-full h-11 px-3 border border-gray-300 rounded-xl bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-colors text-[15px] placeholder:text-gray-400" placeholder="Confirm your password">
                </label>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label class="text-gray-700">
                        I agree to the 
                        <a href="#" class="text-orange-600 hover:text-orange-500 transition-colors">Terms of Service</a> 
                        and 
                        <a href="#" class="text-orange-600 hover:text-orange-500 transition-colors">Privacy Policy</a>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-4 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                Create Account
            </button>
        </form>

        <div class="mt-8">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
            <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span class="ml-2">Google</span>
            </button>
            <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <span class="ml-2">Facebook</span>
            </button>
        </div>

        <div class="mt-8 text-center">
            <p class="text-gray-600">
                Already have an account? 
                <a href="/login" class="text-orange-600 hover:text-orange-500 font-semibold transition-colors">Sign in here</a>
            </p>
        </div>

        <div class="mt-8 bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 text-center">What you get with your free account:</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs text-gray-700">14-day free trial</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs text-gray-700">Complete POS system</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs text-gray-700">Free setup & training</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs text-gray-700">24/7 customer support</span>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- Footer Component --}}
    <x-footer />
</body>
</html>
