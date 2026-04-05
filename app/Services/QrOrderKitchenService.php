<?php

namespace App\Services;

use App\Models\Account;
use App\Models\KitchenTicket;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Sends unsent order lines to the kitchen (same rules as {@see \App\Http\Controllers\Tenant\Pos\KotController::fire}).
 * Used after POS QR approval and after Razorpay-paid QR orders.
 */
class QrOrderKitchenService
{
    public function __construct(
        private PrinterService $printerService
    ) {}

    /**
     * @return array<string, mixed>|null KOT summary or null if skipped
     */
    public function fireForOrder(Order $order): ?array
    {
        $account = Account::find($order->tenant_id);
        if (! $account || ! $account->kot_enabled) {
            return null;
        }

        $order->loadMissing('items');

        if ($reason = $order->kitchenBlockedReason()) {
            Log::warning('QrOrderKitchenService: KOT skipped', [
                'order_id' => $order->id,
                'reason' => $reason,
            ]);

            return null;
        }

        $newItems = $order->items()->where('sent_to_kitchen', false)->get();
        if ($newItems->isEmpty()) {
            return null;
        }

        $station = (string) (data_get($account->kot_settings, 'default_station') ?: 'hot-kitchen');

        $kitchenTicket = KitchenTicket::create([
            'tenant_id' => $order->tenant_id,
            'outlet_id' => $order->outlet_id,
            'device_id' => $order->device_id,
            'order_id' => $order->id,
            'station' => $station,
            'status' => 'SENT',
        ]);

        foreach ($newItems as $orderItem) {
            $kitchenTicket->lines()->create([
                'tenant_id' => $order->tenant_id,
                'order_item_id' => $orderItem->id,
                'qty' => $orderItem->qty,
            ]);
        }

        $order->items()
            ->whereIn('id', $newItems->pluck('id')->all())
            ->update(['sent_to_kitchen' => true]);

        if ($order->state === 'NEW') {
            $order->update(['state' => 'IN_KITCHEN']);
        }

        try {
            $this->printerService->printKOT($kitchenTicket);
        } catch (Throwable $e) {
            Log::warning('QrOrderKitchenService: printKOT failed', ['message' => $e->getMessage()]);
        }

        return [
            'id' => $kitchenTicket->id,
            'order_id' => $kitchenTicket->order_id,
            'station' => $kitchenTicket->station,
            'status' => $kitchenTicket->status,
            'lines_count' => $kitchenTicket->lines()->count(),
        ];
    }
}
