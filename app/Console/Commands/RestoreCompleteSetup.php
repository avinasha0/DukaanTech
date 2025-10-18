<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class RestoreCompleteSetup extends Command
{
    protected $signature = 'make:complete-setup';
    protected $description = 'Restore demo organization setup to complete state';

    public function handle()
    {
        $this->info('Restoring organization setup to complete state...');
        
        $demoAccount = Account::where('slug', 'demo')->first();
        if (!$demoAccount) {
            $this->error('Demo tenant not found.');
            return 1;
        }
        
        // Restore settings
        $demoAccount->update([
            'settings' => [
                'currency' => 'INR',
                'timezone' => 'Asia/Kolkata',
                'tax_inclusive' => true,
            ]
        ]);
        $this->line("Restored settings");
        
        $this->info('Organization setup is now complete!');
        
        // Check status
        $this->call('check:org-setup');
        
        $this->line("\nNow when you login, you'll be redirected to tenant dashboard.");
        
        return 0;
    }
}