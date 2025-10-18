<?php

namespace App\Http\Controllers\Tenant\Pos;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\KitchenTicket;
use App\Models\Order;
use App\Services\PrinterService;
use Illuminate\Http\Request;

class KotController extends Controller
{
    public function __construct(
        private PrinterService $printerService
    ) {}

    public function fire(Request $request, $tenant, Order $order)
    {
        // Check if KOT is enabled for this tenant
        $tenant = Account::find(app('tenant.id'));
        if (!$tenant || !$tenant->kot_enabled) {
            return response()->json(['error' => 'KOT functionality is disabled'], 403);
        }
        
        $data = $request->validate([
            'station' => 'required|string|max:255',
            'device_id' => 'nullable|exists:devices,id',
        ]);
        
        if ($order->state !== 'NEW') {
            return response()->json(['error' => 'Order must be in NEW state to fire KOT'], 400);
        }
        
        // Create kitchen ticket
        $kitchenTicket = KitchenTicket::create([
            'tenant_id' => app('tenant.id'),
            'outlet_id' => $order->outlet_id,
            'device_id' => $data['device_id'] ?? (app()->bound('device.id') ? app('device.id') : null),
            'order_id' => $order->id,
            'station' => $data['station'],
            'status' => 'SENT',
        ]);
        
        // Create kitchen lines for each order item
        foreach ($order->items as $orderItem) {
            $kitchenTicket->lines()->create([
                'tenant_id' => app('tenant.id'),
                'order_item_id' => $orderItem->id,
                'qty' => $orderItem->qty,
            ]);
        }
        
        // Print KOT
        $this->printerService->printKOT($kitchenTicket);
        
        // Update order state
        $order->update(['state' => 'IN_KITCHEN']);
        
        return response()->json([
            'id' => $kitchenTicket->id,
            'order_id' => $kitchenTicket->order_id,
            'station' => $kitchenTicket->station,
            'status' => $kitchenTicket->status,
            'created_at' => $kitchenTicket->created_at,
            'lines_count' => $kitchenTicket->lines()->count()
        ], 201);
    }

    public function markReady(Request $request, $tenant, KitchenTicket $kitchenTicket)
    {
        // Validate that the KOT belongs to the current tenant
        if ($kitchenTicket->tenant_id !== app('tenant.id')) {
            return response()->json(['error' => 'Unauthorized access to KOT'], 403);
        }
        
        // Check if KOT is already ready
        if ($kitchenTicket->status === 'READY') {
            return response()->json(['error' => 'KOT is already marked as ready'], 400);
        }
        
        // Check if KOT is in a valid state to be marked ready
        if ($kitchenTicket->status !== 'SENT') {
            return response()->json(['error' => 'KOT must be in SENT status to mark as ready'], 400);
        }
        
        // Update KOT status
        $kitchenTicket->update(['status' => 'READY']);
        
        // Check if all KOTs for the order are ready
        $order = $kitchenTicket->order;
        $allReady = $order->kitchenTickets()->where('status', '!=', 'READY')->count() === 0;
        
        if ($allReady) {
            $order->update(['state' => 'READY']);
        }
        
        // Return updated KOT with relationships
        $kitchenTicket->load(['order', 'lines.orderItem.item']);
        
        return response()->json([
            'message' => 'KOT marked as ready successfully',
            'kot' => $kitchenTicket,
            'order_ready' => $allReady
        ]);
    }

    public function index(Request $request)
    {
        $query = KitchenTicket::where('tenant_id', app('tenant.id'))
            ->with(['order', 'lines.orderItem.item']);
            
        if ($request->has('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }
        
        if ($request->has('station')) {
            $query->where('station', $request->station);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $kotTickets = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($kotTickets);
    }

    public function show(KitchenTicket $kitchenTicket)
    {
        $kitchenTicket->load(['order', 'lines.orderItem.item', 'lines.orderItem.variant']);
        
        return response()->json($kitchenTicket);
    }
}
