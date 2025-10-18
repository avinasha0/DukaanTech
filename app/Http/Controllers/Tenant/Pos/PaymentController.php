<?php

namespace App\Http\Controllers\Tenant\Pos;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function store(Request $request, Bill $bill)
    {
        $data = $request->validate([
            'method' => 'required|in:CASH,CARD,UPI,WALLET,OTHER',
            'amount' => 'required|numeric|min:0.01',
            'ref' => 'nullable|string|max:255',
            'device_id' => 'nullable|exists:devices,id',
        ]);
        
        if ($bill->state !== 'OPEN') {
            return response()->json(['error' => 'Bill is not open for payment'], 400);
        }
        
        if (!isset($data['device_id']) && app()->bound('device.id')) {
            app()->instance('device.id', app('device.id'));
        } elseif (isset($data['device_id'])) {
            app()->instance('device.id', $data['device_id']);
        }

        $payment = $this->paymentService->addPayment(
            $bill,
            $data['method'],
            $data['amount'],
            $data['ref'] ?? null
        );
        
        // Check if bill is fully paid
        $this->paymentService->settle($bill);
        
        return response()->json($payment, 201);
    }

    public function index(Bill $bill)
    {
        $payments = $bill->payments()->orderBy('created_at', 'desc')->get();
        
        return response()->json($payments);
    }

    public function getPaymentMethods()
    {
        $methods = $this->paymentService->getPaymentMethods();
        
        return response()->json($methods);
    }

    public function calculateChange(Request $request, Bill $bill)
    {
        $data = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);
        
        $change = $this->paymentService->calculateChange($data['paid_amount'], $bill->net_total);
        
        return response()->json([
            'change' => $change,
            'paid_amount' => $data['paid_amount'],
            'bill_total' => $bill->net_total,
        ]);
    }
}
