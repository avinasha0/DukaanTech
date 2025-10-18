<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakeSetupIncomplete extends Command
{
    protected $signature = 'make:setup-incomplete';
    protected $description = 'Make demo organization setup incomplete for testing';

    public function handle()
    {
        $this->info('Making organization setup incomplete...');
        
        $demoAccount = Account::where('slug', 'demo')->first();
        if (!$demoAccount) {
            $this->error('Demo tenant not found.');
            return 1;
        }
        
        // Clear foreign key references first
        DB::table('categories')->where('tenant_id', $demoAccount->id)->update(['outlet_id' => null]);
        $this->line("Cleared outlet references from categories");
        
        // Remove outlets using raw query
        $outletDeleted = DB::table('outlets')->where('tenant_id', $demoAccount->id)->delete();
        $this->line("Deleted {$outletDeleted} outlets");
        
        // Remove tax rates using raw query
        $taxRateDeleted = DB::table('tax_rates')->where('tenant_id', $demoAccount->id)->delete();
        $this->line("Deleted {$taxRateDeleted} tax rates");
        
        // Clear settings
        $demoAccount->update(['settings' => null]);
        $this->line("Cleared settings");
        
        $this->info('Organization setup is now incomplete!');
        
        // Check status
        $this->call('check:org-setup');
        
        $this->line("\nNow when you login, you'll be redirected to organization setup page.");
        
        return 0;
    }
}