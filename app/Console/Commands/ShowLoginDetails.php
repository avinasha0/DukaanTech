<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Account;
use Illuminate\Console\Command;

class ShowLoginDetails extends Command
{
    protected $signature = 'login:details';
    protected $description = 'Show login details for demo users';

    public function handle()
    {
        $this->info('=== POS SaaS Login Details ===');
        
        // Check for demo tenant
        $demoAccount = Account::where('slug', 'demo')->first();
        
        if ($demoAccount) {
            $this->info("Demo Tenant: {$demoAccount->name} (slug: {$demoAccount->slug})");
            
            // Check for users in demo tenant
            $users = User::where('tenant_id', $demoAccount->id)->get();
            
            if ($users->count() > 0) {
                $this->info("\nDemo Users:");
                foreach ($users as $user) {
                    $this->line("Email: {$user->email}");
                    $this->line("Password: password");
                    $this->line("Name: {$user->name}");
                    $this->line("Status: " . ($user->email_verified_at ? 'Verified' : 'Not Verified'));
                    $this->line("---");
                }
            } else {
                $this->warn('No users found for demo tenant');
            }
        } else {
            $this->warn('Demo tenant not found');
        }
        
        $this->info("\n=== Registration Flow ===");
        $this->info('1. Visit: http://localhost:8000/register');
        $this->info('2. Fill registration form');
        $this->info('3. Check email for activation link');
        $this->info('4. Complete organization setup');
        $this->info('5. Access POS dashboard');
        
        $this->info("\n=== Direct Login (if demo user exists) ===");
        $this->info('Visit: http://localhost:8000/login');
        
        return 0;
    }
}