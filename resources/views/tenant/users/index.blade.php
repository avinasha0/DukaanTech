@extends('layouts.tenant')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2 font-dm">User Management</h1>
                <p class="text-sm lg:text-base text-gray-600">Manage web users and terminal users</p>
            </div>
            <div class="flex gap-3">
                @if(Auth::user()->hasPermission('assign-roles'))
                    <a href="{{ route('tenant.users.create', ['tenant' => $tenant->slug]) }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Add Web User
                    </a>
                @endif
                <button onclick="openTerminalUserModal()" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Add Terminal User
                </button>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 max-w-4xl mx-auto">
            <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-green-600 mt-0.5 mr-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">Success!</h3>
                        <p class="text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 max-w-4xl mx-auto">
            <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mt-0.5 mr-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800">Error!</h3>
                        <p class="text-red-700 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="bg-white rounded-lg shadow border border-gray-100">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="switchTab('web-users')" 
                        class="tab-button active py-4 px-1 border-b-2 font-medium text-sm" 
                        data-tab="web-users">
                    Web Users
                    <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $users->count() }}</span>
                </button>
                <button onclick="switchTab('terminal-users')" 
                        class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" 
                        data-tab="terminal-users">
                    Terminal Users
                    <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $terminalUsers->count() }}</span>
                </button>
            </nav>
        </div>

        {{-- Web Users Tab Content --}}
        <div id="web-users-content" class="tab-content">
            {{-- Web Users Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-orange-50 to-red-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center">
                                                <span class="text-orange-600 font-semibold text-lg">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-500">No roles assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('tenant.users.edit-roles', ['tenant' => $tenant->slug, 'user' => $user]) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Edit Roles</a>
                                        <a href="{{ route('tenant.users.show', ['tenant' => $tenant->slug, 'user' => $user]) }}" 
                                           class="text-green-600 hover:text-green-800 font-medium transition-colors">View</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 mb-2">No web users found</p>
                                        <p class="text-gray-500 mb-4">Add web users to manage your system.</p>
                                        @if(Auth::user()->hasPermission('assign-roles'))
                                            <a href="{{ route('tenant.users.create', ['tenant' => $tenant->slug]) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                                Add First User
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Terminal Users Tab Content --}}
        <div id="terminal-users-content" class="tab-content hidden">
            {{-- Terminal Users Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-50 to-purple-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($terminalUsers as $terminalUser)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-semibold text-lg">
                                                    {{ strtoupper(substr($terminalUser->terminal_id, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $terminalUser->terminal_id }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($terminalUser->role) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $terminalUser->terminal_id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $terminalUser->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $terminalUser->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $terminalUser->created_at->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <button onclick="editTerminalUser({{ $terminalUser->id }})" 
                                                class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Edit</button>
                                        <form method="POST" action="{{ route('tenant.users.destroy-terminal', ['tenant' => $tenant->slug, 'terminalUser' => $terminalUser]) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this terminal user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition-colors">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 mb-2">No terminal users found</p>
                                        <p class="text-gray-500 mb-4">Add terminal users for POS system access.</p>
                                        <button onclick="openTerminalUserModal()" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            Add First Terminal User
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Terminal User Modal --}}
<div id="terminalUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Add Terminal User</h3>
                <button onclick="closeTerminalUserModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="terminalUserForm" method="POST" action="{{ route('tenant.users.store-terminal', ['tenant' => $tenant->slug]) }}">
                @csrf
                <input type="hidden" id="editMode" name="editMode" value="false">
                <input type="hidden" id="terminalUserId" name="terminalUserId" value="">
                <input type="hidden" id="methodOverride" name="_method" value="POST">
                <div class="mb-4">
                    <label for="terminal_id" class="block text-sm font-medium text-gray-700 mb-2">Terminal ID</label>
                    <input type="text" id="terminal_id" name="terminal_id" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., CASHIER01, MANAGER01">
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., John Doe">
                </div>
                <div class="mb-4">
                    <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">PIN</label>
                    <input type="text" id="pin" name="pin" maxlength="6" pattern="[0-9]+"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., 1234">
                    <p id="pinHelpText" class="text-xs text-gray-500 mt-1">Leave blank to keep current PIN when editing</p>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Role</option>
                        <option value="cashier">Cashier</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" checked
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTerminalUserModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submitButton"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Add Terminal User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-orange-500', 'text-orange-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-content').classList.remove('hidden');
        
        // Add active class to selected button
        const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
        activeButton.classList.add('active', 'border-orange-500', 'text-orange-600');
        activeButton.classList.remove('border-transparent', 'text-gray-500');
    }

    function openTerminalUserModal() {
        // Reset form for create mode
        document.getElementById('editMode').value = 'false';
        document.getElementById('terminalUserId').value = '';
        document.getElementById('methodOverride').value = 'POST';
        document.getElementById('modalTitle').textContent = 'Add Terminal User';
        document.getElementById('submitButton').textContent = 'Add Terminal User';
        document.getElementById('terminalUserForm').action = '{{ route("tenant.users.store-terminal", ["tenant" => $tenant->slug]) }}';
        document.getElementById('terminalUserForm').reset();
        document.getElementById('is_active').checked = true;
        
        // Set PIN as required for create mode
        document.getElementById('pin').required = true;
        document.getElementById('pin').placeholder = 'e.g., 1234';
        document.getElementById('pinHelpText').textContent = 'Required for new terminal users';
        
        document.getElementById('terminalUserModal').classList.remove('hidden');
    }

    function closeTerminalUserModal() {
        document.getElementById('terminalUserModal').classList.add('hidden');
        document.getElementById('terminalUserForm').reset();
    }

    function editTerminalUser(userId) {
        // Get terminal user data
        fetch(`/teabench1/users/terminal/${userId}/data`)
            .then(response => response.json())
            .then(data => {
                // Set edit mode
                document.getElementById('editMode').value = 'true';
                document.getElementById('terminalUserId').value = userId;
                document.getElementById('methodOverride').value = 'PUT';
                document.getElementById('modalTitle').textContent = 'Edit Terminal User';
                document.getElementById('submitButton').textContent = 'Update Terminal User';
                document.getElementById('terminalUserForm').action = `{{ route("tenant.users.update-terminal", ["tenant" => $tenant->slug, "terminalUser" => ":id"]) }}`.replace(':id', userId);
                
                // Prefill form data
                document.getElementById('terminal_id').value = data.terminal_id;
                document.getElementById('name').value = data.name;
                document.getElementById('pin').value = ''; // Don't show PIN for security
                document.getElementById('pin').required = false; // Make PIN optional for edit
                document.getElementById('pin').placeholder = 'Leave blank to keep current PIN';
                document.getElementById('pinHelpText').textContent = 'Leave blank to keep current PIN';
                document.getElementById('role').value = data.role;
                document.getElementById('is_active').checked = data.is_active;
                
                // Show modal
                document.getElementById('terminalUserModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching terminal user data:', error);
                alert('Error loading terminal user data');
            });
    }
</script>
@endsection