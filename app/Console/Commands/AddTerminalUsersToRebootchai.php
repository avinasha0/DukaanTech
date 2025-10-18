<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\TerminalUser;
use Illuminate\Console\Command;

class AddTerminalUsersToRebootchai extends Command
{
    protected $signature = 'terminal:add-to-dukaantech';
    protected $description = 'Add terminal users to dukaantech tenant';

    public function handle()
    {
        $tenant = Account::where('slug', 'dukaantech')->first();
        
        if (!$tenant) {
            $this->error('Tenant "dukaantech" not found!');
            return;
        }

        $this->info('Found tenant: ' . $tenant->name . ' (ID: ' . $tenant->id . ')');

        // Check if terminal users already exist
        $existingCount = TerminalUser::where('tenant_id', $tenant->id)->count();
        
        if ($existingCount > 0) {
            $this->info('Terminal users already exist for this tenant (' . $existingCount . ' users)');
            
            // Show existing users
            $users = TerminalUser::where('tenant_id', $tenant->id)->get(['terminal_id', 'name', 'role', 'is_active']);
            $this->info('Existing terminal users:');
            foreach ($users as $user) {
                $status = $user->is_active ? 'Active' : 'Inactive';
                $this->line("  {$user->terminal_id} - {$user->name} ({$user->role}) - {$status}");
            }
            return;
        }

        // Create terminal users
        $terminalUsers = [
            [
                'terminal_id' => 'CASHIER01',
                'name' => 'John Doe',
                'pin' => '1234',
                'role' => 'cashier',
                'is_active' => true,
            ],
            [
                'terminal_id' => 'CASHIER02',
                'name' => 'Jane Smith',
                'pin' => '5678',
                'role' => 'cashier',
                'is_active' => true,
            ],
            [
                'terminal_id' => 'MANAGER01',
                'name' => 'Mike Johnson',
                'pin' => '9999',
                'role' => 'manager',
                'is_active' => true,
            ],
            [
                'terminal_id' => 'ADMIN01',
                'name' => 'Sarah Wilson',
                'pin' => '0000',
                'role' => 'admin',
                'is_active' => true,
            ],
        ];

        foreach ($terminalUsers as $userData) {
            TerminalUser::create([
                'tenant_id' => $tenant->id,
                ...$userData
            ]);
        }

        $this->info('Created ' . count($terminalUsers) . ' terminal users for dukaantech tenant');
        $this->info('');
        $this->info('Terminal Login URL: http://localhost:8000/dukaantech/terminal/login');
        $this->info('');
        $this->info('Demo Credentials:');
        foreach ($terminalUsers as $user) {
            $this->info("  {$user['terminal_id']} - {$user['name']} (PIN: {$user['pin']}) - {$user['role']}");
        }
    }
}