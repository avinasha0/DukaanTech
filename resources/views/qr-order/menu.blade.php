<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $account->name }} - QR Order Menu</title>
    
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
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-royal-purple">{{ $account->name }}</h1>
                    </div>
                    <div class="text-sm text-gray-500">
                        QR Order Menu
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Order Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Place Your Order</h2>
                
                <form id="orderForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Outlet</label>
                            <select name="outlet_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple">
                                <option value="">Select Outlet</option>
                                @foreach($outlets as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Type</label>
                            <select name="order_type_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple">
                                <option value="">Select Order Type</option>
                                @foreach($orderTypes as $orderType)
                                    <option value="{{ $orderType->id }}">{{ $orderType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select name="payment_method" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple">
                                <option value="">Select Payment</option>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="upi">UPI</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Mode</label>
                            <select name="mode" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple">
                                <option value="">Select Mode</option>
                                <option value="DINE_IN">Dine In</option>
                                <option value="TAKEAWAY">Takeaway</option>
                                <option value="DELIVERY">Delivery</option>
                                <option value="PICKUP">Pickup</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                            <input type="text" name="customer_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" name="customer_phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Special Instructions</label>
                        <textarea name="special_instructions" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-royal-purple focus:border-royal-purple"></textarea>
                    </div>
                </form>
            </div>

            <!-- Menu Categories -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $category->items->count() }} items available</p>
                            
                            <div class="flex justify-between items-center">
                                <button onclick="viewCategory({{ $category->id }})" 
                                        class="bg-royal-purple text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors">
                                    View Items
                                </button>
                                <span class="text-sm text-gray-500">Category</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Section -->
            <div id="cartSection" class="bg-white rounded-lg shadow-md p-6 mt-8 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Order</h3>
                <div id="cartItems" class="space-y-2 mb-4">
                    <!-- Cart items will be added here -->
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold">Total: ₹<span id="cartTotal">0</span></span>
                    <button onclick="placeOrder()" 
                            class="bg-tiffany-blue text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors">
                        Place Order
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script>
        let cart = [];
        const tenantSlug = '{{ $account->slug }}';

        function viewCategory(categoryId) {
            window.location.href = `/${tenantSlug}/qr-order/category/${categoryId}`;
        }

        function addToCart(itemId, itemName, price) {
            const existingItem = cart.find(item => item.item_id === itemId);
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({
                    item_id: itemId,
                    name: itemName,
                    price: price,
                    qty: 1
                });
            }
            updateCartDisplay();
        }

        function removeFromCart(itemId) {
            cart = cart.filter(item => item.item_id !== itemId);
            updateCartDisplay();
        }

        function updateQuantity(itemId, newQty) {
            if (newQty <= 0) {
                removeFromCart(itemId);
                return;
            }
            
            const item = cart.find(item => item.item_id === itemId);
            if (item) {
                item.qty = newQty;
                updateCartDisplay();
            }
        }

        function updateCartDisplay() {
            const cartSection = document.getElementById('cartSection');
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            
            if (cart.length === 0) {
                cartSection.classList.add('hidden');
                return;
            }
            
            cartSection.classList.remove('hidden');
            
            cartItems.innerHTML = cart.map(item => `
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                    <div>
                        <span class="font-medium">${item.name}</span>
                        <span class="text-gray-600">× ${item.qty}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="updateQuantity(${item.item_id}, ${item.qty - 1})" 
                                class="bg-gray-200 text-gray-700 px-2 py-1 rounded">-</button>
                        <span>${item.qty}</span>
                        <button onclick="updateQuantity(${item.item_id}, ${item.qty + 1})" 
                                class="bg-gray-200 text-gray-700 px-2 py-1 rounded">+</button>
                        <span class="font-medium">₹${(item.price * item.qty).toFixed(2)}</span>
                        <button onclick="removeFromCart(${item.item_id})" 
                                class="text-red-600 hover:text-red-800">×</button>
                    </div>
                </div>
            `).join('');
            
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            cartTotal.textContent = total.toFixed(2);
        }

        async function placeOrder() {
            if (cart.length === 0) {
                alert('Please add items to your cart');
                return;
            }

            const formData = new FormData(document.getElementById('orderForm'));
            const orderData = {
                items: cart,
                outlet_id: formData.get('outlet_id') || 1,
                order_type_id: formData.get('order_type_id') || 1,
                payment_method: formData.get('payment_method') || 'cash',
                customer_name: formData.get('customer_name') || null,
                customer_phone: formData.get('customer_phone') || null,
                special_instructions: formData.get('special_instructions') || null,
                mode: formData.get('mode') || 'DINE_IN'
            };

            // Validate required fields
            if (!orderData.outlet_id || !orderData.order_type_id || !orderData.payment_method || !orderData.mode) {
                alert('Please fill in all required fields');
                return;
            }

            console.log('Sending order data:', orderData);

            // Try the main endpoint first, then fallback to simple endpoint
            const endpoints = [
                `/api/${tenantSlug}/public/qr-order/create`,
                `/api/${tenantSlug}/public/simple-order`
            ];
            
            let lastError = null;
            
            for (const endpoint of endpoints) {
                try {
                    console.log('Trying endpoint:', endpoint);
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(orderData)
                    });

                    console.log('Response status:', response.status);
                    
                    // Check if response is ok
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Server error:', response.status, errorText);
                        lastError = `Server error: ${response.status}`;
                        continue; // Try next endpoint
                    }

                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const errorText = await response.text();
                        console.error('Non-JSON response:', errorText);
                        lastError = 'Server returned invalid response';
                        continue; // Try next endpoint
                    }

                    const result = await response.json();
                    console.log('Order result:', result);

                    if (result.success) {
                        alert('Order placed successfully! Order ID: ' + result.order_id);
                        cart = [];
                        updateCartDisplay();
                        document.getElementById('orderForm').reset();
                        return; // Success, exit the function
                    } else {
                        lastError = result.message || 'Unknown error';
                        continue; // Try next endpoint
                    }
                } catch (error) {
                    console.error('Fetch error for', endpoint, ':', error);
                    lastError = error.message;
                    continue; // Try next endpoint
                }
            }
            
            // If we get here, all endpoints failed
            alert('Error placing order: ' + (lastError || 'All endpoints failed'));
        }

        // Make addToCart globally available
        window.addToCart = addToCart;
    </script>
</body>
</html>
