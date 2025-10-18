<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class TestOrgSetupPage extends Command
{
    protected $signature = 'test:org-setup-page';
    protected $description = 'Test organization setup page access';

    public function handle()
    {
        $this->info('Testing organization setup page access...');
        
        // Get test user
        $user = User::where('email', 'test@example.com')->first();
        
        if (!$user) {
            $this->error('Test user not found. Run: php artisan user:create-test');
            return 1;
        }
        
        $this->line("Test user found: {$user->email}");
        $this->line("Tenant ID: " . ($user->tenant_id ?? 'NULL'));
        
        // Simulate login
        Auth::login($user);
        
        $this->info('User logged in successfully');
        $this->line('You can now test the organization setup page at: http://localhost:8000/organization/setup');
        
        return 0;
    }
}