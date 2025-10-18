<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeaBenchUserSeeder extends Seeder
{
    public function run(): void
    {
        // Get the teabench1 tenant
        $tenant = Account::where('slug', 'teabench1')->first();
        
        if (!$tenant) {
            $this->command->info('Tenant teabench1 not found. Creating...');
            $tenant = Account::create([
                'name' => 'Tea Bench 1',
                'slug' => 'teabench1',
                'settings' => [
                    'currency' => 'INR',
                    'timezone' => 'Asia/Kolkata',
                    'tax_inclusive' => true,
                ]
            ]);
        }

        // Check if user already exists
        $existingUser = User::where('email', 'teabench1@example.com')->first();
        if ($existingUser) {
            $this->command->info('User already exists for teabench1');
            return;
        }

        // Create user for teabench1
        $user = User::create([
            'name' => 'Tea Bench Manager',
            'email' => 'teabench1@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
        ]);

        $this->command->info('Created user: teabench1@example.com with password: password');
    }
}

