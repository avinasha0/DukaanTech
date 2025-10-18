<?php

namespace App\Http\Controllers\Tenant\Pos;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Services\ShiftService;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function __construct(
        private ShiftService $shiftService
    ) {}

    public function open(Request $request)
    {
        $data = $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'opening_float' => 'nullable|numeric|min:0',
        ]);
        
        // Check if there's already an open shift
        $existingShift = $this->shiftService->getCurrentShift($data['outlet_id']);
        
        if ($existingShift) {
            return response()->json(['error' => 'There is already an open shift'], 400);
        }
        
        $shift = $this->shiftService->openShift(
            $data['outlet_id'],
            $data['opening_float'] ?? 0
        );
        
        return response()->json($shift, 201);
    }

    public function close(Request $request, Shift $shift)
    {
        $data = $request->validate([
            'actual_cash' => 'required|numeric|min:0',
        ]);
        
        if ($shift->closed_at) {
            return response()->json(['error' => 'Shift is already closed'], 400);
        }
        
        $shift = $this->shiftService->closeShift($shift, $data['actual_cash']);
        
        return response()->json($shift);
    }

    public function current(Request $request)
    {
        $data = $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
        ]);
        
        $shift = $this->shiftService->getCurrentShift($data['outlet_id']);
        
        if (!$shift) {
            return response()->json(['error' => 'No open shift found'], 404);
        }
        
        $summary = $this->shiftService->getShiftSummary($shift);
        
        return response()->json([
            'shift' => $shift,
            'summary' => $summary,
        ]);
    }

    public function index(Request $request)
    {
        $query = Shift::where('tenant_id', app('tenant.id'))
            ->with(['openedBy', 'outlet']);
            
        if ($request->has('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }
        
        if ($request->has('status')) {
            if ($request->status === 'open') {
                $query->whereNull('closed_at');
            } elseif ($request->status === 'closed') {
                $query->whereNotNull('closed_at');
            }
        }
        
        $shifts = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($shifts);
    }

    public function show(Shift $shift)
    {
        $shift->load(['openedBy', 'outlet']);
        $summary = $this->shiftService->getShiftSummary($shift);
        
        return response()->json([
            'shift' => $shift,
            'summary' => $summary,
        ]);
    }
}
