@extends('layouts.tenant')

@section('title', 'Menu Management')

@section('content')
<style>
[x-cloak] { display: none !important; }
</style>
<div class="space-y-6" x-data="menuData()">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-dm">Menu Management</h1>
                <p class="text-gray-600 mt-2">Manage your restaurant menu items, categories, and pricing</p>
            </div>
            <div class="flex space-x-3">
                <button @click="openImportModal" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    Import Menu
                </button>
                <button @click="openAddCategoryModal" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Category
                </button>
                <button @click="openAddItemModal" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Menu Item
                </button>
            </div>
        </div>
    </div>

    {{-- Loading State --}}
    <div x-show="loading" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading menu items...</span>
        </div>
    </div>

    {{-- Menu Content --}}
    <div x-show="!loading" class="space-y-6">
        {{-- Categories --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Categories</h2>
                <span class="text-sm text-gray-500" x-text="`${categories.length} categories`"></span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="category in categories" :key="category.id">
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h3 class="font-medium text-gray-900" x-text="category.name"></h3>
                        <p class="text-sm text-gray-500 mt-1" x-text="`${getItemsInCategory(category.id).length} items`"></p>
                    </div>
                </template>
            </div>
        </div>

        {{-- Menu Items --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Menu Items</h2>
                <span class="text-sm text-gray-500" x-text="`${items.length} items`"></span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <template x-for="item in items" :key="item.id">
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-medium text-gray-900" x-text="item.name"></h3>
                            <div class="flex items-center space-x-1">
                                <span x-show="item.is_veg" class="text-green-600 text-xs font-medium">VEG</span>
                                <span x-show="!item.is_veg" class="text-red-600 text-xs font-medium">NON-VEG</span>
                            </div>
                        </div>
                        <p class="text-lg font-semibold text-gray-900" x-text="`₹${item.price}`"></p>
                        <p class="text-sm text-gray-500 mt-1" x-text="getCategoryName(item.category_id)"></p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-xs px-2 py-1 rounded-full" 
                                  :class="item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                  x-text="item.is_active ? 'Active' : 'Inactive'"></span>
                            <div class="flex space-x-1">
                                <button @click="editItem(item)" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                                <button @click="deleteItem(item)" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Add Category Modal --}}
    <div x-show="showAddCategoryModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full"
     style="z-index: 9998; display: none;">
    <div class="relative top-20 mx-auto p-5 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white modal-content"
         style="z-index: 9999;">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add New Category</h3>
                <button @click="closeAddCategoryModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="createCategory">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" x-model="addCategoryForm.name" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Position</label>
                        <input type="number" x-model="addCategoryForm.position" min="0" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Optional">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to add at the end</p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="closeAddCategoryModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- Add Menu Item Modal --}}
    <div x-show="showAddItemModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full"
         style="z-index: 9998;">
        <div class="relative top-20 mx-auto p-5 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white modal-content"
             style="z-index: 9999;">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New Menu Item</h3>
                    <button @click="closeAddItemModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form @submit.prevent="createItem">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Item Name</label>
                            <input type="text" x-model="addItemForm.name" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price (₹)</label>
                            <input type="number" step="0.01" x-model="addItemForm.price" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select x-model="addItemForm.category_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select Category</option>
                                <template x-for="category in categories" :key="category.id">
                                    <option :value="category.id" x-text="category.name"></option>
                                </template>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SKU (Optional)</label>
                            <input type="text" x-model="addItemForm.sku" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea x-model="addItemForm.description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="addItemForm.is_veg" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Vegetarian</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" x-model="addItemForm.is_active" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" @click="closeAddItemModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Create Menu Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Import Menu Modal --}}
    <div x-show="showImportModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full"
         style="z-index: 9998;"
         @click.self="closeImportModal">
        <div class="relative top-20 mx-auto p-5 border-0 w-full max-w-lg shadow-2xl rounded-2xl bg-white modal-content"
             style="z-index: 9999;">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Import Menu Items</h3>
                    <button @click="closeImportModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-blue-800">Required Excel Format:</h4>
                            <button @click="window.open('/{{ $tenant->slug }}/menu/template', '_blank')" class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors">
                                Download Template
                            </button>
                        </div>
                        <div class="text-xs text-blue-700 space-y-1">
                            <p><strong>Required columns:</strong> Item Name, Price, Category</p>
                            <p><strong>Optional columns:</strong> SKU, Description, Is Vegetarian (true/false), Is Active (true/false)</p>
                            <p><strong>Note:</strong> If category doesn't exist, it will be created automatically</p>
                        </div>
                    </div>
                </div>
                
                <form @submit.prevent="importMenu" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Excel File</label>
                            <input type="file" 
                                   x-ref="fileInput"
                                   accept=".xlsx,.xls"
                                   @change="selectedFileName = $event.target.files[0]?.name || ''"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" 
                                   required>
                        </div>
                        
                        <div x-show="selectedFileName" class="text-sm text-gray-600">
                            <p>Selected file: <span x-text="selectedFileName"></span></p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" @click="closeImportModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit" 
                                :disabled="!selectedFileName || importing" 
                                class="px-6 py-2 bg-purple-600 text-white font-bold text-sm rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed border-2 border-purple-700">
                            <span x-show="!importing" class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                </svg>
                                IMPORT
                            </span>
                            <span x-show="importing" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                IMPORTING...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Item Modal --}}
    <div x-show="showEditModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full"
         style="z-index: 9998;"
         @click.self="closeEditModal()"
         x-ref="editModal"
         x-cloak>
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" x-data="editModalData()">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Item</h3>
                <button @click="closeModal(); $parent.closeEditModal();" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            
            <form @submit.prevent="updateItem()">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Item Name</label>
                        <input type="text" x-model="editForm.name" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price (₹)</label>
                        <input type="number" step="0.01" x-model="editForm.price" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" x-model="editForm.sku" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select x-model="editForm.category_id" 
                                x-init="setTimeout(() => { if(editForm.category_id) { $el.value = editForm.category_id; } }, 100);"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Category</option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea x-model="editForm.description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   x-model="editForm.is_veg" 
                                   class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Vegetarian</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   x-model="editForm.is_active" 
                                   class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="closeModal(); $parent.closeEditModal();" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" 
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full"
         style="z-index: 9998;"
         x-cloak
         x-bind:class="showDeleteModal ? 'block' : 'hidden'">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            
            <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Delete Item</h3>
            <p class="text-sm text-gray-500 text-center mb-4">
                Are you sure you want to delete "<span x-text="deleteForm.name"></span>"?
            </p>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                <div class="flex items-center mb-2">
                    <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <span class="text-sm font-medium text-yellow-800">Warning</span>
                </div>
                <p class="text-xs text-yellow-700">
                    This item may have been used in orders. Deleting it will remove it from the menu, but existing order records will remain intact.
                </p>
            </div>
            
            <p class="text-sm text-gray-500 text-center mb-6">
                This action cannot be undone.
            </p>
            
            <div class="flex justify-center space-x-3">
                <button @click="closeDeleteModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button @click="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Delete Item
                </button>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script>

