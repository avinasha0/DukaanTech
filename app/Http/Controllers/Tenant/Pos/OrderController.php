<?php

namespace App\Http\Controllers\Tenant\Pos;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'device_id' => 'nullable|exists:devices,id',
            'order_type_id' => 'required|exists:order_types,id',
            'payment_method' => 'required|in:cash,card,upi',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'delivery_address' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric|min:0',
            'special_instructions' => 'nullable|string',
            'mode' => 'required|in:DINE_IN,TAKEAWAY,DELIVERY,PICKUP',
            'table_no' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'meta' => 'nullable|array',
        ]);
        
        $data['tenant_id'] = app('tenant')->id;
        if (!isset($data['device_id']) && app()->bound('device.id')) {
            $data['device_id'] = app('device.id');
        }
        $data['state'] = 'NEW';
        
        $order = $this->orderService->create($data);
        
        return response()->json($order, 201);
    }

    public function addItem(Request $request, Order $order)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:item_variants,id',
            'modifiers' => 'nullable|array',
            'modifiers.*' => 'exists:modifiers,id',
            'note' => 'nullable|string|max:255',
        ]);
        
        $item = Item::find($data['item_id']);
        $variant = $data['variant_id'] ? ItemVariant::find($data['variant_id']) : null;
        
        $orderItem = $this->orderService->addItem(
            $order,
            $item,
            $data['qty'],
            $variant,
            $data['modifiers'] ?? [],
            $data['note'] ?? null
        );
        
        if (!$order->device_id && app()->bound('device.id')) {
            $order->update(['device_id' => app('device.id')]);
        }

        return response()->json($orderItem, 201);
    }

    public function updateState(Request $request, Order $order)
    {
        $data = $request->validate([
            'state' => 'required|in:NEW,IN_KITCHEN,READY,SERVED,BILLED,CLOSED',
        ]);
        
        $order = $this->orderService->updateOrderState($order, $data['state']);
        
        return response()->json($order);
    }

    public function show(Order $order)
    {
        $order->load(['items.item', 'items.variant', 'items.modifiers.modifier', 'outlet']);
        
        return response()->json($order);
    }

    public function index(Request $request)
    {
        $query = Order::where('tenant_id', app('tenant.id'))
            ->with(['items.item', 'outlet']);
            
        if ($request->has('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }
        
        if ($request->has('state')) {
            $query->where('state', $request->state);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($orders);
    }
}
