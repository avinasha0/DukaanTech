<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class SimpleIncompleteSetup extends Command
{
    protected $signature = 'make:simple-incomplete';
    protected $description = 'Make demo organization setup appear incomplete by clearing settings';

    public function handle()
    {
        $this->info('Making organization setup appear incomplete...');
        
        $demoAccount = Account::where('slug', 'demo')->first();
        if (!$demoAccount) {
            $this->error('Demo tenant not found.');
            return 1;
        }
        
        // Just clear settings to make setup appear incomplete
        $demoAccount->update(['settings' => null]);
        $this->line("Cleared settings - setup will appear incomplete");
        
        $this->info('Organization setup now appears incomplete!');
        
        // Check status
        $this->call('check:org-setup');
        
        $this->line("\nNow when you login, you'll be redirected to organization setup page.");
        $this->line("Note: Outlets and tax rates still exist, but settings are missing.");
        
        return 0;
    }
}