function editModalData() {
    return {
        editForm: {
            id: null,
            name: '',
            price: 0,
            sku: '',
            category_id: '',
            description: '',
            is_veg: false,
            is_active: true
        },
        categories: [],
        
        init() {
            // Get categories from parent
            this.categories = window.menuApp?.categories || [];
            
            // Watch for changes in editForm and update select element
            this.$watch('editForm.category_id', (newValue) => {
                if (newValue) {
                    this.$nextTick(() => {
                        const selectElement = this.$el.querySelector('select[x-model="editForm.category_id"]');
                        if (selectElement) {
                            selectElement.value = newValue;
                        }
                    });
                }
            });
        },
        
        closeModal() {
            // Close the parent modal
            if (window.menuApp) {
                window.menuApp.showEditModal = false;
            }
            
            // Also force close the modal element
            const modal = document.querySelector('[x-show="showEditModal"]');
            if (modal) {
                modal.style.display = 'none';
                modal.style.visibility = 'hidden';
                modal.style.opacity = '0';
            }
        },
        
        async updateItem() {
            try {
                // Call parent update function
                if (window.menuApp) {
                    // Update the parent's editForm with current modal data
                    window.menuApp.editForm = { ...this.editForm };
                    
                    // Call the parent's updateItem function
                    await window.menuApp.updateItem();
                    
                    // Close the modal after successful update
                    this.closeModal();
                }
            } catch (error) {
                console.error('Error updating item:', error);
            }
        }
    }
}

