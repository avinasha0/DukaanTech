<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-roles');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $tenant = Auth::user()->tenant;
        $roles = Role::where('tenant_id', $tenantId)
            ->with('permissions')
            ->paginate(10);

        return view('tenant.roles.index', compact('roles', 'tenant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenant = Auth::user()->tenant;
        $permissions = Permission::active()->get()->groupBy('module');
        return view('tenant.roles.create', compact('permissions', 'tenant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Ensure role name is unique per tenant to avoid duplicates
                Rule::unique('roles', 'name')->where(fn ($q) => $q->where('tenant_id', $tenantId)),
            ],
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Derive slug and ensure uniqueness per tenant as well
        $slug = Str::slug($request->name);

        // Optional: guard duplicate slug per tenant before hitting DB unique indexes
        $existingWithSlug = Role::where('tenant_id', $tenantId)->where('slug', $slug)->exists();
        if ($existingWithSlug) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A role with this name already exists for this location.');
        }

        $role = Role::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'tenant_id' => $tenantId,
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('tenant.roles.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($tenant, Role $role)
    {
        $tenant = Auth::user()->tenant;
        if ($role->tenant_id !== $tenant->id) {
            abort(404);
        }
        $role->load('permissions', 'users');
        return view('tenant.roles.show', compact('role', 'tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($tenant, Role $role)
    {
        $tenant = Auth::user()->tenant;
        if ($role->tenant_id !== $tenant->id) {
            abort(404);
        }
        $permissions = Permission::active()->get()->groupBy('module');
        $role->load('permissions');
        return view('tenant.roles.edit', compact('role', 'permissions', 'tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $tenant, Role $role)
    {
        $tenant = Auth::user()->tenant;
        if ($role->tenant_id !== $tenant->id) {
            abort(404);
        }
        $tenantId = Auth::user()->tenant_id;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->where(fn ($q) => $q->where('tenant_id', $tenantId))
                    ->ignore($role->id),
            ],
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $newSlug = Str::slug($request->name);

        // Guard duplicate slug per tenant (excluding current role)
        $slugTaken = Role::where('tenant_id', $tenantId)
            ->where('slug', $newSlug)
            ->where('id', '!=', $role->id)
            ->exists();
        if ($slugTaken) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A role with this name already exists for this location.');
        }

        $role->update([
            'name' => $request->name,
            'slug' => $newSlug,
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('tenant.roles.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tenant, Role $role)
    {
        $tenant = Auth::user()->tenant;
        if ($role->tenant_id !== $tenant->id) {
            abort(404);
        }
        // Prevent deletion of admin role
        if ($role->slug === 'admin') {
            return redirect()->route('tenant.roles.index', ['tenant' => Auth::user()->tenant->slug])
                ->with('error', 'Cannot delete admin role.');
        }

        $role->delete();

        return redirect()->route('tenant.roles.index', ['tenant' => Auth::user()->tenant->slug])
            ->with('success', 'Role deleted successfully.');
    }
}
