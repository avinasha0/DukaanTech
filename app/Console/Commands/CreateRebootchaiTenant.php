<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\TerminalUser;
use Illuminate\Console\Command;

class CreateRebootchaiTenant extends Command
{
    protected $signature = 'tenant:create-dukaantech';
    protected $description = 'Create dukaantech tenant with terminal users';

    public function handle()
    {
        // Check if tenant already exists
        $tenant = Account::where('slug', 'dukaantech')->first();
        
        if ($tenant) {
            $this->info('Tenant "dukaantech" already exists!');
            $this->info('Tenant ID: ' . $tenant->id);
            $this->info('Tenant Name: ' . $tenant->name);
            return;
        }

        // Create the tenant
        $tenant = Account::create([
            'name' => 'Dukaantech Restaurant',
            'slug' => 'dukaantech',
            'plan' => 'free',
            'kot_enabled' => true,
        ]);

        $this->info('Created tenant: ' . $tenant->name . ' (slug: ' . $tenant->slug . ')');

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