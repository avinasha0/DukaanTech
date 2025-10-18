<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QROrderApiController extends Controller
{
    /**
     * Create order from QR ordering interface - simplified approach
     */
    public function createOrder(Request $request, string $tenantSlug)
    {
        try {
            // Find tenant
            $account = Account::where('slug', $tenantSlug)->first();
            if (!$account) {
                return response()->json(['error' => 'Restaurant not found'], 404);
            }

            // Get request data
            $data = $request->all();
            
            // Basic validation
            if (empty($data['items']) || !is_array($data['items'])) {
                return response()->json(['error' => 'Items are required'], 400);
            }

            // Validate required fields
            $requiredFields = ['outlet_id', 'order_type_id', 'payment_method', 'mode'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    return response()->json(['error' => "Field {$field} is required"], 400);
                }
            }

            // Start database transaction
            DB::beginTransaction();

            try {
                // Create order
                $order = Order::create([
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
                    'table_no' => $data['table_no'] ?? null,
                    'state' => 'NEW',
                    'source' => 'mobile_qr', // Add source indicator
                ]);

                // Add order items
                foreach ($data['items'] as $itemData) {
                    $item = Item::find($itemData['item_id']);
                    if ($item) {
                        OrderItem::create([
                            'tenant_id' => $account->id,
                            'order_id' => $order->id,
                            'item_id' => $item->id,
                            'qty' => $itemData['qty'],
                            'price' => $item->price,
                            'tax_rate' => 0,
                            'discount' => 0,
                        ]);
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully!',
                    'order_id' => $order->id,
                    'order' => $order->load('items.item')
                ], 201);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('QR Order API Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }
}
