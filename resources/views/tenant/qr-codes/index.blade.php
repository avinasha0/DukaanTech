@extends('layouts.tenant')

@section('title', 'QR Code Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">QR Code Management</h1>
                <p class="text-gray-600">Generate QR codes for your menu, categories, items, and tables</p>
            </div>
        </div>
    </div>


    <!-- Saved QR Codes -->
    @if($savedQRCodes->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Saved QR Codes</h2>
        <p class="text-gray-600 mb-4">Previously generated QR codes that are saved and ready to use</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($savedQRCodes as $qrCode)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-medium text-gray-900">{{ $qrCode->name }}</h3>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($qrCode->type === 'menu') bg-blue-100 text-blue-800
                            @elseif($qrCode->type === 'category') bg-green-100 text-green-800
                            @elseif($qrCode->type === 'item') bg-purple-100 text-purple-800
                            @elseif($qrCode->type === 'table') bg-orange-100 text-orange-800
                            @endif">
                            {{ ucfirst($qrCode->type) }}
                        </span>
                    </div>
                    
                    <div class="w-full h-32 border rounded flex items-center justify-center mb-3 bg-gray-50">
                        <div id="savedQR{{ $qrCode->id }}" class="w-full h-full flex items-center justify-center">
                            <!-- QR code will be loaded here -->
                        </div>
                    </div>
                    
                    <div class="text-xs text-gray-500 mb-3">
                        Created: {{ $qrCode->created_at->format('M d, Y H:i') }}
                    </div>
                    
                    <div class="flex space-x-2">
                        <button onclick="loadSavedQR({{ $qrCode->id }}, '{{ $qrCode->file_path }}')" 
                                class="flex-1 bg-royal-purple text-white px-3 py-2 rounded text-sm hover:bg-purple-700 transition-colors">
                            View
                        </button>
                        <button onclick="downloadSavedQR('{{ $qrCode->file_path }}')" 
                                class="flex-1 bg-tiffany-blue text-white px-3 py-2 rounded text-sm hover:bg-blue-600 transition-colors">
                            Download
                        </button>
                        <button onclick="deleteQR({{ $qrCode->id }})" 
                                class="bg-red-500 text-white px-3 py-2 rounded text-sm hover:bg-red-600 transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Menu QR Code -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Full Menu QR Code</h2>
        <p class="text-gray-600 mb-4">Generate a QR code that links to your complete menu</p>
        
        <div class="flex items-center space-x-4">
            <button onclick="generateMenuQR()" 
                    class="bg-royal-purple text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors">
                Generate QR Code
            </button>
            <div id="menuQRResult" class="hidden">
                <div class="flex items-center space-x-4">
                    <div id="menuQRImage" class="w-32 h-32 border rounded flex items-center justify-center"></div>
                    <div>
                        <button onclick="downloadQR('menuQRImage')" 
                                class="bg-tiffany-blue text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                            Download
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories QR Codes -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Category QR Codes</h2>
        <p class="text-gray-600 mb-4">Generate QR codes for specific categories</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $category)
                <div class="border rounded-lg p-4">
                    <h3 class="font-medium text-gray-900">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $category->items_count }} items</p>
                    
                    <button onclick="generateCategoryQR({{ $category->id }})" 
                            class="w-full bg-royal-purple text-white px-3 py-2 rounded text-sm hover:bg-purple-700 transition-colors">
                        Generate QR
                    </button>
                    
                    <div id="categoryQR{{ $category->id }}" class="hidden mt-3">
                        <div id="categoryQRImage{{ $category->id }}" class="w-24 h-24 border rounded mx-auto flex items-center justify-center"></div>
                        <button onclick="downloadQR('categoryQRImage{{ $category->id }}')" 
                                class="w-full mt-2 bg-tiffany-blue text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                            Download
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Items QR Codes -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Item QR Codes</h2>
        <p class="text-gray-600 mb-4">Generate QR codes for specific items</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($items as $item)
                <div class="border rounded-lg p-4">
                    <h3 class="font-medium text-gray-900">{{ $item->name }}</h3>
                    <p class="text-sm text-gray-600 mb-1">{{ $item->category->name }}</p>
                    <p class="text-sm text-gray-600 mb-3">₹{{ number_format($item->price, 2) }}</p>
                    
                    <button onclick="generateItemQR({{ $item->id }})" 
                            class="w-full bg-royal-purple text-white px-3 py-2 rounded text-sm hover:bg-purple-700 transition-colors">
                        Generate QR
                    </button>
                    
                    <div id="itemQR{{ $item->id }}" class="hidden mt-3">
                        <div id="itemQRImage{{ $item->id }}" class="w-24 h-24 border rounded mx-auto flex items-center justify-center"></div>
                        <button onclick="downloadQR('itemQRImage{{ $item->id }}')" 
                                class="w-full mt-2 bg-tiffany-blue text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                            Download
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Table QR Code Generator -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Table QR Code Generator</h2>
        <p class="text-gray-600 mb-4">Generate QR codes for specific tables</p>
        
        <div class="flex items-center space-x-4">
            <input type="text" id="tableNumber" placeholder="Enter table number" 
                   class="border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple">
            <button onclick="generateTableQR()" 
                    class="bg-royal-purple text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors">
                Generate QR Code
            </button>
        </div>
        
        <div id="tableQRResult" class="hidden mt-4">
            <div class="flex items-center space-x-4">
                <div id="tableQRImage" class="w-32 h-32 border rounded flex items-center justify-center"></div>
                <div>
                    <button onclick="downloadQR('tableQRImage')" 
                            class="bg-tiffany-blue text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                        Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const tenantSlug = '{{ $tenant->slug }}';
    const baseUrl = window.location.origin; // Get the current domain (192.168.29.111:8000)

    async function generateMenuQR() {
        try {
            const response = await fetch(`${baseUrl}/${tenantSlug}/qr-codes/generate-menu`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const result = await response.json();
            
            if (result.qr_url) {
                fetch(result.qr_url)
                    .then(response => response.text())
                    .then(svg => {
                        document.getElementById('menuQRImage').innerHTML = svg;
                        document.getElementById('menuQRResult').classList.remove('hidden');
                    });
                
                // Refresh page to show in saved QR codes list
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Error: ' + result.error);
            }
        } catch (error) {
            alert('Error generating QR code: ' + error.message);
        }
    }

    async function generateCategoryQR(categoryId) {
        try {
            const response = await fetch(`${baseUrl}/${tenantSlug}/qr-codes/generate-category/${categoryId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const result = await response.json();
            
            if (result.qr_url) {
                fetch(result.qr_url)
                    .then(response => response.text())
                    .then(svg => {
                        document.getElementById(`categoryQRImage${categoryId}`).innerHTML = svg;
                        document.getElementById(`categoryQR${categoryId}`).classList.remove('hidden');
                    });
                
                // Refresh page to show in saved QR codes list
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Error: ' + result.error);
            }
        } catch (error) {
            alert('Error generating QR code: ' + error.message);
        }
    }

    async function generateItemQR(itemId) {
        try {
            const response = await fetch(`${baseUrl}/${tenantSlug}/qr-codes/generate-item/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const result = await response.json();
            
            if (result.qr_url) {
                fetch(result.qr_url)
                    .then(response => response.text())
                    .then(svg => {
                        document.getElementById(`itemQRImage${itemId}`).innerHTML = svg;
                        document.getElementById(`itemQR${itemId}`).classList.remove('hidden');
                    });
                
                // Refresh page to show in saved QR codes list
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Error: ' + result.error);
            }
        } catch (error) {
            alert('Error generating QR code: ' + error.message);
        }
    }

    async function generateTableQR() {
        const tableNumber = document.getElementById('tableNumber').value;
        if (!tableNumber) {
            alert('Please enter a table number');
            return;
        }

        try {
            const response = await fetch(`${baseUrl}/${tenantSlug}/qr-codes/generate-table`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ table_no: tableNumber })
            });

            const result = await response.json();
            
            if (result.qr_url) {
                fetch(result.qr_url)
                    .then(response => response.text())
                    .then(svg => {
                        document.getElementById('tableQRImage').innerHTML = svg;
                        document.getElementById('tableQRResult').classList.remove('hidden');
                    });
                
                // Refresh page to show in saved QR codes list
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Error: ' + result.error);
            }
        } catch (error) {
            alert('Error generating QR code: ' + error.message);
        }
    }

    function downloadQR(imageId) {
        const container = document.getElementById(imageId);
        const svg = container.querySelector('svg');
        if (svg) {
            const svgData = new XMLSerializer().serializeToString(svg);
            const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
            const svgUrl = URL.createObjectURL(svgBlob);
            const link = document.createElement('a');
            link.download = 'qr-code.svg';
            link.href = svgUrl;
            link.click();
            URL.revokeObjectURL(svgUrl);
        }
    }


    // Functions for saved QR codes
    async function loadSavedQR(qrId, filePath) {
        try {
            const response = await fetch(`/storage/${filePath}`);
            const svg = await response.text();
            document.getElementById(`savedQR${qrId}`).innerHTML = svg;
        } catch (error) {
            console.error('Error loading QR code:', error);
            document.getElementById(`savedQR${qrId}`).innerHTML = '<span class="text-gray-500 text-sm">Error loading QR code</span>';
        }
    }

    function downloadSavedQR(filePath) {
        const link = document.createElement('a');
        link.href = `/storage/${filePath}`;
        link.download = filePath.split('/').pop();
        link.click();
    }

    async function deleteQR(qrId) {
        console.log('=== QR CODE DELETE JAVASCRIPT START ===');
        console.log('deleteQR called with ID:', qrId, 'Type:', typeof qrId);
        console.log('QR ID value:', qrId);
        console.log('QR ID parsed as int:', parseInt(qrId));
        console.log('Current URL:', window.location.href);
        console.log('Base URL:', baseUrl);
        console.log('Tenant slug:', tenantSlug);
        
        // Validate the QR ID
        if (!qrId || qrId === 'undefined' || qrId === 'null') {
            console.error('Invalid QR code ID:', qrId);
            alert('Invalid QR code ID. Please refresh the page and try again.');
            return;
        }
        
        if (!confirm('Are you sure you want to delete this QR code?')) {
            console.log('User cancelled deletion');
            return;
        }
        
        console.log('User confirmed deletion, proceeding...');

        try {
            console.log('Starting CSRF token retrieval...');
            
            // Get CSRF token from meta tag or cookie
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            console.log('CSRF token from meta tag:', csrfToken ? 'Found' : 'Not found');
            
            // Fallback: try to get from cookie
            if (!csrfToken) {
                console.log('Trying to get CSRF token from cookies...');
                const cookies = document.cookie.split(';');
                console.log('All cookies:', document.cookie);
                for (let cookie of cookies) {
                    const [name, value] = cookie.trim().split('=');
                    console.log('Cookie:', name, '=', value);
                    if (name === 'XSRF-TOKEN') {
                        csrfToken = decodeURIComponent(value);
                        console.log('CSRF token from cookie:', csrfToken);
                        break;
                    }
                }
            }
            
            console.log('Final CSRF Token:', csrfToken);
            console.log('CSRF Token length:', csrfToken.length);
            console.log('Deleting QR code ID:', qrId);
            console.log('QR ID type:', typeof qrId);
            console.log('Tenant slug:', tenantSlug);
            
            // Ensure qrId is a number
            const numericQrId = parseInt(qrId);
            console.log('Numeric QR ID:', numericQrId);
            
            const fullUrl = `${baseUrl}/${tenantSlug}/qr-codes/${numericQrId}/delete`;
            console.log('Full URL:', fullUrl);
            
            // Check if we have a valid CSRF token
            if (!csrfToken || csrfToken.length < 10) {
                console.error('Invalid CSRF token:', csrfToken);
                alert('CSRF token not found or invalid. Please refresh the page and try again.');
                return;
            }
            
            console.log('All validation passed, making fetch request...');

            console.log('Making fetch request with headers:', {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            });

            const response = await fetch(fullUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log('=== FETCH RESPONSE RECEIVED ===');
            console.log('Response status:', response.status);
            console.log('Response status text:', response.statusText);
            console.log('Response ok:', response.ok);
            console.log('Response headers:', response.headers);
            console.log('Response URL:', response.url);

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            console.log('Response content type:', contentType);
            
            if (!contentType || !contentType.includes('application/json')) {
                console.error('=== NON-JSON RESPONSE ERROR ===');
                const text = await response.text();
                console.error('Response text:', text);
                console.error('Response length:', text.length);
                console.error('First 500 chars:', text.substring(0, 500));
                console.error('Response status:', response.status);
                console.error('Response URL:', response.url);
                alert('Server returned HTML instead of JSON. This might be an authentication issue. Check console for details.');
                return;
            }

            console.log('Response is JSON, parsing...');
            const result = await response.json();
            console.log('=== PARSED RESPONSE ===');
            console.log('Response result:', result);
            console.log('Response success:', response.ok);
            
            if (response.ok) {
                console.log('=== DELETE SUCCESS ===');
                console.log('QR code deleted successfully');
                
                // Remove the QR code card from the DOM
                const qrCard = document.querySelector(`[onclick*="loadSavedQR(${qrId}"]`).closest('.border');
                if (qrCard) {
                    console.log('Removing QR code card from DOM');
                    qrCard.remove();
                } else {
                    console.log('QR code card not found in DOM, will reload page');
                }
                
                // Show success message
                alert('QR code deleted successfully');
                
                // Reload page to refresh the list
                console.log('Reloading page...');
                window.location.reload();
            } else {
                console.error('=== DELETE FAILED ===');
                console.error('Response not ok:', response.status, response.statusText);
                console.error('Error details:', result);
                alert('Error: ' + (result.error || 'Unknown error'));
            }
        } catch (error) {
            console.error('=== JAVASCRIPT EXCEPTION ===');
            console.error('Error type:', error.name);
            console.error('Error message:', error.message);
            console.error('Error stack:', error.stack);
            console.error('Full error object:', error);
            alert('Error deleting QR code: ' + error.message);
        }
        
        console.log('=== QR CODE DELETE JAVASCRIPT END ===');
    }
</script>
@endsection
