<?php

namespace App\Http\Controllers\Tenant\Pos;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Order;
use App\Models\BillTemplate;
use App\Services\BillingService;
use App\Services\PrinterService;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function __construct(
        private BillingService $billingService,
        private PrinterService $printerService
    ) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'device_id' => 'nullable|exists:devices,id',
        ]);
        
        $order = Order::find($data['order_id']);
        
        if ($order->state !== 'SERVED') {
            return response()->json(['error' => 'Order must be served before billing'], 400);
        }
        
        if (!isset($data['device_id']) && app()->bound('device.id')) {
            app()->instance('device.id', app('device.id')); // ensure available to service
        } elseif (isset($data['device_id'])) {
            app()->instance('device.id', $data['device_id']);
        }

        $bill = $this->billingService->createBillFromOrder($order);
        
        // Update order state to BILLED
        $order->update(['state' => 'BILLED']);
        
        return response()->json($bill, 201);
    }

    public function show(Bill $bill)
    {
        $bill->load(['order.items.item', 'order.items.variant', 'order.items.modifiers.modifier', 'order.orderType', 'payments', 'outlet']);
        
        return response()->json($bill);
    }

    public function void(Request $request, Bill $bill)
    {
        if ($bill->state !== 'OPEN') {
            return response()->json(['error' => 'Only open bills can be voided'], 400);
        }
        
        $bill = $this->billingService->voidBill($bill);
        
        return response()->json($bill);
    }

    public function print(Request $request, Bill $bill)
    {
        $templateId = $request->get('template_id');
        $template = $templateId ? BillTemplate::find($templateId) : null;
        
        $this->printerService->printBill($bill, $template);
        
        return response()->json(['message' => 'Bill sent to printer']);
    }

    public function generatePDF(Request $request, Bill $bill)
    {
        $templateId = $request->get('template_id');
        $template = $templateId ? BillTemplate::find($templateId) : null;
        
        $pdf = $this->printerService->generateBillPDF($bill, $template);
        
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="bill-' . $bill->invoice_no . '.pdf"');
    }

    public function generateHTML(Request $request, Bill $bill)
    {
        $templateId = $request->get('template_id');
        $template = $templateId ? BillTemplate::find($templateId) : null;
        
        $html = $this->printerService->generateBillHTML($bill, $template);
        
        return response($html);
    }

    public function generateQRCode(Bill $bill)
    {
        $qrCode = $this->printerService->generatePaymentQRCode($bill);
        
        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml');
    }

    public function index(Request $request)
    {
        $query = Bill::where('tenant_id', app('tenant.id'))
            ->with(['order', 'payments', 'outlet']);
            
        if ($request->has('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }
        
        if ($request->has('state')) {
            $query->where('state', $request->state);
        }
        
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        $bills = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($bills);
    }
}
