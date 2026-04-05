<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\DB;

class QrPublicOrderService
{
    /**
     * Create a new QR order or append lines to the open table session order.
     *
     * @param  array<string, mixed>  $data
     * @return array{order: Order, appended: bool, created: bool}
     */
    public function createOrAppend(array $data, Account $account): array
    {
        app()->instance('tenant.id', $account->id);
        app()->instance('tenant.model', $account);
        app()->instance('tenant', $account);

        return DB::transaction(function () use ($data, $account) {
            $mode = (string) ($data['mode'] ?? '');
            if ($mode === 'PICKUP') {
                $mode = 'TAKEAWAY';
            }
            if ($mode === 'DELIVERY') {
                throw new \InvalidArgumentException('Delivery is not available from QR ordering. Choose dine-in or pickup.');
            }
            if (! in_array($mode, ['DINE_IN', 'TAKEAWAY'], true)) {
                throw new \InvalidArgumentException('Invalid order type for QR ordering. Choose dine-in or pickup.');
            }
            $data['mode'] = $mode;
            // Pickup never uses a table / table QR session
            if ($mode === 'TAKEAWAY') {
                $data['table_id'] = null;
            }

            $tableId = isset($data['table_id']) ? (int) $data['table_id'] : 0;
            $existingOrderId = isset($data['existing_order_id']) ? (int) $data['existing_order_id'] : null;
            $phone = trim((string) ($data['customer_phone'] ?? ''));

            if ($tableId > 0 && $mode === 'DINE_IN') {
                $table = RestaurantTable::where('tenant_id', $account->id)
                    ->whereKey($tableId)
                    ->lockForUpdate()
                    ->first();

                if (! $table) {
                    throw new \InvalidArgumentException('Table not found');
                }

                if ((int) $table->outlet_id !== (int) $data['outlet_id']) {
                    throw new \InvalidArgumentException('Outlet does not match this table');
                }

                $order = $this->findOpenQrOrderForTable($account, $table, $existingOrderId, $phone);

                if ($order) {
                    return $this->appendItems($order, $data, $account, $table);
                }
            }

            $table = $tableId > 0 ? RestaurantTable::where('tenant_id', $account->id)->whereKey($tableId)->first() : null;

            if ($table) {
                $this->assertTableAllowsNewQrOrder($account, $table);
            }

            return $this->createNewOrder($data, $account, $table);
        });
    }

    /**
     * Block a brand-new QR session when the table is already in use (POS or approved dine-in session).
     */
    public function ensureTableAllowsNewQrOrder(Account $account, RestaurantTable $table): void
    {
        $this->assertTableAllowsNewQrOrder($account, $table);
    }

    protected function assertTableAllowsNewQrOrder(Account $account, RestaurantTable $table): void
    {
        $table->refresh();

        if ($table->status === 'occupied') {
            throw new \RuntimeException(
                'This table is already occupied. Please ask staff for assistance.'
            );
        }

        $hasOpenPosOrNonQrSession = Order::where('tenant_id', $account->id)
            ->where('table_id', $table->id)
            ->where('status', Order::STATUS_OPEN)
            ->where('mode', 'DINE_IN')
            ->where(function ($q) {
                $q->whereNull('source')
                    ->orWhere('source', '!=', 'mobile_qr');
            })
            ->exists();

        if ($hasOpenPosOrNonQrSession) {
            throw new \RuntimeException(
                'This table is already occupied. Please ask staff for assistance.'
            );
        }
    }

    protected function findOpenQrOrderForTable(
        Account $account,
        RestaurantTable $table,
        ?int $existingOrderId,
        string $phone
    ): ?Order {
        $base = Order::where('tenant_id', $account->id)
            ->where('table_id', $table->id)
            ->where('status', Order::STATUS_OPEN)
            ->where('source', 'mobile_qr');

        if ($existingOrderId) {
            $order = (clone $base)->whereKey($existingOrderId)->first();
            if ($order) {
                $this->assertPhoneMatch($order, $phone);

                return $order;
            }
        }

        $orders = (clone $base)->orderByDesc('id')->get();
        if ($orders->isEmpty()) {
            return null;
        }

        $phoneNorm = $phone !== '' ? preg_replace('/\D+/', '', $phone) : '';

        if ($phoneNorm !== '') {
            $matches = $orders->filter(function ($o) use ($phoneNorm) {
                $op = preg_replace('/\D+/', '', (string) ($o->customer_phone ?? ''));

                return $op !== '' && $op === $phoneNorm;
            });

            if ($matches->count() === 1) {
                return $matches->first();
            }
            if ($matches->isEmpty()) {
                throw new \RuntimeException(
                    'This table is already occupied. Use the same phone number as the first guest on this table, or ask staff for help.'
                );
            }

            throw new \RuntimeException('Multiple active orders match this phone — ask staff for help.');
        }

        // No phone: resume only via existing_order_id (handled above). Orders is non-empty here, so another guest cannot start.
        throw new \RuntimeException(
            'This table is already occupied. Use the same phone number as your first order, or ask staff for help.'
        );
    }

