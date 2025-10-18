<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Account;
use App\Models\Outlet;
use App\Models\TaxRate;
use Illuminate\Console\Command;

class TestOrganizationSetup extends Command
{
    protected $signature = 'test:org-setup {action}';
    protected $description = 'Test organization setup scenarios (complete|incomplete|reset)';

    public function handle()
    {
        $action = $this->argument('action');
        
        $demoAccount = Account::where('slug', 'demo')->first();
        if (!$demoAccount) {
            $this->error('Demo tenant not found.');
            return 1;
        }
        
        switch ($action) {
            case 'complete':
                $this->makeSetupComplete($demoAccount);
                break;
            case 'incomplete':
                $this->makeSetupIncomplete($demoAccount);
                break;
            case 'reset':
                $this->resetSetup($demoAccount);
                break;
            default:
                $this->error('Invalid action. Use: complete, incomplete, or reset');
                return 1;
        }
        
        return 0;
    }
    
    private function makeSetupComplete($account)
    {
        $this->info('Making organization setup complete...');
        
        // Ensure outlet exists
        if (!$account->outlets()->exists()) {
            Outlet::create([
                'tenant_id' => $account->id,
                'name' => 'Main Outlet',
                'code' => 'MAIN',
                'address' => [
                    'street' => '123 Main St',
                    'city' => 'Demo City',
                    'state' => 'Demo State',
                    'pincode' => '12345',
                    'country' => 'India',
                ]
            ]);
        }
        
        // Ensure tax rates exist
        if (!$account->taxRates()->exists()) {
            TaxRate::create([
                'tenant_id' => $account->id,
                'name' => 'GST 5%',
                'code' => 'GST5',
                'rate' => 5.00,
                'inclusive' => true,
            ]);
            
            TaxRate::create([
                'tenant_id' => $account->id,
                'name' => 'GST 18%',
                'code' => 'GST18',
                'rate' => 18.00,
                'inclusive' => true,
            ]);
        }
        
        // Ensure settings exist
        if (!$account->settings || !isset($account->settings['currency'])) {
            $account->update([
                'settings' => [
                    'currency' => 'INR',
                    'timezone' => 'Asia/Kolkata',
                    'tax_inclusive' => true,
                ]
            ]);
        }
        
        $this->info('Organization setup is now complete!');
        $this->showSetupStatus($account);
    }
    
    private function makeSetupIncomplete($account)
    {
        $this->info('Making organization setup incomplete...');
        
        // Remove outlets (bypass tenant scope)
        Outlet::where('tenant_id', $account->id)->delete();
        
        // Remove tax rates (bypass tenant scope)
        TaxRate::where('tenant_id', $account->id)->delete();
        
        // Clear settings
        $account->update(['settings' => null]);
        
        $this->info('Organization setup is now incomplete!');
        $this->showSetupStatus($account);
    }
    
    private function resetSetup($account)
    {
        $this->info('Resetting organization setup...');
        
        // Remove outlets (bypass tenant scope)
        Outlet::where('tenant_id', $account->id)->delete();
        
        // Remove tax rates (bypass tenant scope)
        TaxRate::where('tenant_id', $account->id)->delete();
        
        // Clear settings
        $account->update(['settings' => null]);
        
        $this->info('Organization setup has been reset!');
        $this->showSetupStatus($account);
    }
    
    private function showSetupStatus($account)
    {
        $this->line("\n=== Organization Setup Status ===");
        $this->line("Tenant: {$account->name}");
        $this->line("Outlets: " . Outlet::where('tenant_id', $account->id)->count());
        $this->line("Tax Rates: " . TaxRate::where('tenant_id', $account->id)->count());
        $this->line("Settings: " . ($account->settings ? 'Present' : 'Missing'));
        
        $isComplete = $this->isSetupComplete($account);
        $this->line("Setup Complete: " . ($isComplete ? 'YES' : 'NO'));
        
        if (!$isComplete) {
            $this->line("\nLogin will redirect to organization setup page.");
        } else {
            $this->line("\nLogin will redirect to tenant dashboard.");
        }
    }
    
    private function isSetupComplete($account): bool
    {
        if (Outlet::where('tenant_id', $account->id)->count() == 0) return false;
        if (TaxRate::where('tenant_id', $account->id)->count() == 0) return false;
        if (!$account->settings || !isset($account->settings['currency'])) return false;
        
        return true;
    }
}