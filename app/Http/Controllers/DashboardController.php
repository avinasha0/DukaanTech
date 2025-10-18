<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\SetupStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private SetupStatusService $setupStatusService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $tenant = null;
        $setupStatus = null;
        $nextStep = null;
        $completionPercentage = 0;

        // If user has a tenant, get setup status
        if ($user && $user->tenant_id) {
            $tenant = $user->tenant;
            $setupStatus = $this->setupStatusService->getSetupStepsStatus($tenant);
            $nextStep = $this->setupStatusService->getNextStep($tenant);
            $completionPercentage = $this->setupStatusService->getCompletionPercentage($tenant);
        }

        return view('dashboard', compact('tenant', 'setupStatus', 'nextStep', 'completionPercentage'));
    }
}
