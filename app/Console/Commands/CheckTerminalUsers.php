<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\TerminalUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CheckTerminalUsers extends Command
{
    protected $signature = 'terminal:check {tenant_slug=dukaantech}';
    protected $description = 'Check and fix terminal users for a tenant';

    public function handle()
    {
        $tenantSlug = $this->argument('tenant_slug');
        
        $tenant = Account::where('slug', $tenantSlug)->first();
        
        if (!$tenant) {
            $this->error("Tenant '{$tenantSlug}' not found!");
            return;
        }

        $this->info("Checking tenant: {$tenant->name} (ID: {$tenant->id})");

        $users = TerminalUser::where('tenant_id', $tenant->id)->get();
        
        if ($users->count() == 0) {
            $this->warn('No terminal users found for this tenant!');
            $this->info('Creating demo terminal users...');
            
            $this->createDemoUsers($tenant);
            return;
        }

        $this->info("Found {$users->count()} terminal users:");
        
        foreach ($users as $user) {
            $status = $user->is_active ? 'Active' : 'Inactive';
            $this->line("  {$user->terminal_id} - {$user->name} ({$user->role}) - {$status}");
            
            // Test PIN verification
            if ($user->terminal_id === 'CASHIER01') {
                $pinTest = $user->verifyPin('1234');
                $this->line("    PIN '1234' verification: " . ($pinTest ? 'SUCCESS' : 'FAILED'));
            }
        }

        // Check if CASHIER01 exists and is active
        $cashier01 = TerminalUser::where('tenant_id', $tenant->id)
            ->where('terminal_id', 'CASHIER01')
            ->where('is_active', true)
            ->first();

        if (!$cashier01) {
            $this->warn('CASHIER01 not found or inactive!');
            $this->info('Creating CASHIER01...');
            
            TerminalUser::create([
                'tenant_id' => $tenant->id,
                'terminal_id' => 'CASHIER01',
                'name' => 'John Doe',
                'pin' => '1234',
                'role' => 'cashier',
                'is_active' => true,
            ]);
            
            $this->info('CASHIER01 created successfully!');
        }
    }

    private function createDemoUsers($tenant)
    {
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

        $this->info('Created ' . count($terminalUsers) . ' demo terminal users');
    }
}