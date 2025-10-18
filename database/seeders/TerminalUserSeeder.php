<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TerminalUser;
use App\Models\Account;

class TerminalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find demo tenant
        $demoAccount = Account::where('slug', 'demo')->first();
        
        if (!$demoAccount) {
            $this->command->warn('Demo tenant not found. Skipping terminal user seeding.');
            return;
        }

        // Create demo terminal users
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
                'tenant_id' => $demoAccount->id,
                ...$userData
            ]);
        }

        $this->command->info('Created ' . count($terminalUsers) . ' demo terminal users for demo tenant.');
    }
}