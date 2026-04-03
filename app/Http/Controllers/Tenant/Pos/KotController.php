<?php

namespace App\Http\Controllers\Tenant\Pos;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\KitchenTicket;
use App\Models\Order;
use App\Services\PrinterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        
        $order->load('items');
        $newItems = $order->items()->where('sent_to_kitchen', false)->get();
        if ($newItems->isEmpty()) {
            return response()->json([
                'message' => 'No new items to send to kitchen',
                'order_id' => $order->id,
                'lines_count' => 0
            ], 200);
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
        
        // Create kitchen lines only for newly added items
        foreach ($newItems as $orderItem) {
            $kitchenTicket->lines()->create([
                'tenant_id' => app('tenant.id'),
                'order_item_id' => $orderItem->id,
                'qty' => $orderItem->qty,
            ]);
        }

        // Mark these items as sent to kitchen to avoid duplicate KOT lines
        $order->items()
            ->whereIn('id', $newItems->pluck('id')->all())
            ->update(['sent_to_kitchen' => true]);
        
        // Print KOT
        $this->printerService->printKOT($kitchenTicket);
        
        // Update order state once at least one KOT has been fired
        if ($order->state === 'NEW') {
            $order->update(['state' => 'IN_KITCHEN']);
        }
        
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
        return $this->finalizeMarkReady($kitchenTicket, (int) app('tenant.id'));
    }

    /**
     * Kitchen display at /{tenant}/kot is public; staff are not always logged in as web users.
     * Uses numeric id (not route model binding) so missing tickets return JSON instead of an HTML 404 page.
     */
    public function markReadyPublic(Request $request, string $tenant, int $kitchenTicketId): JsonResponse
    {
        Log::info('kot.mark_ready_public.hit', [
            'tenant_slug' => $tenant,
            'kitchen_ticket_id' => $kitchenTicketId,
            'path' => $request->path(),
            'method' => $request->method(),
            'wants_json' => $request->expectsJson(),
            'ip' => $request->ip(),
        ]);

        try {
            $account = Account::where('slug', $tenant)->first();
            if (! $account) {
                Log::warning('kot.mark_ready_public.tenant_missing', ['tenant_slug' => $tenant]);

                return response()->json([
                    'error' => 'Tenant not found',
                    'code' => 'tenant_not_found',
                ], 404);
            }

            $ticketLookup = KitchenTicket::query()
                ->where('tenant_id', $account->id)
                ->where('id', $kitchenTicketId)
                ->first();

            if (! $ticketLookup) {
                Log::warning('kot.mark_ready_public.ticket_missing', [
                    'tenant_id' => $account->id,
                    'kitchen_ticket_id' => $kitchenTicketId,
                ]);

                return response()->json([
                    'error' => 'Kitchen ticket not found',
                    'code' => 'ticket_not_found',
                ], 404);
            }

            return $this->finalizeMarkReady($ticketLookup, (int) $account->id);
        } catch (Throwable $e) {
            Log::error('kot.mark_ready_public.exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Server error while marking KOT ready',
                'code' => 'exception',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function indexPublic(Request $request, string $tenant): JsonResponse
    {
        Log::info('kot.tickets_public.hit', [
            'tenant_slug' => $tenant,
            'query' => $request->query(),
        ]);

        try {
            $account = Account::where('slug', $tenant)->first();
            if (! $account) {
                Log::warning('kot.tickets_public.tenant_missing', ['tenant_slug' => $tenant]);

                return response()->json(['error' => 'Tenant not found', 'code' => 'tenant_not_found'], 404);
            }

            if (! $account->kot_enabled) {
                return response()->json([]);
            }

            $query = KitchenTicket::query()
                ->where('tenant_id', $account->id)
                ->with(['order', 'lines.orderItem.item']);

            if ($request->filled('outlet_id')) {
                $query->where('outlet_id', $request->outlet_id);
            }
            if ($request->filled('station')) {
                $query->where('station', $request->station);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $kotTickets = $query->orderBy('created_at', 'desc')->get();

            Log::info('kot.tickets_public.ok', ['count' => $kotTickets->count()]);

            return response()->json($kotTickets);
        } catch (Throwable $e) {
            Log::error('kot.tickets_public.exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Failed to load kitchen tickets',
                'code' => 'exception',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function finalizeMarkReady(KitchenTicket $kitchenTicket, int $expectedTenantId): JsonResponse
    {
        $kotId = $kitchenTicket->id;
        $statusRaw = $kitchenTicket->status;
        $status = is_string($statusRaw) ? $statusRaw : (string) $statusRaw;

        if ((int) $kitchenTicket->tenant_id !== $expectedTenantId) {
            Log::warning('kot.finalize.tenant_mismatch', [
                'kitchen_ticket_id' => $kotId,
                'ticket_tenant_id' => $kitchenTicket->tenant_id,
                'expected_tenant_id' => $expectedTenantId,
            ]);

            return response()->json([
                'error' => 'Unauthorized access to KOT',
                'code' => 'tenant_mismatch',
            ], 403);
        }

        if ($status === 'READY') {
            Log::info('kot.finalize.already_ready', ['kitchen_ticket_id' => $kotId]);

            return response()->json([
                'error' => 'KOT is already marked as ready',
                'code' => 'already_ready',
            ], 400);
        }

        if ($status !== 'SENT') {
            Log::warning('kot.finalize.bad_status', [
                'kitchen_ticket_id' => $kotId,
                'status' => $status,
            ]);

            return response()->json([
                'error' => 'KOT must be in SENT status to mark as ready',
                'code' => 'invalid_status',
                'debug' => config('app.debug') ? ['current_status' => $status] : null,
            ], 400);
        }

        try {
            $kitchenTicket->update(['status' => 'READY']);
        } catch (Throwable $e) {
            Log::error('kot.finalize.update_failed', [
                'kitchen_ticket_id' => $kotId,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }

        $order = $kitchenTicket->order;
        if (! $order) {
            Log::error('kot.finalize.order_missing', ['kitchen_ticket_id' => $kotId]);

            return response()->json([
                'error' => 'Linked order not found for this KOT',
                'code' => 'order_missing',
            ], 422);
        }

        $allReady = $order->kitchenTickets()->where('status', '!=', 'READY')->count() === 0;

        if ($allReady) {
            $order->update(['state' => 'READY']);
        }

        $kitchenTicket->load(['order', 'lines.orderItem.item']);

        Log::info('kot.finalize.success', [
            'kitchen_ticket_id' => $kotId,
            'order_id' => $order->id,
            'order_ready' => $allReady,
        ]);

        return response()->json([
            'message' => 'KOT marked as ready successfully',
            'kot' => $kitchenTicket,
            'order_ready' => $allReady,
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
