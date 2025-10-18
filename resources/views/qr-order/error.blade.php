<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - QR Order</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <link rel="apple-touch-icon" href="/favicon.png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'royal-purple': '#6E46AE',
                        'tiffany-blue': '#00B6B4'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-6xl mb-4">❌</div>
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Oops! Something went wrong</h1>
            <p class="text-gray-600 mb-6">{{ $message ?? 'An error occurred while loading the menu.' }}</p>
            <button onclick="window.history.back()" 
                    class="bg-royal-purple text-white px-6 py-2 rounded-md hover:bg-purple-700 transition-colors">
                Go Back
            </button>
        </div>
    </div>
</body>
</html>
