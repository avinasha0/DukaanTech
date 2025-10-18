<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Account;
use Illuminate\Console\Command;

class FixDemoUser extends Command
{
    protected $signature = 'user:fix-demo';
    protected $description = 'Fix demo user tenant assignment';

    public function handle()
    {
        $this->info('Fixing demo user tenant assignment...');
        
        // Get demo tenant
        $demoAccount = Account::where('slug', 'demo')->first();
        
        if (!$demoAccount) {
            $this->error('Demo tenant not found.');
            return 1;
        }
        
        // Get demo user
        $demoUser = User::where('email', 'demo@pos-saas.com')->first();
        
        if (!$demoUser) {
            $this->error('Demo user not found.');
            return 1;
        }
        
        // Update user with tenant_id
        $demoUser->update(['tenant_id' => $demoAccount->id]);
        
        $this->info('Demo user fixed successfully!');
        $this->line("Email: {$demoUser->email}");
        $this->line("Tenant: {$demoAccount->name}");
        $this->line("Tenant Slug: {$demoAccount->slug}");
        
        $this->info("\nLogin URL: http://localhost:8000/login");
        $this->info("After login, you'll be redirected to: http://{$demoAccount->slug}.localhost:8000/t/dashboard");
        
        return 0;
    }
}