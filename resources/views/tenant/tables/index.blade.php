<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Tables - {{ $tenant->name }} - Dukaantech POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-dm { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-red-50 min-h-screen">
    {{-- Header Component --}}
    <div class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 py-4">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg lg:text-xl font-bold text-gray-900">{{ $tenant->name }}</span>
                        <span class="text-sm text-gray-600 ml-1">POS</span>
                    </div>
                </a>

                {{-- Mobile Navigation Menu --}}
                <div x-data="{ mobileMenuOpen: false }" class="lg:hidden w-full">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Menu</span>
                        <svg class="w-5 h-5 text-gray-500" :class="mobileMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="mobileMenuOpen" x-transition class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                        <nav class="p-4 space-y-3">
                            @php
                                $isPathBased = !str_contains(request()->getHost(), '.');
                                $dashboardRoute = 'tenant.dashboard';
                                $posRoute = 'tenant.pos.terminal';
                                $kotRoute = 'tenant.kot.public';
                            @endphp
                            <a href="{{ route($dashboardRoute, ['tenant' => $tenant->slug]) }}" class="block text-gray-700 hover:text-orange-600 transition-colors py-2">Dashboard</a>
                            <a href="{{ route($posRoute, ['tenant' => $tenant->slug]) }}" target="_blank" class="block text-gray-700 hover:text-orange-600 transition-colors py-2">POS Register</a>
                            <a href="{{ route($kotRoute, ['tenant' => $tenant->slug]) }}" target="_blank" class="block text-gray-700 hover:text-orange-600 transition-colors py-2">KOT Dashboard</a>
                        </nav>
                    </div>
                </div>

                {{-- Desktop Navigation --}}
                <nav class="hidden lg:flex items-center gap-8">
                    @php
                        $isPathBased = !str_contains(request()->getHost(), '.');
                        $dashboardRoute = 'tenant.dashboard';
                        $posRoute = 'tenant.pos.terminal';
                        $kotRoute = 'tenant.kot.public';
                    @endphp
                    <a href="{{ route($dashboardRoute, ['tenant' => $tenant->slug]) }}" class="text-gray-700 hover:text-orange-600 transition-colors">Dashboard</a>
                    <a href="{{ route($posRoute, ['tenant' => $tenant->slug]) }}" target="_blank" class="text-gray-700 hover:text-orange-600 transition-colors">POS Register</a>
                    <a href="{{ route($kotRoute, ['tenant' => $tenant->slug]) }}" target="_blank" class="text-gray-700 hover:text-orange-600 transition-colors">KOT Dashboard</a>
                </nav>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 lg:gap-4 w-full lg:w-auto">
                    <div class="text-sm text-gray-600">
                        Welcome, <span class="font-medium">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->roles->count() > 0)
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-500">Role:</span>
                                @foreach(Auth::user()->roles as $role)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-orange-600 transition-colors text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="min-h-screen py-8">
        <div class="mx-auto max-w-7xl px-4">
            {{-- Page Header --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 font-dm">Manage Tables</h1>
                        <p class="text-gray-600 mt-2">Create and manage your restaurant tables for dine-in orders</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            {{-- Add New Table Section --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Add New Table</h2>
                    <button onclick="toggleAddForm()" id="toggleAddBtn" 
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold hover:bg-orange-700 transition-colors">
                        <span id="toggleAddText">Add Table</span>
                    </button>
                </div>
                
                <div id="addTableForm" class="hidden">
                    <form id="tableForm" onsubmit="handleTableSubmit(event)">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Table Name/Number *</label>
                                <input type="text" id="tableName" name="name" required
                                       placeholder="e.g., Table 1, A1, VIP-1" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Table Capacity</label>
                                <input type="number" id="tableCapacity" name="capacity" min="1" max="20"
                                       placeholder="Number of seats" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </div>
                        
                        {{-- Table Shape Selection --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Table Shape</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                {{-- Round Table --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="tableShape" value="round" class="sr-only" checked>
                                    <div class="border-2 border-gray-300 rounded-full p-4 text-center hover:border-orange-500 transition-colors table-shape-option" data-shape="round">
                                        <div class="w-10 h-10 mx-auto mb-2">
                                            <svg class="w-full h-full text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <circle cx="12" cy="12" r="8" fill="currentColor" fill-opacity="0.1"/>
                                                <circle cx="12" cy="12" r="8"/>
                                                <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                                <circle cx="8" cy="8" r="1" fill="currentColor"/>
                                                <circle cx="16" cy="8" r="1" fill="currentColor"/>
                                                <circle cx="8" cy="16" r="1" fill="currentColor"/>
                                                <circle cx="16" cy="16" r="1" fill="currentColor"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-600 font-medium">Round</span>
                                    </div>
                                </label>
                                
                                {{-- Rectangular Table --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="tableShape" value="rectangular" class="sr-only">
                                    <div class="border-2 border-gray-300 rounded-lg p-4 text-center hover:border-orange-500 transition-colors table-shape-option" data-shape="rectangular">
                                        <div class="w-10 h-8 mx-auto mb-2">
                                            <svg class="w-full h-full text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <rect x="4" y="6" width="16" height="12" rx="2" fill="currentColor" fill-opacity="0.1"/>
                                                <rect x="4" y="6" width="16" height="12" rx="2"/>
                                                <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                                                <circle cx="7" cy="9" r="1" fill="currentColor"/>
                                                <circle cx="17" cy="9" r="1" fill="currentColor"/>
                                                <circle cx="7" cy="15" r="1" fill="currentColor"/>
                                                <circle cx="17" cy="15" r="1" fill="currentColor"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-600 font-medium">Rectangular</span>
                                    </div>
                                </label>
                                
                                {{-- Oval Table --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="tableShape" value="oval" class="sr-only">
                                    <div class="border-2 border-gray-300 rounded-full p-4 text-center hover:border-orange-500 transition-colors table-shape-option" data-shape="oval">
                                        <div class="w-10 h-8 mx-auto mb-2">
                                            <svg class="w-full h-full text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <ellipse cx="12" cy="12" rx="10" ry="6" fill="currentColor" fill-opacity="0.1"/>
                                                <ellipse cx="12" cy="12" rx="10" ry="6"/>
                                                <ellipse cx="12" cy="12" rx="3" ry="2" fill="currentColor"/>
                                                <circle cx="6" cy="8" r="1" fill="currentColor"/>
                                                <circle cx="18" cy="8" r="1" fill="currentColor"/>
                                                <circle cx="6" cy="16" r="1" fill="currentColor"/>
                                                <circle cx="18" cy="16" r="1" fill="currentColor"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-600 font-medium">Oval</span>
                                    </div>
                                </label>
                                
                                {{-- Square Table --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="tableShape" value="square" class="sr-only">
                                    <div class="border-2 border-gray-300 rounded-lg p-4 text-center hover:border-orange-500 transition-colors table-shape-option" data-shape="square">
                                        <div class="w-10 h-10 mx-auto mb-2">
                                            <svg class="w-full h-full text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path d="M4 8h16v8H4z" fill="currentColor" fill-opacity="0.1"/>
                                                <path d="M4 8h16v8H4z"/>
                                                <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                                                <circle cx="6" cy="6" r="1" fill="currentColor"/>
                                                <circle cx="18" cy="6" r="1" fill="currentColor"/>
                                                <circle cx="6" cy="18" r="1" fill="currentColor"/>
                                                <circle cx="18" cy="18" r="1" fill="currentColor"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-600 font-medium">Square</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Table Type</label>
                                <select id="tableType" name="type" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <option value="standard">Standard</option>
                                    <option value="booth">Booth</option>
                                    <option value="bar">Bar</option>
                                    <option value="outdoor">Outdoor</option>
                                    <option value="private">Private Room</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="tableStatus" name="status" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <option value="free">Not Occupied</option>
                                    <option value="occupied">Occupied</option>
                                    <option value="reserved">Reserved</option>
                                    <option value="maintenance">Under Maintenance</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="tableDescription" name="description" rows="3"
                                      placeholder="Optional description or special notes about this table"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none"></textarea>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="submit" id="submitBtn"
                                    class="px-6 py-3 bg-orange-600 text-white rounded-lg font-semibold hover:bg-orange-700 transition-colors">
                                <span id="submitText">Add Table</span>
                            </button>
                            <button type="button" onclick="resetForm()" 
                                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                                Reset
                            </button>
                            <button type="button" onclick="cancelEdit()" id="cancelBtn" class="hidden px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
                <p class="text-sm text-gray-500 mt-2">Tables will appear in your POS terminal for dine-in orders</p>
            </div>

            {{-- Tables Grid --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <h2 class="text-xl font-semibold text-gray-900">Your Tables</h2>
                        <div class="flex gap-2">
                            <button onclick="toggleViewMode('grid')" id="gridViewBtn" 
                                    class="p-2 rounded-lg bg-orange-100 text-orange-600 hover:bg-orange-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button onclick="toggleViewMode('list')" id="listViewBtn" 
                                    class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span id="tableCount">0</span> tables created
                    </div>
                </div>
                
                {{-- Grid View --}}
                <div id="gridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <!-- Tables will be dynamically added here -->
                </div>
                
                {{-- List View --}}
                <div id="listView" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Table</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Type</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Capacity</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Current Total</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Created</th>
                                    <th class="text-center py-3 px-4 font-semibold text-gray-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="listViewBody">
                                <!-- Table rows will be dynamically added here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Empty State --}}
                <div id="emptyState" class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No tables created yet</h3>
                    <p class="text-gray-600 mb-4">Create your first table to start managing dine-in orders</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Component --}}
    <x-footer />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        // Global variables
        let currentEditingTable = null;
        let currentViewMode = 'grid';

        // Simple function to toggle add form
        function toggleAddForm() {
            const form = document.getElementById('addTableForm');
            const toggleBtn = document.getElementById('toggleAddBtn');
            const toggleText = document.getElementById('toggleAddText');
            
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                toggleText.textContent = 'Hide Form';
                toggleBtn.classList.remove('bg-orange-600');
                toggleBtn.classList.add('bg-gray-600');
            } else {
                form.classList.add('hidden');
                toggleText.textContent = 'Add Table';
                toggleBtn.classList.remove('bg-gray-600');
                toggleBtn.classList.add('bg-orange-600');
                resetForm();
            }
        }

        // Handle form submission
        function handleTableSubmit(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const tableData = {
                name: formData.get('name').trim(),
                capacity: parseInt(formData.get('capacity')) || 4,
                shape: formData.get('tableShape'),
                type: formData.get('type'),
                status: formData.get('status'),
                description: formData.get('description').trim(),
                totalAmount: 0,
                orders: [],
                createdAt: new Date().toISOString()
            };

            if (currentEditingTable) {
                updateTable(currentEditingTable.id, tableData);
            } else {
                addTable(tableData);
            }
        }

        function addTable(tableData) {
            
            fetch('/{{ $tenant->slug }}/api/tables', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(tableData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                
                resetForm();
                loadTables();
                alert('Table created successfully!');
            })
            .catch(error => {
                console.error('Error creating table:', error);
                alert('Error creating table. Please try again.');
            });
        }

        function updateTable(tableId, tableData) {
            
            fetch(`/{{ $tenant->slug }}/api/tables/${tableId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(tableData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                
                resetForm();
                loadTables();
                alert('Table updated successfully!');
            })
            .catch(error => {
                console.error('Error updating table:', error);
                alert('Error updating table. Please try again.');
            });
        }

        function editTable(tableId) {
            const table = window.currentTables ? window.currentTables.find(t => t.id === tableId) : null;
            
            if (table) {
                currentEditingTable = table;
                
                document.getElementById('tableName').value = table.name;
                document.getElementById('tableCapacity').value = table.capacity || 4;
                document.getElementById('tableType').value = table.type || 'standard';
                document.getElementById('tableStatus').value = table.status || 'free';
                document.getElementById('tableDescription').value = table.description || '';
                
                // Set shape selection
                const shapeRadio = document.querySelector(`input[name="tableShape"][value="${table.shape || 'round'}"]`);
                if (shapeRadio) {
                    shapeRadio.checked = true;
                    updateShapeSelection(table.shape || 'round');
                }
                
                document.getElementById('submitText').textContent = 'Update Table';
                document.getElementById('toggleAddText').textContent = 'Edit Table';
                document.getElementById('cancelBtn').classList.remove('hidden');
                
                document.getElementById('addTableForm').classList.remove('hidden');
                document.getElementById('addTableForm').scrollIntoView({ behavior: 'smooth' });
            }
        }

        function cancelEdit() {
            resetForm();
            document.getElementById('addTableForm').classList.add('hidden');
        }

        function resetForm() {
            currentEditingTable = null;
            document.getElementById('tableForm').reset();
            document.getElementById('submitText').textContent = 'Add Table';
            document.getElementById('toggleAddText').textContent = 'Add Table';
            document.getElementById('cancelBtn').classList.add('hidden');
        }

        function viewTable(tableId) {
            const table = window.currentTables ? window.currentTables.find(t => t.id === tableId) : null;
            
            if (table) {
                alert(`Table: ${table.name}\nType: ${table.type || 'Standard'}\nCapacity: ${table.capacity || 4} seats\nStatus: ${table.status === 'occupied' ? 'Occupied' : 'Not Occupied'}\nCreated: ${new Date(table.created_at).toLocaleDateString()}`);
            } else {
                alert('Table not found');
            }
        }

        function toggleTableStatus(tableId) {
            
            // Find the current table to get its current status
            const currentTable = window.currentTables ? window.currentTables.find(t => t.id === tableId) : null;
            if (!currentTable) {
                alert('Table not found');
                return;
            }
            
            const newStatus = currentTable.status === 'occupied' ? 'free' : 'occupied';
            const updateData = {
                status: newStatus,
                total_amount: newStatus === 'free' ? 0 : currentTable.total_amount
            };
            
            fetch(`/{{ $tenant->slug }}/api/tables/${tableId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(updateData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                
                loadTables();
                alert(`Table ${data.name} is now ${data.status === 'occupied' ? 'Occupied' : 'Not Occupied'}`);
            })
            .catch(error => {
                console.error('Error updating table status:', error);
                alert('Error updating table status. Please try again.');
            });
        }

        function deleteTable(tableId) {
            if (confirm('Are you sure you want to delete this table?')) {
                
                fetch(`/{{ $tenant->slug }}/api/tables/${tableId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    loadTables();
                    alert('Table deleted successfully!');
                })
                .catch(error => {
                    console.error('Error deleting table:', error);
                    alert('Error deleting table. Please try again.');
                });
            }
        }

        function toggleViewMode(mode) {
            currentViewMode = mode;
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');
            
            if (mode === 'grid') {
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');
                gridBtn.classList.add('bg-orange-100', 'text-orange-600');
                gridBtn.classList.remove('text-gray-400');
                listBtn.classList.remove('bg-orange-100', 'text-orange-600');
                listBtn.classList.add('text-gray-400');
            } else {
                gridView.classList.add('hidden');
                listView.classList.remove('hidden');
                listBtn.classList.add('bg-orange-100', 'text-orange-600');
                listBtn.classList.remove('text-gray-400');
                gridBtn.classList.remove('bg-orange-100', 'text-orange-600');
                gridBtn.classList.add('text-gray-400');
            }
            
            loadTables();
        }

        function loadTables() {
            const tableCount = document.getElementById('tableCount');
            const emptyState = document.getElementById('emptyState');
            
            // Show loading state
            tableCount.textContent = 'Loading...';
            
            fetch('/{{ $tenant->slug }}/api/tables')
                .then(response => response.json())
                .then(tables => {
                    // Store tables globally for other functions to access
                    window.currentTables = tables;
                    
                    tableCount.textContent = tables.length;
                    
                    if (tables.length === 0) {
                        emptyState.style.display = 'block';
                        document.getElementById('gridView').innerHTML = '';
                        document.getElementById('listViewBody').innerHTML = '';
                        return;
                    }
                    
                    emptyState.style.display = 'none';
                    
                    if (currentViewMode === 'grid') {
                        loadGridView(tables);
                    } else {
                        loadListView(tables);
                    }
                })
                .catch(error => {
                    console.error('Error loading tables:', error);
                    tableCount.textContent = 'Error';
                    emptyState.style.display = 'block';
                    document.getElementById('gridView').innerHTML = '';
                    document.getElementById('listViewBody').innerHTML = '';
                });
        }

        function loadGridView(tables) {
            const grid = document.getElementById('gridView');
            grid.innerHTML = tables.map(table => createTableCard(table)).join('');
        }

        function loadListView(tables) {
            const tbody = document.getElementById('listViewBody');
            tbody.innerHTML = tables.map(table => createTableRow(table)).join('');
        }

        function createTableCard(table) {
            const statusColor = table.status === 'occupied' ? '#ef4444' : '#10b981';
            const statusBg = table.status === 'occupied' ? '#fef2f2' : '#f0fdf4';
            
            return `
                <div class="bg-white border-2 rounded-lg p-6 shadow-sm hover:shadow-md transition-all duration-200"
                     style="border-color: ${statusColor}; background-color: ${statusBg};">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">${table.name}</h3>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: ${statusColor}"></div>
                            <span class="text-sm font-medium" style="color: ${statusColor}">
                                ${table.status === 'occupied' ? 'Occupied' : 'Not Occupied'}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Table Shape Icon -->
                    <div class="flex justify-center mb-4">
                        ${getTableShapeIcon(table.shape || 'square')}
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shape:</span>
                            <span class="font-medium text-gray-900 capitalize">${table.shape || 'Square'}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium text-gray-900 capitalize">${table.type || 'Standard'}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Capacity:</span>
                            <span class="font-medium text-gray-900">${table.capacity || 4} seats</span>
                        </div>
                        ${table.status === 'occupied' ? `
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Current Total:</span>
                            <span class="font-medium text-gray-900">₹${table.totalAmount.toFixed(2)}</span>
                        </div>
                        ` : ''}
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Created:</span>
                            <span class="font-medium text-gray-900">${new Date(table.createdAt).toLocaleDateString()}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="viewTable(${table.id})" 
                                class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            View
                        </button>
                        <button onclick="editTable(${table.id})" 
                                class="flex-1 px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit
                        </button>
                        <button onclick="toggleTableStatus(${table.id})" 
                                class="flex-1 px-3 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                                style="background-color: ${statusColor};">
                            ${table.status === 'occupied' ? 'Free' : 'Occupy'}
                        </button>
                        <button onclick="deleteTable(${table.id})" 
                                class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            `;
        }

        function createTableRow(table) {
            const statusColor = table.status === 'occupied' ? '#ef4444' : '#10b981';
            const statusBg = table.status === 'occupied' ? '#fef2f2' : '#f0fdf4';
            
            return `
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <div class="font-medium text-gray-900">${table.name}</div>
                        ${table.description ? `<div class="text-sm text-gray-500">${table.description}</div>` : ''}
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-900 capitalize">${table.type || 'Standard'}</td>
                    <td class="py-3 px-4 text-sm text-gray-900">${table.capacity || 4} seats</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                              style="background-color: ${statusBg}; color: ${statusColor};">
                            ${table.status === 'occupied' ? 'Occupied' : 'Not Occupied'}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-900">
                        ${table.status === 'occupied' ? `₹${table.totalAmount.toFixed(2)}` : '-'}
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-900">${new Date(table.createdAt).toLocaleDateString()}</td>
                    <td class="py-3 px-4">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="viewTable(${table.id})" 
                                    class="p-1 text-blue-600 hover:text-blue-800 transition-colors">
                                View
                            </button>
                            <button onclick="editTable(${table.id})" 
                                    class="p-1 text-orange-600 hover:text-orange-800 transition-colors">
                                Edit
                            </button>
                            <button onclick="toggleTableStatus(${table.id})" 
                                    class="p-1 transition-colors"
                                    style="color: ${statusColor};">
                                Toggle
                            </button>
                            <button onclick="deleteTable(${table.id})" 
                                    class="p-1 text-gray-600 hover:text-red-600 transition-colors">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        // Load tables when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadTables();
            setupShapeSelection();
        });
        
        // Setup shape selection functionality
        function setupShapeSelection() {
            document.querySelectorAll('.table-shape-option').forEach(option => {
                option.addEventListener('click', function() {
                    const shape = this.dataset.shape;
                    updateShapeSelection(shape);
                });
            });
            
            // Set initial selection
            updateShapeSelection('round');
        }
        
        // Update shape selection visual feedback
        function updateShapeSelection(selectedShape) {
            // Remove selected class from all options
            document.querySelectorAll('.table-shape-option').forEach(opt => {
                opt.classList.remove('border-orange-500', 'bg-orange-50');
                opt.classList.add('border-gray-300');
            });
            
            // Add selected class to clicked option
            const selectedOption = document.querySelector(`.table-shape-option[data-shape="${selectedShape}"]`);
            if (selectedOption) {
                selectedOption.classList.remove('border-gray-300');
                selectedOption.classList.add('border-orange-500', 'bg-orange-50');
            }
            
            // Check the corresponding radio button
            const radio = document.querySelector(`input[name="tableShape"][value="${selectedShape}"]`);
            if (radio) {
                radio.checked = true;
            }
        }
        
        // Get table shape icon based on shape
        function getTableShapeIcon(shape) {
            const iconSize = 'w-12 h-12';
            const iconColor = 'text-gray-600';
            
            switch(shape) {
                case 'round':
                    return `<svg class="${iconSize} ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="8" fill="currentColor" fill-opacity="0.1"/>
                        <circle cx="12" cy="12" r="8"/>
                        <circle cx="12" cy="12" r="2" fill="currentColor"/>
                        <circle cx="8" cy="8" r="1" fill="currentColor"/>
                        <circle cx="16" cy="8" r="1" fill="currentColor"/>
                        <circle cx="8" cy="16" r="1" fill="currentColor"/>
                        <circle cx="16" cy="16" r="1" fill="currentColor"/>
                    </svg>`;
                    
                case 'rectangular':
                    return `<svg class="${iconSize} ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="4" y="6" width="16" height="12" rx="2" fill="currentColor" fill-opacity="0.1"/>
                        <rect x="4" y="6" width="16" height="12" rx="2"/>
                        <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                        <circle cx="7" cy="9" r="1" fill="currentColor"/>
                        <circle cx="17" cy="9" r="1" fill="currentColor"/>
                        <circle cx="7" cy="15" r="1" fill="currentColor"/>
                        <circle cx="17" cy="15" r="1" fill="currentColor"/>
                    </svg>`;
                    
                case 'oval':
                    return `<svg class="${iconSize} ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <ellipse cx="12" cy="12" rx="10" ry="6" fill="currentColor" fill-opacity="0.1"/>
                        <ellipse cx="12" cy="12" rx="10" ry="6"/>
                        <ellipse cx="12" cy="12" rx="3" ry="2" fill="currentColor"/>
                        <circle cx="6" cy="8" r="1" fill="currentColor"/>
                        <circle cx="18" cy="8" r="1" fill="currentColor"/>
                        <circle cx="6" cy="16" r="1" fill="currentColor"/>
                        <circle cx="18" cy="16" r="1" fill="currentColor"/>
                    </svg>`;
                    
                default: // square
                    return `<svg class="${iconSize} ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M4 8h16v8H4z" fill="currentColor" fill-opacity="0.1"/>
                        <path d="M4 8h16v8H4z"/>
                        <rect x="10" y="10" width="4" height="4" fill="currentColor"/>
                        <circle cx="6" cy="6" r="1" fill="currentColor"/>
                        <circle cx="18" cy="6" r="1" fill="currentColor"/>
                        <circle cx="6" cy="18" r="1" fill="currentColor"/>
                        <circle cx="18" cy="18" r="1" fill="currentColor"/>
                    </svg>`;
            }
        }
        
        
        
        function clearAndResetTables() {
            if (confirm('This will clear all existing tables and reset to 3 default tables (T1, T2, T3). Continue?')) {
                // Clear localStorage
                localStorage.removeItem('restaurantTables');
                
                // Create 3 default tables
                const defaultTables = [
                    {
                        id: 1,
                        name: 'T1',
                        status: 'free',
                        capacity: 4,
                        shape: 'round',
                        type: 'standard',
                        description: '',
                        orders: [],
                        totalAmount: 0,
                        createdAt: new Date().toISOString()
                    },
                    {
                        id: 2,
                        name: 'T2',
                        status: 'free',
                        capacity: 6,
                        shape: 'rectangular',
                        type: 'standard',
                        description: '',
                        orders: [],
                        totalAmount: 0,
                        createdAt: new Date().toISOString()
                    },
                    {
                        id: 3,
                        name: 'T3',
                        status: 'free',
                        capacity: 2,
                        shape: 'oval',
                        type: 'standard',
                        description: '',
                        orders: [],
                        totalAmount: 0,
                        createdAt: new Date().toISOString()
                    }
                ];
                
                localStorage.setItem('restaurantTables', JSON.stringify(defaultTables));
                loadTables();
                alert('Tables reset to 3 default tables (T1, T2, T3)');
            }
        }
    </script>
</body>
</html>
