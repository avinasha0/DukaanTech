<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\QrPublicOrderService;
use Illuminate\Http\Request;

class QROrderApiController extends Controller
{
    /**
     * Create order from QR ordering interface — supports table_id + existing_order_id for session continuation.
     */
    public function createOrder(Request $request, string $tenantSlug, QrPublicOrderService $qrPublicOrderService)
    {
        try {
            $account = Account::where('slug', $tenantSlug)->first();
            if (! $account) {
                return response()->json(['error' => 'Restaurant not found'], 404);
            }

            $data = $request->all();

            if (empty($data['items']) || ! is_array($data['items'])) {
                return response()->json(['error' => 'Items are required'], 400);
            }

            $requiredFields = ['outlet_id', 'order_type_id', 'payment_method', 'mode'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    return response()->json(['error' => "Field {$field} is required"], 400);
                }
            }

            $result = $qrPublicOrderService->createOrAppend($data, $account);

            $order = $result['order'];

            return response()->json([
                'success' => true,
                'message' => $result['appended'] ? 'Items added to your order.' : 'Order placed successfully!',
                'order_id' => $order->id,
                'appended' => $result['appended'],
                'created' => $result['created'],
                'order' => $order,
            ], $result['created'] ? 201 : 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            \Log::error('QR Order API Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: '.$e->getMessage(),
            ], 500);
        }
    }
}
