<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class SetupStatusService
{
    /**
     * Get the completion status of all setup steps
     */
    public function getSetupStepsStatus(Account $tenant): array
    {
        return [
            'organization_setup' => $this->isOrganizationSetupComplete($tenant),
            'menu_items' => $this->hasMenuItems($tenant),
            'orders_taken' => $this->hasOrdersTaken($tenant),
            'share_to_friend' => $this->hasSharedToFriend($tenant),
        ];
    }

    /**
     * Check if organization setup is complete
     */
    private function isOrganizationSetupComplete(Account $tenant): bool
    {
        // Check if tenant has at least one outlet
        if (!$tenant->outlets()->exists()) {
            return false;
        }
        
        // Check if tenant has at least one tax rate
        if (!$tenant->taxRates()->exists()) {
            return false;
        }
        
        // Check if tenant has basic settings
        if (!$tenant->settings || !isset($tenant->settings['currency'])) {
            return false;
        }
        
        return true;
    }

    /**
     * Check if tenant has menu items
     */
    private function hasMenuItems(Account $tenant): bool
    {
        return $tenant->items()->where('is_active', true)->exists();
    }

    /**
     * Check if tenant has shared to a friend
     * This could be tracked through a referral system or social sharing
     */
    private function hasSharedToFriend(Account $tenant): bool
    {
        // For now, we'll consider this as completed if the tenant has been active for a while
        // In a real implementation, you might track referrals or social shares
        return $tenant->created_at->diffInDays(now()) >= 1;
    }

    /**
     * Check if tenant has taken any orders
     */
    private function hasOrdersTaken(Account $tenant): bool
    {
        return $tenant->orders()->where('status', '!=', 'CANCELLED')->exists();
    }

    /**
     * Get the next incomplete step
     */
    public function getNextStep(Account $tenant): ?string
    {
        $steps = $this->getSetupStepsStatus($tenant);
        
        if (!$steps['organization_setup']) {
            return 'organization_setup';
        }
        
        if (!$steps['menu_items']) {
            return 'menu_items';
        }
        
        if (!$steps['orders_taken']) {
            return 'orders_taken';
        }
        
        if (!$steps['share_to_friend']) {
            return 'share_to_friend';
        }
        
        return null; // All steps completed
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage(Account $tenant): int
    {
        $steps = $this->getSetupStepsStatus($tenant);
        $completedSteps = array_sum($steps);
        return (int) round(($completedSteps / count($steps)) * 100);
    }
}
