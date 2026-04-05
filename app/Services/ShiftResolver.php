<?php

namespace App\Services;

use App\Models\Shift;

class ShiftResolver
{
    /**
     * Latest open shift for tenant/outlet (same idea as PosApiController::resolveActiveShiftId).
     */
    public static function activeShiftIdForOutlet(int $tenantId, ?int $outletId): ?int
    {
        $query = Shift::where('tenant_id', $tenantId)->whereNull('closed_at');
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        return optional($query->latest('id')->first())->id;
    }
}
