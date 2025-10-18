<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\TerminalUser;
use App\Rules\GmailOnly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-users');
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $tenant = Auth::user()->tenant;
        $users = User::where('tenant_id', $tenantId)
            ->with('roles')
            ->paginate(10);

        // Get terminal users for the same tenant
        $terminalUsers = TerminalUser::where('tenant_id', $tenantId)
            ->orderBy('terminal_id')
            ->get();

        return view('tenant.users.index', compact('users', 'terminalUsers', 'tenant'));
    }

    /**
     * Show create user form.
     */
    public function create()
    {
        $tenant = Auth::user()->tenant;
        $roles = \App\Models\Role::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('tenant.users.create', compact('tenant', 'roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email:rfc,dns', 'unique:users,email', new GmailOnly()],
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            // User model casts password => hashed, so plain assignment is OK
            'password' => $validated['password'],
            'tenant_id' => $tenantId,
        ]);

        if (!empty($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()->route('tenant.users.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing user roles.
     */
    public function editRoles($tenant, User $user)
    {
        $tenantId = Auth::user()->tenant_id;
        $tenant = Auth::user()->tenant;
        if ($user->tenant_id !== $tenantId) {
            abort(404);
        }
        $roles = Role::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->get();

        $user->load('roles');

        return view('tenant.users.edit-roles', compact('user', 'roles', 'tenant'));
    }

    /**
     * Update user roles.
     */
    public function updateRoles(Request $request, $tenant, User $user)
    {
        if ($user->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->syncRoles($request->roles ?? []);

        return redirect()->route('tenant.users.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'User roles updated successfully.');
    }

    /**
     * Show user details.
     */
    public function show($tenant, User $user)
    {
        $tenant = Auth::user()->tenant;
        if ($user->tenant_id !== $tenant->id) {
            abort(404);
        }
        $user->load('roles.permissions');
        return view('tenant.users.show', compact('user', 'tenant'));
    }

    /**
     * Store a newly created terminal user.
     */
    public function storeTerminalUser(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        
        $request->validate([
            'terminal_id' => [
                'required',
                'string',
                'max:20',
                'unique:terminal_users,terminal_id,NULL,id,tenant_id,' . $tenantId
            ],
            'name' => 'required|string|max:255',
            'pin' => 'required|string|min:4|max:6|regex:/^[0-9]+$/',
            'role' => 'required|in:cashier,manager,admin',
        ]);

        TerminalUser::create([
            'tenant_id' => $tenantId,
            'terminal_id' => $request->terminal_id,
            'name' => $request->name,
            'pin' => $request->pin, // Will be hashed automatically by the model
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('tenant.users.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Terminal user created successfully.');
    }

    /**
     * Update terminal user.
     */
    public function updateTerminalUser(Request $request, $tenant, TerminalUser $terminalUser)
    {
        $tenantId = Auth::user()->tenant_id;
        
        // Ensure the terminal user belongs to the current tenant
        if ($terminalUser->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to terminal user.');
        }
        
        $request->validate([
            'terminal_id' => [
                'required',
                'string',
                'max:20',
                \Illuminate\Validation\Rule::unique('terminal_users', 'terminal_id')->ignore($terminalUser->id)->where('tenant_id', $tenantId)
            ],
            'name' => 'required|string|max:255',
            'pin' => 'nullable|string|min:4|max:6|regex:/^[0-9]+$/',
            'role' => 'required|in:cashier,manager,admin',
            'is_active' => 'boolean',
        ]);

        $data = [
            'terminal_id' => $request->terminal_id,
            'name' => $request->name,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active'),
        ];

        // Only update PIN if provided
        if ($request->filled('pin')) {
            $data['pin'] = $request->pin; // Will be hashed automatically by the model
        }

        $terminalUser->update($data);

        return redirect()->route('tenant.users.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Terminal user updated successfully.');
    }

    /**
     * Delete terminal user.
     */
    public function destroyTerminalUser($tenant, TerminalUser $terminalUser)
    {
        $tenantId = Auth::user()->tenant_id;
        
        // Ensure the terminal user belongs to the current tenant
        if ($terminalUser->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to terminal user.');
        }
        
        $terminalUser->delete();

        return redirect()->route('tenant.users.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Terminal user deleted successfully.');
    }

    /**
     * Toggle terminal user status.
     */
    public function toggleTerminalUserStatus($tenant, TerminalUser $terminalUser)
    {
        $tenantId = Auth::user()->tenant_id;
        
        // Ensure the terminal user belongs to the current tenant
        if ($terminalUser->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to terminal user.');
        }
        
        $terminalUser->update(['is_active' => !$terminalUser->is_active]);

        $status = $terminalUser->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('tenant.users.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', "Terminal user {$status} successfully.");
    }
}