function menuData() {
    return {
        loading: true,
        categories: [],
        items: [],
        
        // Modal states
        showAddCategoryModal: false,
        showAddItemModal: false,
        showEditModal: false,
        showDeleteModal: false,
        showImportModal: false,
        importing: false,
        selectedFileName: '',
        
        // Form data
        addCategoryForm: {
            name: '',
            position: ''
        },
        
        addItemForm: {
            name: '',
            price: 0,
            category_id: '',
            sku: '',
            description: '',
            is_veg: false,
            is_active: true
        },
        
        editForm: {
            id: null,
            name: '',
            price: 0,
            sku: '',
            category_id: '',
            description: '',
            is_veg: false,
            is_active: true
        },
        
        deleteForm: {
            id: null,
            name: ''
        },
        
        async init() {
            // Make this app globally accessible
            window.menuApp = this;
            
            await this.loadData();
        },
        
        async loadData() {
            try {
                this.loading = true;
                
                // Load categories and items in parallel
                const [categoriesResponse, itemsResponse] = await Promise.all([
                    fetch(`/{{ $tenant->slug }}/api/categories`),
                    fetch(`/{{ $tenant->slug }}/api/items`)
                ]);
                
                if (categoriesResponse.ok) {
                    this.categories = await categoriesResponse.json();
                }
                
                if (itemsResponse.ok) {
                    this.items = await itemsResponse.json();
                }
                
            } catch (error) {
                console.error('Error loading menu data:', error);
            } finally {
                this.loading = false;
            }
        },
        
        getItemsInCategory(categoryId) {
            return this.items.filter(item => item.category_id === categoryId);
        },
        
        getCategoryName(categoryId) {
            const category = this.categories.find(cat => cat.id === categoryId);
            return category ? category.name : 'Unknown';
        },
        
        // Category management functionality
        openAddCategoryModal() {
            this.addCategoryForm = {
                name: '',
                position: ''
            };
            this.showAddCategoryModal = true;
            
            // Force modal visibility
            this.$nextTick(() => {
                const modal = document.querySelector('[x-show="showAddCategoryModal"]');
                if (modal) {
                    modal.style.display = 'block';
                    modal.style.visibility = 'visible';
                    modal.style.opacity = '1';
                    modal.style.zIndex = '9999';
                }
            });
        },
        
        closeAddCategoryModal() {
            this.showAddCategoryModal = false;
            this.addCategoryForm = {
                name: '',
                position: ''
            };
        },
        
        async createCategory() {
            try {
                const formData = {
                    name: this.addCategoryForm.name,
                    position: this.addCategoryForm.position && this.addCategoryForm.position.trim() !== '' ? parseInt(this.addCategoryForm.position) : null
                };
                
                const response = await fetch(`/{{ $tenant->slug }}/categories`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });
                
                if (response.ok) {
                    const newCategory = await response.json();
                    this.categories.push(newCategory);
                    this.closeAddCategoryModal();
                    this.showNotification('Category created successfully!', 'success');
                } else {
                    const error = await response.json();
                    this.showNotification(error.message || 'Failed to create category', 'error');
                }
            } catch (error) {
                console.error('Error creating category:', error);
                this.showNotification('Error creating category', 'error');
            }
        },
        
        // Menu Item management functionality
        openAddItemModal() {
            this.addItemForm = {
                name: '',
                price: 0,
                category_id: '',
                sku: '',
                description: '',
                is_veg: false,
                is_active: true
            };
            this.showAddItemModal = true;
            
            // Force modal visibility
            this.$nextTick(() => {
                const modal = document.querySelector('[x-show="showAddItemModal"]');
                if (modal) {
                    modal.style.display = 'block';
                    modal.style.visibility = 'visible';
                    modal.style.opacity = '1';
                    modal.style.zIndex = '9999';
                }
            });
        },
        
        closeAddItemModal() {
            this.showAddItemModal = false;
            this.addItemForm = {
                name: '',
                price: 0,
                category_id: '',
                sku: '',
                description: '',
                is_veg: false,
                is_active: true
            };
        },
        
        async createItem() {
            try {
                const formData = {
                    name: this.addItemForm.name,
                    price: parseFloat(this.addItemForm.price),
                    category_id: this.addItemForm.category_id,
                    sku: this.addItemForm.sku || null,
                    description: this.addItemForm.description || null,
                    is_veg: this.addItemForm.is_veg,
                    is_active: this.addItemForm.is_active
                };
                
                const response = await fetch(`/api/{{ $tenant->slug }}/public/items`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                if (response.ok) {
                    const newItem = await response.json();
                    this.items.push(newItem);
                    this.closeAddItemModal();
                    this.showNotification('Menu item created successfully!', 'success');
                } else {
                    const error = await response.json();
                    this.showNotification(error.message || 'Failed to create menu item', 'error');
                }
            } catch (error) {
                console.error('Error creating menu item:', error);
                this.showNotification('Error creating menu item', 'error');
            }
        },
        
        // Edit functionality
        editItem(item) {
            this.editForm = {
                id: item.id,
                name: item.name || '',
                price: item.price || 0,
                sku: item.sku || '',
                category_id: item.category_id || '',
                description: item.description || '',
                is_veg: Boolean(item.is_veg),
                is_active: Boolean(item.is_active)
            };
            
            this.showEditModal = true;
            
            // Force modal visibility
            this.$nextTick(() => {
                const modal = document.querySelector('[x-show="showEditModal"]');
                if (modal) {
                    modal.style.display = 'block';
                    modal.style.visibility = 'visible';
                    modal.style.opacity = '1';
                    modal.style.zIndex = '9999';
                }
            });
            
            // Populate the modal's data
            setTimeout(() => {
                const modalElement = document.querySelector('[x-data="editModalData()"]');
                if (modalElement && modalElement._x_dataStack) {
                    const modalData = modalElement._x_dataStack[0];
                    if (modalData) {
                        // Set categories first
                        modalData.categories = [...this.categories];
                        
                        // Then set editForm to trigger watchers
                        modalData.editForm = { ...this.editForm };
                        
                        // Force update the select element
                        setTimeout(() => {
                            const selectElement = modalElement.querySelector('select[x-model="editForm.category_id"]');
                            if (selectElement) {
                                selectElement.value = this.editForm.category_id;
                                selectElement.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        }, 50);
                    }
                }
            }, 200);
        },
        
        closeEditModal() {
            this.showEditModal = false;
            this.editForm = {
                id: null,
                name: '',
                price: 0,
                sku: '',
                category_id: '',
                description: '',
                is_veg: false,
                is_active: true
            };
        },
        
        async updateItem() {
            try {
                const response = await fetch(`/api/{{ $tenant->slug }}/public/items/${this.editForm.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.editForm)
                });
                
                if (response.ok) {
                    const updatedItem = await response.json();
                    
                    // Update the item in the local array
                    const index = this.items.findIndex(item => item.id === this.editForm.id);
                    
                    if (index !== -1) {
                        this.items[index] = { ...this.items[index], ...this.editForm };
                    }
                    
                    // Don't close modal here - let the calling function handle it
                    this.showNotification('Item updated successfully!', 'success');
                } else {
                    const error = await response.json();
                    this.showNotification(error.message || 'Failed to update item', 'error');
                }
            } catch (error) {
                console.error('Error updating item:', error);
                this.showNotification('Error updating item', 'error');
            }
        },
        
        // Delete functionality
        deleteItem(item) {
            this.deleteForm = {
                id: item.id,
                name: item.name
            };
            this.showDeleteModal = true;
            
            // Fallback for modal visibility issues
            this.$nextTick(() => {
                const modal = document.querySelector('[x-show="showDeleteModal"]');
                if (modal) {
                    const rect = modal.getBoundingClientRect();
                    if (rect.width === 0 || rect.height === 0) {
                        this.createWorkingDeleteModal();
                        return;
                    }
                } else {
                    this.createWorkingDeleteModal();
                }
            });
        },
        
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.deleteForm = {
                id: null,
                name: ''
            };
        },
        
        async confirmDelete() {
            try {
                const response = await fetch(`/api/{{ $tenant->slug }}/public/items/${this.deleteForm.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const result = await response.json();
                    
                    // Remove the item from the local array
                    this.items = this.items.filter(item => item.id !== this.deleteForm.id);
                    
                    this.closeDeleteModal();
                    this.showNotification('Item deleted successfully!', 'success');
                } else {
                    const error = await response.json();
                    this.showNotification(error.message || error.error || 'Failed to delete item', 'error');
                }
            } catch (error) {
                this.showNotification('Error deleting item: ' + error.message, 'error');
            }
        },
        
        
        // Create a working delete modal (fallback for Alpine.js issues)
        createWorkingDeleteModal() {
            // Remove existing working modal if any
            const existingModal = document.getElementById('working-delete-modal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Create working modal
            const modal = document.createElement('div');
            modal.id = 'working-delete-modal';
            modal.innerHTML = `
                <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 99999; display: flex; align-items: center; justify-content: center;">
                    <div style="background: white; padding: 20px; border-radius: 8px; max-width: 400px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <div style="width: 48px; height: 48px; background: #fef2f2; border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 24px; height: 24px; color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <h3 style="font-size: 18px; font-weight: 500; color: #111827; margin-bottom: 8px;">Delete Item</h3>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">
                                Are you sure you want to delete "<strong>${this.deleteForm.name || 'this item'}</strong>"?
                            </p>
                            <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px; padding: 12px; margin-bottom: 20px;">
                                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                    <svg style="width: 16px; height: 16px; color: #f59e0b; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <span style="font-size: 14px; font-weight: 500; color: #92400e;">Warning</span>
                                </div>
                                <p style="font-size: 13px; color: #92400e; margin: 0;">
                                    This item may have been used in orders. Deleting it will remove it from the menu, but existing order records will remain intact.
                                </p>
                            </div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                                This action cannot be undone.
                            </p>
                        </div>
                        <div style="display: flex; justify-content: center; gap: 12px;">
                            <button onclick="document.getElementById('working-delete-modal').remove()" style="background: #d1d5db; color: #374151; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">Cancel</button>
                            <button onclick="window.menuApp.confirmDeleteFromWorkingModal()" style="background: #dc2626; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">Delete Item</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        },
        
        // Confirm delete from working modal
        async confirmDeleteFromWorkingModal() {
            await this.confirmDelete();
            document.getElementById('working-delete-modal').remove();
        },
        
        // Import functionality
        openImportModal() {
            this.showImportModal = true;
            this.selectedFileName = '';
            
            // Force show the modal
            this.$nextTick(() => {
                const modal = document.querySelector('[x-show="showImportModal"]');
                if (modal) {
                    modal.style.display = 'block';
                    modal.style.visibility = 'visible';
                    modal.style.opacity = '1';
                }
            });
        },
        
        closeImportModal() {
            this.showImportModal = false;
            this.selectedFileName = '';
            this.importing = false;
            
            // Force close the modal element
            this.$nextTick(() => {
                const modal = document.querySelector('[x-show="showImportModal"]');
                if (modal) {
                    modal.style.display = 'none';
                    modal.style.visibility = 'hidden';
                    modal.style.opacity = '0';
                }
            });
        },
        
        downloadTemplate() {
            window.open(`/{{ $tenant->slug }}/menu/template`, '_blank');
        },
        
        async importMenu() {
            const fileInput = this.$refs.fileInput;
            
            if (!fileInput || !fileInput.files[0]) {
                this.showNotification('Please select a file to import', 'error');
                return;
            }
            
            this.importing = true;
            
            try {
                const formData = new FormData();
                formData.append('file', fileInput.files[0]);
                
                const response = await fetch(`/{{ $tenant->slug }}/menu/import`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                if (response.ok) {
                    const result = await response.json();
                    
                    if (result.success) {
                        // Show detailed success message
                        const details = result.details;
                        let message = `✅ Import completed successfully!\n\n`;
                        message += `📊 Import Statistics:\n`;
                        message += `• Menu items imported: ${details.items_imported}\n`;
                        message += `• Categories created: ${details.categories_created}\n`;
                        
                        if (details.errors > 0) {
                            message += `• Errors: ${details.errors}\n`;
                        }
                        if (details.failures > 0) {
                            message += `• Failures: ${details.failures}\n`;
                        }
                        
                        this.showDetailedNotification(message, 'success');
                        this.closeImportModal();
                        await this.loadData(); // Reload the menu data
                    } else {
                        // Show no data imported message
                        const details = result.details;
                        let message = `❌ No menu items were imported\n\n`;
                        message += `📊 Import Statistics:\n`;
                        message += `• Menu items imported: ${details.items_imported}\n`;
                        message += `• Categories created: ${details.categories_created}\n`;
                        
                        if (details.errors > 0) {
                            message += `• Errors: ${details.errors}\n`;
                        }
                        if (details.failures > 0) {
                            message += `• Failures: ${details.failures}\n`;
                        }
                        
                        this.showDetailedNotification(message, 'error');
                    }
                } else {
                    const error = await response.json();
                    this.showNotification(error.message || 'Failed to import menu items', 'error');
                }
            } catch (error) {
                console.error('Error importing menu:', error);
                this.showNotification('Error importing menu items', 'error');
            } finally {
                this.importing = false;
            }
        },
        
        // Notification system
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        },
        
        showDetailedNotification(message, type = 'info') {
            // Create notification element with multi-line support
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 max-w-md ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            
            // Convert newlines to <br> tags
            const formattedMessage = message.replace(/\n/g, '<br>');
            notification.innerHTML = formattedMessage;
            
            document.body.appendChild(notification);
            
            // Remove notification after 5 seconds (longer for detailed stats)
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }
}
</script>
@endsection