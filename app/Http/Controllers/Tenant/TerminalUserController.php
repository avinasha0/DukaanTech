<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TerminalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TerminalUserController extends Controller
{
    public function index()
    {
        $tenant = app('tenant');
        $terminalUsers = TerminalUser::where('tenant_id', $tenant->id)
            ->orderBy('terminal_id')
            ->get();
        
        return view('tenant.terminal-users.index', compact('tenant', 'terminalUsers'));
    }

    public function create()
    {
        $tenant = app('tenant');
        return view('tenant.terminal-users.create', compact('tenant'));
    }

    public function show(TerminalUser $terminalUser)
    {
        $tenant = app('tenant');
        
        // Ensure the terminal user belongs to the current tenant
        if ($terminalUser->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to terminal user.');
        }
        
        return view('tenant.terminal-users.show', compact('tenant', 'terminalUser'));
    }

    public function store(Request $request)
    {
        $tenant = app('tenant');
        
        $request->validate([
            'terminal_id' => [
                'required',
                'string',
                'max:20',
                'unique:terminal_users,terminal_id,NULL,id,tenant_id,' . $tenant->id
            ],
            'name' => 'required|string|max:255',
            'pin' => 'required|string|min:4|max:6|regex:/^[0-9]+$/',
            'role' => 'required|in:cashier,manager,admin',
        ]);

        TerminalUser::create([
            'tenant_id' => $tenant->id,
            'terminal_id' => $request->terminal_id,
            'name' => $request->name,
            'pin' => $request->pin, // Will be hashed automatically by the model
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('tenant.terminal-users.index')
            ->with('success', 'Terminal user created successfully.');
    }

    public function edit(TerminalUser $terminalUser)
    {
        $tenant = app('tenant');
        
        // Ensure the terminal user belongs to the current tenant
        if ($terminalUser->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to terminal user.');
        }
        
        return view('tenant.terminal-users.edit', compact('tenant', 'terminalUser'));
    }

    public function update(Request $request, TerminalUser $terminalUser)
    {
        $tenant = app('tenant');
        
        // Ensure the terminal user belongs to the current tenant
        if ($terminalUser->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to terminal user.');
        }
        
        $request->validate([
            'terminal_id' => [
                'required',
                'string',
                'max:20',
                Rule::unique('terminal_users', 'terminal_id')->ignore($terminalUser->id)->where('tenant_id', $tenant->id)
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

        return redirect()->route('tenant.terminal-users.index')
            ->with('success', 'Terminal user updated successfully.');
    }

    public function destroy(TerminalUser $terminalUser)
    {
        $tenant = app('tenant');
        
        // Ensure the terminal user belongs to the current tenant
        if ($terminalUser->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to terminal user.');
        }
        
        $terminalUser->delete();

        return redirect()->route('tenant.terminal-users.index')
            ->with('success', 'Terminal user deleted successfully.');
    }

    public function toggleStatus(TerminalUser $terminalUser)
    {
        $tenant = app('tenant');
        
        // Ensure the terminal user belongs to the current tenant
        if ($terminalUser->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to terminal user.');
        }
        
        $terminalUser->update(['is_active' => !$terminalUser->is_active]);

        $status = $terminalUser->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('tenant.terminal-users.index')
            ->with('success', "Terminal user {$status} successfully.");
    }
}