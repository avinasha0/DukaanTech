<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\RazorpayQrOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class RazorpayQrPublicController extends Controller
{
    public function paymentGatewayConfig(string $tenant): JsonResponse
    {
        $account = Account::where('slug', $tenant)->first();
        if (! $account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        return response()->json(app(RazorpayQrOrderService::class)->publicConfig($account));
    }

    public function createOrder(Request $request, string $tenant): JsonResponse
    {
        $account = Account::where('slug', $tenant)->first();
        if (! $account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        try {
            $result = app(RazorpayQrOrderService::class)->createRazorpayCheckout($tenant, $account, $request->all());

            return response()->json([
                'success' => true,
                'key_id' => $result['key_id'],
                'amount' => $result['amount'],
                'currency' => $result['currency'],
                'razorpay_order_id' => $result['razorpay_order_id'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            if ($e instanceof HttpExceptionInterface) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getStatusCode());
            }
            Log::error('Razorpay createOrder', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Could not start payment',
            ], 422);
        }
    }

    public function verify(Request $request, string $tenant): JsonResponse
    {
        $account = Account::where('slug', $tenant)->first();
        if (! $account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $data = $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        try {
            $result = app(RazorpayQrOrderService::class)->verifyAndComplete(
                $account,
                $data['razorpay_order_id'],
                $data['razorpay_payment_id'],
                $data['razorpay_signature']
            );

            return response()->json([
                'success' => true,
                'already_processed' => $result['already_processed'],
                'order_id' => $result['order']->id,
                'order' => $result['order'],
                'kot' => $result['kot'],
            ]);
        } catch (Throwable $e) {
            if ($e instanceof HttpExceptionInterface) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Verification failed',
                ], $e->getStatusCode());
            }
            Log::error('Razorpay verify', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Verification failed',
            ], 422);
        }
    }

    /**
     * Razorpay webhooks: https://razorpay.com/docs/webhooks/
     */
    public function webhook(Request $request, string $tenant): JsonResponse
    {
        $account = Account::where('slug', $tenant)->first();
        if (! $account) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $secret = $account->razorpayWebhookSecret();
        $raw = $request->getContent();
        $sig = (string) $request->header('X-Razorpay-Signature', '');

        if ($secret === null || $secret === '') {
            return response()->json([
                'ignored' => true,
                'message' => 'Configure webhook secret in settings to process Razorpay webhooks securely.',
            ]);
        }

        $expected = hash_hmac('sha256', $raw, $secret);
        if (! hash_equals($expected, $sig)) {
            Log::warning('Razorpay webhook: bad signature', ['tenant' => $tenant]);

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? '';

        if ($event !== 'payment.captured') {
            return response()->json(['ignored' => true, 'event' => $event]);
        }

        $paymentEntity = data_get($payload, 'payload.payment.entity', []);
        $razorpayPaymentId = $paymentEntity['id'] ?? null;
        $razorpayOrderId = $paymentEntity['order_id'] ?? null;

        if (! $razorpayPaymentId || ! $razorpayOrderId) {
            return response()->json(['error' => 'Missing payment fields'], 422);
        }

        try {
            $result = app(RazorpayQrOrderService::class)->completeFromWebhook(
                $account,
                $razorpayOrderId,
                $razorpayPaymentId
            );

            return response()->json([
                'ok' => true,
                'skipped' => $result['skipped'] ?? null,
                'order_id' => $result['order']?->id,
            ]);
        } catch (Throwable $e) {
            Log::error('Razorpay webhook handler', ['message' => $e->getMessage()]);

            return response()->json(['error' => 'Handler failed'], 500);
        }
    }
}
