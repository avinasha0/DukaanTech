<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    protected $signature = 'user:create-test';
    protected $description = 'Create a test user without tenant for testing redirect';

    public function handle()
    {
        $this->info('Creating test user...');
        
        // Create user without tenant_id
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'tenant_id' => null, // No tenant
        ]);
        
        $this->info('Test user created successfully!');
        $this->line("Email: {$user->email}");
        $this->line("Password: password");
        $this->line("Tenant ID: " . ($user->tenant_id ?? 'NULL'));
        
        $this->info("\nLogin URL: http://localhost:8000/login");
        $this->info("This user should be redirected to organization setup page.");
        
        return 0;
    }
}