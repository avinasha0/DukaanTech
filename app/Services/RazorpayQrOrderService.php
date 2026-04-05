<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\QrRazorpayCheckout;
use App\Models\RazorpayQrPaymentKey;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Throwable;

class RazorpayQrOrderService
{
    public function __construct(
        private QrOrderKitchenService $qrOrderKitchenService,
        private QrPublicOrderService $qrPublicOrderService
    ) {}

    /**
     * @return array{enabled: bool, key_id: string|null}
     */
    public function publicConfig(Account $account): array
    {
        $ready = $account->razorpayQrPaymentReady();

        return [
            'enabled' => $ready,
            'key_id' => $ready ? $account->razorpayKeyId() : null,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{checkout: QrRazorpayCheckout, key_id: string, amount: int, currency: string, razorpay_order_id: string}
     */
    public function createRazorpayCheckout(string $tenantSlug, Account $account, array $data): array
    {
        if (! $account->razorpayQrPaymentReady()) {
            abort(403, 'Online payment is not enabled for this restaurant.');
        }

        $secret = $account->razorpayKeySecret();
        if (! $secret) {
            abort(503, 'Payment configuration is incomplete.');
        }

        $validated = $this->validateOrderPayload($data, $account, allowAppend: false);
        $outlet = $validated['outlet'];

        $totalPaise = $this->computeTotalPaise($account, $outlet, $validated['items']);

        if ($totalPaise < 100) {
            throw ValidationException::withMessages(['amount' => 'Order total must be at least ₹1.00']);
        }

        $api = new Api($account->razorpayKeyId(), $secret);
        $receipt = 'qr_'.substr(str_replace('-', '', (string) \Illuminate\Support\Str::uuid()), 0, 32);

        $rzOrder = $api->order->create([
            'receipt' => $receipt,
            'amount' => $totalPaise,
            'currency' => 'INR',
            'notes' => [
                'tenant_slug' => $tenantSlug,
                'tenant_id' => (string) $account->id,
            ],
        ]);

        $checkout = QrRazorpayCheckout::create([
            'tenant_id' => $account->id,
            'payload' => $validated['payload'],
            'amount_paise' => $totalPaise,
            'currency' => 'INR',
            'razorpay_order_id' => $rzOrder['id'],
            'expires_at' => now()->addMinutes(30),
        ]);

        return [
            'checkout' => $checkout,
            'key_id' => $account->razorpayKeyId(),
            'amount' => $totalPaise,
            'currency' => 'INR',
            'razorpay_order_id' => $rzOrder['id'],
        ];
    }

    /**
     * @return array{order: Order, kot: array|null, already_processed: bool}
     */
    public function verifyAndComplete(
        Account $account,
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $razorpaySignature
    ): array {
        if (! $account->razorpayQrPaymentReady()) {
            abort(403, 'Online payment is not enabled for this restaurant.');
        }

        $secret = $account->razorpayKeySecret();
        if (! $secret) {
            abort(503, 'Payment configuration is incomplete.');
        }

        $existing = RazorpayQrPaymentKey::where('razorpay_payment_id', $razorpayPaymentId)->first();
        if ($existing) {
            $order = Order::where('tenant_id', $account->id)->findOrFail($existing->order_id);

            return ['order' => $order->load('items.item'), 'kot' => null, 'already_processed' => true];
        }

        $checkout = QrRazorpayCheckout::where('tenant_id', $account->id)
            ->where('razorpay_order_id', $razorpayOrderId)
            ->first();

        if (! $checkout || $checkout->completed_at) {
            abort(422, 'Checkout session is invalid or already used.');
        }

        if ($checkout->isExpired()) {
            abort(422, 'Checkout session expired. Please start again.');
        }

        $api = new Api($account->razorpayKeyId(), $secret);
        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
            ]);
        } catch (SignatureVerificationError $e) {
            abort(422, 'Invalid payment signature.');
        }

