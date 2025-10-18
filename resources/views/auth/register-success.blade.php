<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-red-50 min-h-screen">
    {{-- Header Component (inline for now, to be replaced by <x-header />) --}}
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
                <a href="/#features" class="text-gray-700 hover:text-orange-600 transition-colors">Features</a>
                <a href="/#pricing" class="text-gray-700 hover:text-orange-600 transition-colors">Pricing</a>
                <a href="/#testimonials" class="text-gray-700 hover:text-orange-600 transition-colors">Reviews</a>
                <a href="/#contact" class="text-gray-700 hover:text-orange-600 transition-colors">Contact</a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="/login" class="text-gray-700 hover:text-orange-600 transition-colors">Login</a>
                <a href="/register" class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
                    Start Free Trial
                </a>
            </div>
        </div>
    </div>

    {{-- Success Message Section --}}
    <div class="flex items-center justify-center py-20">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Registration Successful!</h1>
            <p class="text-gray-600 mb-6">
                We've sent a verification email to <strong>{{ session('email') }}</strong>. 
                Please check your inbox and click the verification link to activate your account.
            </p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium">Don't see the email?</p>
                        <p>Check your spam folder or <a href="#" class="underline">resend verification email</a></p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <a href="/login" class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-4 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:-translate-y-0.5 block">
                    Go to Login
                </a>
                <a href="/" class="w-full border-2 border-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:border-orange-500 hover:text-orange-600 transition-all block">
                    Back to Homepage
                </a>
            </div>
        </div>
    </div>

    {{-- Footer Component --}}
    <x-footer />
</body>
</html>