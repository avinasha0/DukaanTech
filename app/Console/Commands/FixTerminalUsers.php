<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\TerminalUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixTerminalUsers extends Command
{
    protected $signature = 'terminal:fix {tenant_slug=dukaantech}';
    protected $description = 'Fix terminal users for a tenant';

    public function handle()
    {
        $tenantSlug = $this->argument('tenant_slug');
        
        $tenant = Account::where('slug', $tenantSlug)->first();
        
        if (!$tenant) {
            $this->error("Tenant '{$tenantSlug}' not found!");
            return;
        }

        $this->info("Fixing terminal users for tenant: {$tenant->name} (ID: {$tenant->id})");

        // First, let's see what terminal users exist globally
        $this->info("All terminal users in database:");
        $allUsers = TerminalUser::with('tenant')->get();
        foreach ($allUsers as $user) {
            $this->line("  {$user->terminal_id} - {$user->name} - Tenant: {$user->tenant->slug} (ID: {$user->tenant_id}) - Active: " . ($user->is_active ? 'Yes' : 'No'));
        }

        // Check if there are terminal users for this specific tenant
        $tenantUsers = TerminalUser::where('tenant_id', $tenant->id)->get();
        $this->info("Terminal users for {$tenant->name}: {$tenantUsers->count()}");

        if ($tenantUsers->count() == 0) {
            $this->warn('No terminal users found for this tenant!');
            
            // Check if there are any terminal users with the same terminal_id but different tenant_id
            $conflictingUsers = TerminalUser::whereIn('terminal_id', ['CASHIER01', 'CASHIER02', 'MANAGER01', 'ADMIN01'])
                ->where('tenant_id', '!=', $tenant->id)
                ->get();

            if ($conflictingUsers->count() > 0) {
                $this->info('Found conflicting terminal users with same IDs but different tenants:');
                foreach ($conflictingUsers as $user) {
                    $this->line("  {$user->terminal_id} - Tenant: {$user->tenant->slug} (ID: {$user->tenant_id})");
                }
                
                $this->info('Creating new terminal users with unique IDs...');
                $this->createUniqueUsers($tenant);
            } else {
                $this->info('Creating demo terminal users...');
                $this->createDemoUsers($tenant);
            }
        } else {
            $this->info('Terminal users already exist for this tenant:');
            foreach ($tenantUsers as $user) {
                $this->line("  {$user->terminal_id} - {$user->name} ({$user->role}) - Active: " . ($user->is_active ? 'Yes' : 'No'));
                
                // Test PIN verification for CASHIER01
                if ($user->terminal_id === 'CASHIER01') {
                    $pinTest = $user->verifyPin('1234');
                    $this->line("    PIN '1234' verification: " . ($pinTest ? 'SUCCESS' : 'FAILED'));
                }
            }
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

    private function createUniqueUsers($tenant)
    {
        $terminalUsers = [
            [
                'terminal_id' => 'DUKAAN_CASHIER01',
                'name' => 'John Doe',
                'pin' => '1234',
                'role' => 'cashier',
                'is_active' => true,
            ],
            [
                'terminal_id' => 'DUKAAN_CASHIER02',
                'name' => 'Jane Smith',
                'pin' => '5678',
                'role' => 'cashier',
                'is_active' => true,
            ],
            [
                'terminal_id' => 'DUKAAN_MANAGER01',
                'name' => 'Mike Johnson',
                'pin' => '9999',
                'role' => 'manager',
                'is_active' => true,
            ],
            [
                'terminal_id' => 'DUKAAN_ADMIN01',
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

        $this->info('Created ' . count($terminalUsers) . ' unique terminal users for dukaantech');
        $this->info('Use these Terminal IDs:');
        foreach ($terminalUsers as $user) {
            $this->line("  {$user['terminal_id']} - PIN: {$user['pin']}");
        }
    }
}