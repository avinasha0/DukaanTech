<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
        <link rel="apple-touch-icon" href="/favicon.png">
        
        @yield('meta')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @if(request()->is('/') || request()->is('product') || request()->is('features') || request()->is('pricing') || request()->is('integrations') || request()->is('api-documentation') || request()->is('mobile-app') || request()->is('about-us') || request()->is('careers') || request()->is('blog') || request()->is('press') || request()->is('partners') || request()->is('help-center') || request()->is('contact-us') || request()->is('training') || request()->is('system-status') || request()->is('community') || request()->is('privacy-policy') || request()->is('terms-of-service') || request()->is('cookie-policy') || request()->is('login') || request()->is('register') || request()->is('forgot-password') || request()->is('reset-password*'))
            @yield('content')
        @else
            <div class="min-h-screen bg-gray-100">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        @endif
    </body>
</html>
