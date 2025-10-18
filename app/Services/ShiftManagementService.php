<?php

namespace App\Services;

use App\Models\Shift;
use App\Models\Account;
use App\Models\Outlet;
use App\Models\User;
use Carbon\Carbon;

class ShiftManagementService
{
    const SHIFTS = [
        1 => [
            'name' => 'Morning Shift',
            'start_time' => '06:00',
            'end_time' => '15:00',
            'description' => '6 AM to 3 PM'
        ],
        2 => [
            'name' => 'Evening Shift',
            'start_time' => '14:00', 
            'end_time' => '22:00',
            'description' => '2 PM to 10 PM'
        ],
        3 => [
            'name' => 'Night Shift',
            'start_time' => '22:00',
            'end_time' => '06:00',
            'description' => '10 PM to 6 AM'
        ]
    ];

    /**
     * Get the current shift based on current time
     */
    public function getCurrentShiftNumber(): int
    {
        $currentTime = now()->format('H:i');
        
        // Night shift (10 PM to 6 AM) - spans midnight
        if ($currentTime >= '22:00' || $currentTime < '06:00') {
            return 3;
        }
        // Morning shift (6 AM to 3 PM)
        elseif ($currentTime >= '06:00' && $currentTime < '15:00') {
            return 1;
        }
        // Evening shift (2 PM to 10 PM) - overlaps with morning shift
        elseif ($currentTime >= '14:00' && $currentTime < '22:00') {
            return 2;
        }
        
        // Default to morning shift
        return 1;
    }

    /**
     * Get shift information by number
     */
    public function getShiftInfo(int $shiftNumber): ?array
    {
        return self::SHIFTS[$shiftNumber] ?? null;
    }

    /**
     * Check if a shift can be opened at current time
     */
    public function canOpenShift(int $shiftNumber): bool
    {
        $currentShift = $this->getCurrentShiftNumber();
        return $currentShift === $shiftNumber;
    }

    /**
     * Get the next shift number
     */
    public function getNextShiftNumber(): int
    {
        $current = $this->getCurrentShiftNumber();
        return $current === 3 ? 1 : $current + 1;
    }

    /**
     * Get the previous shift number
     */
    public function getPreviousShiftNumber(): int
    {
        $current = $this->getCurrentShiftNumber();
        return $current === 1 ? 3 : $current - 1;
    }

    /**
     * Check if there's an open shift for the current shift period
     */
    public function hasOpenShiftForCurrentPeriod(int $tenantId, int $outletId): bool
    {
        $currentShift = $this->getCurrentShiftNumber();
        $shiftInfo = $this->getShiftInfo($currentShift);
        
        if (!$shiftInfo) {
            return false;
        }

        $today = now()->startOfDay();
        $shiftStart = $this->getShiftStartTime($currentShift, $today);
        $shiftEnd = $this->getShiftEndTime($currentShift, $today);

        return Shift::where('tenant_id', $tenantId)
            ->where('outlet_id', $outletId)
            ->whereNull('closed_at')
            ->where('created_at', '>=', $shiftStart)
            ->where('created_at', '<=', $shiftEnd)
            ->exists();
    }

    /**
     * Get the start time for a shift on a given date
     */
    public function getShiftStartTime(int $shiftNumber, Carbon $date): Carbon
    {
        $shiftInfo = $this->getShiftInfo($shiftNumber);
        if (!$shiftInfo) {
            return $date;
        }

        $time = Carbon::createFromFormat('H:i', $shiftInfo['start_time']);
        
        // For night shift, if it's before midnight, use previous day
        if ($shiftNumber === 3 && now()->format('H:i') < '06:00') {
            return $date->subDay()->setTimeFromTimeString($shiftInfo['start_time']);
        }
        
        return $date->setTimeFromTimeString($shiftInfo['start_time']);
    }

    /**
     * Get the end time for a shift on a given date
     */
    public function getShiftEndTime(int $shiftNumber, Carbon $date): Carbon
    {
        $shiftInfo = $this->getShiftInfo($shiftNumber);
        if (!$shiftInfo) {
            return $date;
        }

        $time = Carbon::createFromFormat('H:i', $shiftInfo['end_time']);
        
        // For night shift, end time is next day
        if ($shiftNumber === 3) {
            return $date->addDay()->setTimeFromTimeString($shiftInfo['end_time']);
        }
        
        return $date->setTimeFromTimeString($shiftInfo['end_time']);
    }

    /**
     * Get all shifts information
     */
    public function getAllShifts(): array
    {
        return self::SHIFTS;
    }

    /**
     * Validate shift transition
     */
    public function validateShiftTransition(int $fromShift, int $toShift): bool
    {
        // Allow transition to next shift or same shift
        $nextShift = $this->getNextShiftNumber();
        return $toShift === $nextShift || $toShift === $fromShift;
    }

    /**
     * Get shift status for display
     */
    public function getShiftStatus(int $tenantId, int $outletId): array
    {
        $currentShift = $this->getCurrentShiftNumber();
        $currentShiftInfo = $this->getShiftInfo($currentShift);
        $hasOpenShift = $this->hasOpenShiftForCurrentPeriod($tenantId, $outletId);
        
        return [
            'current_shift' => $currentShift,
            'current_shift_info' => $currentShiftInfo,
            'has_open_shift' => $hasOpenShift,
            'can_open_shift' => $this->canOpenShift($currentShift),
            'next_shift' => $this->getNextShiftNumber(),
            'all_shifts' => $this->getAllShifts()
        ];
    }
}
