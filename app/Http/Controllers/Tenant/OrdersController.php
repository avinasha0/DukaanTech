<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        // If this is an API request, return JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            $query = Order::where('tenant_id', app('tenant.id'))
                ->with(['orderType', 'items.item', 'items.modifiers', 'outlet', 'table'])
                ->orderBy('created_at', 'desc');

            // Filter by order type
            if ($request->has('order_type') && $request->order_type !== '') {
                $query->where('order_type_id', $request->order_type);
            }

            // Filter by status
            if ($request->has('status') && $request->status !== '') {
                $query->where('state', $request->status);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Filter by shift_id
            if ($request->has('shift_id') && $request->shift_id) {
                $query->where('shift_id', $request->shift_id);
            }

            // Filter by outlet_id
            if ($request->has('outlet_id') && $request->outlet_id) {
                $query->where('outlet_id', $request->outlet_id);
            }

            $orders = $query->paginate(20);

            return response()->json($orders);
        }

        // For web requests, return the view
        $tenant = app('tenant');
        $orderTypes = \App\Models\OrderType::where('tenant_id', app('tenant.id'))->get();
        
        return view('tenant.orders', compact('tenant', 'orderTypes'));
    }

    public function show(Order $order)
    {
        $order->load(['orderType', 'items.item', 'items.modifiers', 'outlet', 'table']);
        
        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'state' => 'required|in:NEW,IN_KITCHEN,READY,SERVED,BILLED,CLOSED'
        ]);

        $order->update($data);

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        // Delete related records first
        $order->items()->each(function ($item) {
            $item->modifiers()->delete();
            $item->delete();
        });
        
        $order->kitchenTickets()->delete();
        $order->bill()->delete();
        
        // Delete the order
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}