    protected function assertPhoneMatch(Order $order, string $phone): void
    {
        $phoneNorm = $phone !== '' ? preg_replace('/\D+/', '', $phone) : '';
        if ($phoneNorm === '') {
            return;
        }
        $op = preg_replace('/\D+/', '', (string) ($order->customer_phone ?? ''));
        if ($op !== '' && $op !== $phoneNorm) {
            throw new \RuntimeException(
                'This table is already occupied. Use the same phone number as the first guest on this table, or ask staff for help.'
            );
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{order: Order, appended: bool, created: bool}
     */
    protected function appendItems(Order $order, array $data, Account $account, RestaurantTable $table): array
    {
        foreach ($data['items'] as $itemData) {
            $item = Item::where('tenant_id', $account->id)->find($itemData['item_id']);
            if ($item) {
                OrderItem::create([
                    'tenant_id' => $account->id,
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'qty' => (int) ($itemData['qty'] ?? 1),
                    'price' => $item->price,
                    'tax_rate' => 0,
                    'discount' => 0,
                ]);
            }
        }

        $meta = $order->meta ?? [];
        $inPhone = trim((string) ($data['customer_phone'] ?? ''));

        $newState = null;
        if ($order->state === Order::STATE_PENDING_QR_APPROVAL) {
            // Still awaiting first POS approval — extra lines stay in the same approval batch.
        } elseif ($order->state === 'NEW') {
            if (! $account->qrRequirePosApprovalBeforeKot()) {
                $meta['qr_has_unsent_items'] = true;
                $meta['qr_new_items_at'] = now()->toIso8601String();
            } else {
                // Pickup / take-away: every new batch of items must go back to POS for approval before KOT.
                $pickupMode = in_array($order->mode, ['TAKEAWAY', 'PICKUP'], true);
                if ($pickupMode || $account->qrApprovalEachSubmit()) {
                    $newState = Order::STATE_PENDING_QR_APPROVAL;
                } else {
                    $meta['qr_has_unsent_items'] = true;
                    $meta['qr_new_items_at'] = now()->toIso8601String();
                }
            }
        }

        $updates = ['meta' => $meta];
        if ($newState !== null) {
            $updates['state'] = $newState;
        }
        if (! empty($data['customer_name']) && empty($order->customer_name)) {
            $updates['customer_name'] = $data['customer_name'];
        }
        if ($inPhone !== '' && empty($order->customer_phone)) {
            $updates['customer_phone'] = $inPhone;
        }
        if (! empty($data['special_instructions'])) {
            $prev = trim((string) ($order->special_instructions ?? ''));
            $add = trim((string) $data['special_instructions']);
            $updates['special_instructions'] = $prev === '' ? $add : $prev."\n".$add;
        }

        $order->update($updates);
        $order->refresh();

        // Table stays "free" until POS approves the QR order
        if (! $order->isPendingQrApproval()) {
            $table->refresh();
            $table->syncStatus();
        }

        return [
            'order' => $order->fresh()->load('items.item'),
            'appended' => true,
            'created' => false,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{order: Order, appended: bool, created: bool}
     */
    protected function createNewOrder(array $data, Account $account, ?RestaurantTable $table): array
    {
        $row = [
            'tenant_id' => $account->id,
            'outlet_id' => $data['outlet_id'],
            'order_type_id' => $data['order_type_id'],
            'payment_method' => $data['payment_method'],
            'customer_name' => $data['customer_name'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'customer_address' => $data['customer_address'] ?? null,
            'delivery_address' => $data['delivery_address'] ?? null,
            'delivery_fee' => $data['delivery_fee'] ?? 0,
            'special_instructions' => $data['special_instructions'] ?? null,
            'mode' => $data['mode'],
            'table_no' => $table ? $table->name : ($data['table_no'] ?? null),
            'table_id' => $table?->id,
            'state' => $account->qrRequirePosApprovalBeforeKot()
                ? Order::STATE_PENDING_QR_APPROVAL
                : 'NEW',
            'source' => 'mobile_qr',
        ];

        $order = Order::create($row);

        foreach ($data['items'] as $itemData) {
            $item = Item::where('tenant_id', $account->id)->find($itemData['item_id']);
            if ($item) {
                OrderItem::create([
                    'tenant_id' => $account->id,
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'qty' => (int) ($itemData['qty'] ?? 1),
                    'price' => $item->price,
                    'tax_rate' => 0,
                    'discount' => 0,
                ]);
            }
        }

        // Occupied status only after POS approval — skip while awaiting QR confirmation
        if ($table && ! $order->isPendingQrApproval()) {
            $table->syncStatus();
        }

        return [
            'order' => $order->load('items.item'),
            'appended' => false,
            'created' => true,
        ];
    }
}
