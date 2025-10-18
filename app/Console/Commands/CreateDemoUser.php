<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateDemoUser extends Command
{
    protected $signature = 'user:create-demo';
    protected $description = 'Create a demo user for testing';

    public function handle()
    {
        $this->info('Creating demo user...');
        
        // Get demo tenant
        $demoAccount = Account::where('slug', 'demo')->first();
        
        if (!$demoAccount) {
            $this->error('Demo tenant not found. Please run the DemoTenantSeeder first.');
            return 1;
        }
        
        // Create demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@pos-saas.com',
            'password' => Hash::make('password'),
            'tenant_id' => $demoAccount->id,
            'email_verified_at' => now(), // Skip email verification for demo
        ]);
        
        $this->info('Demo user created successfully!');
        $this->line("Email: {$user->email}");
        $this->line("Password: password");
        $this->line("Tenant: {$demoAccount->name}");
        
        $this->info("\nYou can now login at: http://localhost:8000/login");
        $this->info("Or access tenant dashboard at: http://demo.localhost:8000/t/dashboard");
        
        return 0;
    }
}