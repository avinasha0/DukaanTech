<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class AssignOwnerAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:assign-owner-admin {--all : Assign admin role to all account owners}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign admin role to account owners';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminRole = Role::where('slug', 'admin')->first();
        
        if (!$adminRole) {
            $this->error('Admin role not found. Please run the RolePermissionSeeder first.');
            return 1;
        }

        if ($this->option('all')) {
            // Assign admin role to all account owners
            $accounts = Account::with('owner')->get();
            $assigned = 0;
            
            foreach ($accounts as $account) {
                if ($account->owner && !$account->owner->isAdmin()) {
                    $account->owner->assignRole($adminRole);
                    $this->info("Admin role assigned to owner: {$account->owner->name} ({$account->owner->email}) for account: {$account->name}");
                    $assigned++;
                }
            }
            
            $this->info("Total admin roles assigned: {$assigned}");
        } else {
            // Assign admin role to owners without admin role
            $accounts = Account::with('owner')->get();
            $assigned = 0;
            
            foreach ($accounts as $account) {
                if ($account->owner && !$account->owner->isAdmin()) {
                    $account->owner->assignRole($adminRole);
                    $this->info("Admin role assigned to owner: {$account->owner->name} ({$account->owner->email}) for account: {$account->name}");
                    $assigned++;
                }
            }
            
            if ($assigned === 0) {
                $this->info("All account owners already have admin role.");
            } else {
                $this->info("Total admin roles assigned: {$assigned}");
            }
        }

        return 0;
    }
}
