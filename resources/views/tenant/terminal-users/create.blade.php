@extends('layouts.tenant')

@section('title', 'Create Terminal User - ' . $tenant->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create Terminal User</h1>
        <p class="text-gray-600 mt-1">Add a new terminal user for POS login</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('tenant.terminal-users.store') }}" class="space-y-6">
            @csrf
            
            <!-- Terminal ID -->
            <div>
                <label for="terminal_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Terminal ID <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="terminal_id" 
                       name="terminal_id" 
                       value="{{ old('terminal_id') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('terminal_id') border-red-500 @enderror"
                       placeholder="e.g., CASHIER01, MANAGER01"
                       maxlength="20"
                       required>
                @error('terminal_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Unique identifier for this terminal user (max 20 characters)</p>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       placeholder="e.g., John Doe"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- PIN -->
            <div>
                <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">
                    PIN <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       id="pin" 
                       name="pin" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pin') border-red-500 @enderror"
                       placeholder="Enter 4-6 digit PIN"
                       minlength="4"
                       maxlength="6"
                       pattern="[0-9]+"
                       required>
                @error('pin')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">4-6 digit numeric PIN for terminal login</p>
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select id="role" 
                        name="role" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror"
                        required>
                    <option value="">Select a role</option>
                    <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="mt-2 text-sm text-gray-500">
                    <p><strong>Cashier:</strong> Basic POS operations</p>
                    <p><strong>Manager:</strong> All operations + reports</p>
                    <p><strong>Admin:</strong> Full system access + user management</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('tenant.terminal-users.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Create Terminal User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
