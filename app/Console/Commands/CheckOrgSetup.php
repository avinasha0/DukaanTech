<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckOrgSetup extends Command
{
    protected $signature = 'check:org-setup';
    protected $description = 'Check organization setup status for demo tenant';

    public function handle()
    {
        $this->info('Checking organization setup status...');
        
        $demoAccount = Account::where('slug', 'demo')->first();
        if (!$demoAccount) {
            $this->error('Demo tenant not found.');
            return 1;
        }
        
        $this->line("\n=== Organization Setup Status ===");
        $this->line("Tenant: {$demoAccount->name}");
        
        // Check outlets using raw query to avoid tenant scope issues
        $outletCount = DB::table('outlets')->where('tenant_id', $demoAccount->id)->count();
        $this->line("Outlets: {$outletCount}");
        
        // Check tax rates using raw query
        $taxRateCount = DB::table('tax_rates')->where('tenant_id', $demoAccount->id)->count();
        $this->line("Tax Rates: {$taxRateCount}");
        
        // Check settings
        $settings = $demoAccount->settings;
        $this->line("Settings: " . ($settings ? 'Present' : 'Missing'));
        if ($settings) {
            $this->line("Currency: " . ($settings['currency'] ?? 'Not set'));
        }
        
        // Determine if setup is complete
        $isComplete = $outletCount > 0 && $taxRateCount > 0 && $settings && isset($settings['currency']);
        $this->line("Setup Complete: " . ($isComplete ? 'YES' : 'NO'));
        
        if (!$isComplete) {
            $this->line("\n⚠️  Login will redirect to organization setup page.");
            $this->line("Missing components:");
            if ($outletCount == 0) $this->line("- No outlets found");
            if ($taxRateCount == 0) $this->line("- No tax rates found");
            if (!$settings || !isset($settings['currency'])) $this->line("- Settings incomplete");
        } else {
            $this->line("\n✅ Login will redirect to tenant dashboard.");
        }
        
        return 0;
    }
}