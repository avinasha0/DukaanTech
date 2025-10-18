<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Dashboard permissions
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'module' => 'dashboard', 'description' => 'Can view the main dashboard'],
            
            // Order permissions
            ['name' => 'View Orders', 'slug' => 'view-orders', 'module' => 'orders', 'description' => 'Can view orders'],
            ['name' => 'Create Orders', 'slug' => 'create-orders', 'module' => 'orders', 'description' => 'Can create new orders'],
            ['name' => 'Edit Orders', 'slug' => 'edit-orders', 'module' => 'orders', 'description' => 'Can edit existing orders'],
            ['name' => 'Delete Orders', 'slug' => 'delete-orders', 'module' => 'orders', 'description' => 'Can delete orders'],
            ['name' => 'Process Orders', 'slug' => 'process-orders', 'module' => 'orders', 'description' => 'Can process and complete orders'],
            
            // Menu permissions
            ['name' => 'View Menu', 'slug' => 'view-menu', 'module' => 'menu', 'description' => 'Can view menu items'],
            ['name' => 'Create Menu Items', 'slug' => 'create-menu-items', 'module' => 'menu', 'description' => 'Can create new menu items'],
            ['name' => 'Edit Menu Items', 'slug' => 'edit-menu-items', 'module' => 'menu', 'description' => 'Can edit menu items'],
            ['name' => 'Delete Menu Items', 'slug' => 'delete-menu-items', 'module' => 'menu', 'description' => 'Can delete menu items'],
            ['name' => 'Manage Categories', 'slug' => 'manage-categories', 'module' => 'menu', 'description' => 'Can manage menu categories'],
            
            // Reports permissions
            ['name' => 'View Reports', 'slug' => 'view-reports', 'module' => 'reports', 'description' => 'Can view reports'],
            ['name' => 'Export Reports', 'slug' => 'export-reports', 'module' => 'reports', 'description' => 'Can export reports'],
            ['name' => 'View Analytics', 'slug' => 'view-analytics', 'module' => 'reports', 'description' => 'Can view analytics'],
            
            // User management permissions
            ['name' => 'View Users', 'slug' => 'view-users', 'module' => 'users', 'description' => 'Can view users'],
            ['name' => 'Create Users', 'slug' => 'create-users', 'module' => 'users', 'description' => 'Can create new users'],
            ['name' => 'Edit Users', 'slug' => 'edit-users', 'module' => 'users', 'description' => 'Can edit users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users', 'module' => 'users', 'description' => 'Can delete users'],
            ['name' => 'Assign Roles', 'slug' => 'assign-roles', 'module' => 'users', 'description' => 'Can assign roles to users'],
            
            // Role management permissions
            ['name' => 'View Roles', 'slug' => 'view-roles', 'module' => 'roles', 'description' => 'Can view roles'],
            ['name' => 'Create Roles', 'slug' => 'create-roles', 'module' => 'roles', 'description' => 'Can create new roles'],
            ['name' => 'Edit Roles', 'slug' => 'edit-roles', 'module' => 'roles', 'description' => 'Can edit roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete-roles', 'module' => 'roles', 'description' => 'Can delete roles'],
            
            // Settings permissions
            ['name' => 'View Settings', 'slug' => 'view-settings', 'module' => 'settings', 'description' => 'Can view settings'],
            ['name' => 'Edit Settings', 'slug' => 'edit-settings', 'module' => 'settings', 'description' => 'Can edit settings'],
            ['name' => 'Manage Outlets', 'slug' => 'manage-outlets', 'module' => 'settings', 'description' => 'Can manage outlets'],
            ['name' => 'Manage Printers', 'slug' => 'manage-printers', 'module' => 'settings', 'description' => 'Can manage printers'],
            
            // Financial permissions
            ['name' => 'View Financials', 'slug' => 'view-financials', 'module' => 'financials', 'description' => 'Can view financial information'],
            ['name' => 'Process Payments', 'slug' => 'process-payments', 'module' => 'financials', 'description' => 'Can process payments'],
            ['name' => 'Manage Shifts', 'slug' => 'manage-shifts', 'module' => 'financials', 'description' => 'Can manage shifts'],
            ['name' => 'View Cash Flow', 'slug' => 'view-cash-flow', 'module' => 'financials', 'description' => 'Can view cash flow reports'],
            
            // Discount permissions
            ['name' => 'Manage Discounts', 'slug' => 'manage-discounts', 'module' => 'discounts', 'description' => 'Can create and manage discounts'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create roles
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full system access with all permissions',
                'is_active' => true,
            ]
        );

        $managerRole = Role::firstOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Management level access with most permissions',
                'is_active' => true,
            ]
        );

        $cashierRole = Role::firstOrCreate(
            ['slug' => 'cashier'],
            [
                'name' => 'Cashier',
                'slug' => 'cashier',
                'description' => 'Basic POS operations and order processing',
                'is_active' => true,
            ]
        );

        $kitchenRole = Role::firstOrCreate(
            ['slug' => 'kitchen'],
            [
                'name' => 'Kitchen Staff',
                'slug' => 'kitchen',
                'description' => 'Kitchen operations and order management',
                'is_active' => true,
            ]
        );

        // Assign permissions to roles
        $adminRole->syncPermissions(Permission::pluck('id')->toArray());

        $managerPermissions = Permission::whereIn('slug', [
            'view-dashboard', 'view-orders', 'create-orders', 'edit-orders', 'process-orders',
            'view-menu', 'create-menu-items', 'edit-menu-items', 'manage-categories',
            'view-reports', 'export-reports', 'view-analytics',
            'view-users', 'create-users', 'edit-users', 'assign-roles',
            'view-roles', 'create-roles', 'edit-roles',
            'view-settings', 'edit-settings', 'manage-outlets', 'manage-printers',
            'view-financials', 'process-payments', 'manage-shifts', 'view-cash-flow',
            'manage-discounts'
        ])->pluck('id')->toArray();
        $managerRole->syncPermissions($managerPermissions);

        $cashierPermissions = Permission::whereIn('slug', [
            'view-dashboard', 'view-orders', 'create-orders', 'process-orders',
            'view-menu', 'view-reports', 'process-payments'
        ])->pluck('id')->toArray();
        $cashierRole->syncPermissions($cashierPermissions);

        $kitchenPermissions = Permission::whereIn('slug', [
            'view-dashboard', 'view-orders', 'process-orders', 'view-menu'
        ])->pluck('id')->toArray();
        $kitchenRole->syncPermissions($kitchenPermissions);
    }
}