        return $this->finalizePaidCheckout($account, $checkout, $razorpayOrderId, $razorpayPaymentId, 'checkout');
    }

    /**
     * Webhook: payment captured — verify via API and complete (idempotent).
     *
     * @return array{order: Order|null, kot: array|null, already_processed: bool, skipped: string|null}
     */
    public function completeFromWebhook(Account $account, string $razorpayOrderId, string $razorpayPaymentId): array
    {
        if (! $account->razorpayQrPaymentReady()) {
            return ['order' => null, 'kot' => null, 'already_processed' => false, 'skipped' => 'disabled'];
        }

        $existing = RazorpayQrPaymentKey::where('razorpay_payment_id', $razorpayPaymentId)->first();
        if ($existing) {
            $order = Order::where('tenant_id', $account->id)->find($existing->order_id);

            return [
                'order' => $order?->load('items.item'),
                'kot' => null,
                'already_processed' => true,
                'skipped' => null,
            ];
        }

        $secret = $account->razorpayKeySecret();
        $api = new Api($account->razorpayKeyId(), $secret);

        try {
            $payment = $api->payment->fetch($razorpayPaymentId);
            if (($payment['status'] ?? '') !== 'captured') {
                return ['order' => null, 'kot' => null, 'already_processed' => false, 'skipped' => 'not_captured'];
            }
            if (($payment['order_id'] ?? '') !== $razorpayOrderId) {
                return ['order' => null, 'kot' => null, 'already_processed' => false, 'skipped' => 'order_mismatch'];
            }
        } catch (Throwable $e) {
            Log::warning('Razorpay webhook: fetch payment failed', ['message' => $e->getMessage()]);

            return ['order' => null, 'kot' => null, 'already_processed' => false, 'skipped' => 'fetch_failed'];
        }

        $checkout = QrRazorpayCheckout::where('tenant_id', $account->id)
            ->where('razorpay_order_id', $razorpayOrderId)
            ->first();

        if (! $checkout || $checkout->completed_at) {
            return ['order' => null, 'kot' => null, 'already_processed' => false, 'skipped' => 'no_checkout'];
        }

        $result = $this->finalizePaidCheckout($account, $checkout, $razorpayOrderId, $razorpayPaymentId, 'webhook');

        return [
            'order' => $result['order'],
            'kot' => $result['kot'],
            'already_processed' => $result['already_processed'],
            'skipped' => null,
        ];
    }

    /**
     * @return array{order: Order, kot: array|null, already_processed: bool}
     */
    private function finalizePaidCheckout(
        Account $account,
        QrRazorpayCheckout $checkout,
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $via
    ): array {
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);

        $checkout->refresh();
        if ($checkout->completed_at && $checkout->order_id) {
            $done = Order::where('tenant_id', $account->id)->find($checkout->order_id);
            if ($done) {
                return ['order' => $done->load('items.item'), 'kot' => null, 'already_processed' => true];
            }
        }

        $existing = RazorpayQrPaymentKey::where('razorpay_payment_id', $razorpayPaymentId)->first();
        if ($existing) {
            $order = Order::where('tenant_id', $account->id)->findOrFail($existing->order_id);

            return ['order' => $order->load('items.item'), 'kot' => null, 'already_processed' => true];
        }

        $kot = null;
        $order = null;

        DB::transaction(function () use ($account, $checkout, $razorpayOrderId, $razorpayPaymentId, $via, &$order) {
            $locked = QrRazorpayCheckout::whereKey($checkout->id)->lockForUpdate()->firstOrFail();
            if ($locked->completed_at && $locked->order_id) {
                $order = Order::where('tenant_id', $account->id)->find($locked->order_id);

                return;
            }

            $payload = $locked->payload;

            $outlet = Outlet::where('tenant_id', $account->id)
                ->whereKey($payload['outlet_id'])
                ->firstOrFail();

            $recalc = $this->computeTotalPaise($account, $outlet, $payload['items']);
            if ((int) $recalc !== (int) $locked->amount_paise) {
                throw new \RuntimeException('Amount mismatch — prices may have changed. Please try again.');
            }

            $table = null;
            if (! empty($payload['table_id'])) {
                $table = RestaurantTable::where('tenant_id', $account->id)
                    ->whereKey((int) $payload['table_id'])
                    ->firstOrFail();
            }

            if (($payload['mode'] ?? '') === 'DINE_IN' && ! $table) {
                throw new \RuntimeException('Table is required for dine-in orders.');
            }

            if ($table) {
                $this->qrPublicOrderService->ensureTableAllowsNewQrOrder($account, $table);
            }

            $shiftId = ShiftResolver::activeShiftIdForOutlet($account->id, (int) $payload['outlet_id']);

            $meta = [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'qr_paid_at' => now()->toIso8601String(),
                'shift_id' => $shiftId,
                'qr_paid_via' => $via,
            ];

            $order = Order::create([
                'tenant_id' => $account->id,
                'outlet_id' => $payload['outlet_id'],
                'order_type_id' => $payload['order_type_id'],
                'payment_method' => 'razorpay',
                'customer_name' => $payload['customer_name'] ?? null,
                'customer_phone' => $payload['customer_phone'] ?? null,
                'customer_address' => $payload['customer_address'] ?? null,
                'delivery_address' => $payload['delivery_address'] ?? null,
                'delivery_fee' => $payload['delivery_fee'] ?? 0,
                'special_instructions' => $payload['special_instructions'] ?? null,
                'mode' => $payload['mode'],
                'table_no' => $table ? $table->name : ($payload['table_no'] ?? null),
                'table_id' => $table?->id,
                'state' => 'NEW',
                'source' => 'mobile_qr',
                'meta' => $meta,
            ]);

            foreach ($payload['items'] as $itemData) {
                $item = Item::where('tenant_id', $account->id)->find($itemData['item_id']);
                if ($item) {
                    $unit = $item->priceFor(null, $outlet);
                    OrderItem::create([
                        'tenant_id' => $account->id,
                        'order_id' => $order->id,
                        'item_id' => $item->id,
                        'qty' => (int) ($itemData['qty'] ?? 1),
                        'price' => $unit,
                        'tax_rate' => 0,
                        'discount' => 0,
                    ]);
                }
            }

            RazorpayQrPaymentKey::create([
                'tenant_id' => $account->id,
                'razorpay_payment_id' => $razorpayPaymentId,
                'order_id' => $order->id,
            ]);

            $locked->update([
                'completed_at' => now(),
                'order_id' => $order->id,
            ]);

            if ($table) {
                $table->refresh();
                $table->syncStatus();
            }
        });

        if (! $order) {
            throw new \RuntimeException('Could not complete order.');
        }

        $order = $order->fresh()->load('items.item');

        try {
            $kot = $this->qrOrderKitchenService->fireForOrder($order);
        } catch (Throwable $e) {
            Log::error('RazorpayQrOrderService: KOT failed after payment', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);
            $kot = null;
        }

        return [
            'order' => $order,
            'kot' => $kot,
            'already_processed' => false,
        ];
    }

    /**
     * @param  array<int, array{item_id: int, qty: int}>  $items
     */
    public function computeTotalPaise(Account $account, Outlet $outlet, array $items): int
    {
        $totalRupee = 0.0;
        foreach ($items as $line) {
            $item = Item::where('tenant_id', $account->id)->find($line['item_id']);
            if (! $item) {
                continue;
            }
            $qty = (int) ($line['qty'] ?? 1);
            $unit = $item->priceFor(null, $outlet);
            $totalRupee += $unit * $qty;
        }

        return (int) round($totalRupee * 100);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{payload: array<string, mixed>, outlet: Outlet, items: array}
     */
    protected function validateOrderPayload(array $data, Account $account, bool $allowAppend): array
    {
        $rules = [
            'outlet_id' => ['required', Rule::exists('outlets', 'id')->where('tenant_id', $account->id)],
            'order_type_id' => ['required', Rule::exists('order_types', 'id')->where('tenant_id', $account->id)],
            'mode' => 'required|in:DINE_IN,TAKEAWAY,PICKUP',
            'table_no' => 'nullable|string|max:255',
            'table_id' => 'nullable|integer',
            'existing_order_id' => 'nullable|integer',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'delivery_address' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric|min:0',
            'special_instructions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => [
                'required',
                Rule::exists('items', 'id')->where('tenant_id', $account->id)->whereNull('deleted_at'),
            ],
            'items.*.qty' => 'required|integer|min:1',
        ];

        $validator = Validator::make($data, $rules);

        $validated = $validator->validate();

        if (! $allowAppend && ! empty($validated['existing_order_id'])) {
            throw ValidationException::withMessages([
                'existing_order_id' => 'Paid checkout does not support adding to an existing order.',
            ]);
        }

        $mode = (string) $validated['mode'];
        if ($mode === 'PICKUP') {
            $mode = 'TAKEAWAY';
        }
        if ($mode === 'DELIVERY') {
            throw ValidationException::withMessages(['mode' => 'Delivery is not available from QR ordering.']);
        }
        if (! in_array($mode, ['DINE_IN', 'TAKEAWAY'], true)) {
            throw ValidationException::withMessages(['mode' => 'Invalid order mode.']);
        }

        if ($mode === 'TAKEAWAY') {
            $validated['table_id'] = null;
        }

        if ($mode === 'DINE_IN' && empty($validated['table_id'])) {
            throw ValidationException::withMessages([
                'table_id' => 'Table is required for dine-in when paying online.',
            ]);
        }

        $outlet = Outlet::where('tenant_id', $account->id)->findOrFail($validated['outlet_id']);

        foreach ($validated['items'] as $line) {
            $item = Item::where('tenant_id', $account->id)->find($line['item_id']);
            if (! $item || ! $item->is_active) {
                throw ValidationException::withMessages(['items' => 'One or more items are invalid or inactive.']);
            }
        }

        $validated['mode'] = $mode;

        return ['payload' => $validated, 'outlet' => $outlet, 'items' => $validated['items']];
    }
}
