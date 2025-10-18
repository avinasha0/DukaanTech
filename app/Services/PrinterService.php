<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\BillTemplate;
use App\Models\KitchenTicket;
use Illuminate\Support\Facades\Queue;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrinterService
{
    public function printBill(Bill $bill, ?BillTemplate $template = null): void
    {
        // Queue a job to print the bill
        // Queue::push(new PrintBillJob($bill, $template));
        
        // For now, we'll just log the print request
        \Log::info('Bill print requested', [
            'bill_id' => $bill->id,
            'invoice_no' => $bill->invoice_no,
            'amount' => $bill->net_total,
            'template_id' => $template?->id
        ]);
    }

    public function printKOT(KitchenTicket $kt): void
    {
        // Queue a job to print the KOT
        // Queue::push(new PrintKOTJob($kt));
        
        // For now, we'll just log the print request
        \Log::info('KOT print requested', [
            'kitchen_ticket_id' => $kt->id,
            'station' => $kt->station,
            'order_id' => $kt->order_id
        ]);
    }

    public function generateBillPDF(Bill $bill, ?BillTemplate $template = null): string
    {
        // Load necessary relationships
        $bill->load(['order.orderType', 'order.items.item', 'order.items.modifiers.modifier', 'payments', 'outlet', 'tenant']);
        
        // Get template or use default
        $template = $template ?? $this->getDefaultTemplate($bill->tenant_id);
        $config = $template->getMergedConfig();
        
        // Generate PDF content for the bill
        $html = view('prints.bill-template', compact('bill', 'template', 'config'))->render();
        
        // Use DomPDF to generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        
        return $pdf->output();
    }

    public function generateBillHTML(Bill $bill, ?BillTemplate $template = null): string
    {
        // Load necessary relationships
        $bill->load(['order.orderType', 'order.items.item', 'order.items.modifiers.modifier', 'payments', 'outlet', 'tenant']);
        
        // Get template or use default
        $template = $template ?? $this->getDefaultTemplate($bill->tenant_id);
        $config = $template->getMergedConfig();
        
        return view('prints.bill-template', compact('bill', 'template', 'config'))->render();
    }

    public function generateQRCode(string $data, int $size = 200): string
    {
        return QrCode::size($size)
            ->format('svg')
            ->generate($data);
    }

    public function generatePaymentQRCode(Bill $bill): string
    {
        // Generate UPI payment URL or payment link
        $paymentData = $this->buildPaymentData($bill);
        
        return $this->generateQRCode($paymentData, 150);
    }

    protected function buildPaymentData(Bill $bill): string
    {
        // Build UPI payment URL or payment gateway link
        $amount = $bill->net_total;
        $invoiceNo = $bill->invoice_no;
        $merchantName = $bill->tenant->name ?? 'Restaurant';
        
        // Example UPI URL format
        return "upi://pay?pa=merchant@upi&pn={$merchantName}&am={$amount}&cu=INR&tn=Bill-{$invoiceNo}";
    }

    protected function getDefaultTemplate(int $tenantId): BillTemplate
    {
        return BillTemplate::where('tenant_id', $tenantId)
            ->where('is_default', true)
            ->where('is_active', true)
            ->first() ?? $this->createDefaultTemplate($tenantId);
    }

    protected function createDefaultTemplate(int $tenantId): BillTemplate
    {
        return BillTemplate::create([
            'tenant_id' => $tenantId,
            'name' => 'Default Template',
            'slug' => 'default-template',
            'description' => 'Default bill template',
            'is_default' => true,
            'is_active' => true,
        ]);
    }

    public function generateKOTContent(KitchenTicket $kt): string
    {
        // Generate KOT content for thermal printer
        $content = "KOT #{$kt->id}\n";
        $content .= "Order #{$kt->order_id}\n";
        $content .= "Station: {$kt->station}\n";
        $content .= "Time: " . now()->format('H:i:s') . "\n";
        $content .= str_repeat('-', 32) . "\n";
        
        foreach ($kt->lines as $line) {
            $content .= "{$line->qty}x {$line->orderItem->item->name}\n";
            if ($line->orderItem->note) {
                $content .= "Note: {$line->orderItem->note}\n";
            }
        }
        
        $content .= str_repeat('-', 32) . "\n";
        $content .= "Thank you!\n";
        
        return $content;
    }
}
