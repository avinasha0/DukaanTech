<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill - {{ $bill->invoice_no }}</title>
    <style>
        @page {
            margin: 0;
            size: {{ $config['template']['paper_size'] === 'thermal_80mm' ? '80mm auto' : 'A4' }};
        }
        
        body {
            font-family: {{ $config['template']['font_family'] }}, monospace;
            font-size: {{ $config['template']['font_size'] }}px;
            line-height: 1.4;
            margin: {{ $config['template']['margin_top'] }}px {{ $config['template']['margin_right'] }}px {{ $config['template']['margin_bottom'] }}px {{ $config['template']['margin_left'] }}px;
            padding: 0;
            background: white;
            color: black;
        }
        
        .receipt {
            max-width: {{ $config['template']['paper_size'] === 'thermal_80mm' ? '300px' : '100%' }};
            margin: 0 auto;
            {{ $config['template']['show_border'] ? 'border: 1px ' . $config['template']['border_style'] . ' #000; padding: 15px;' : '' }}
        }
        
        .header {
            text-align: {{ $config['header']['logo_position'] }};
            {{ $config['header']['show_separator'] ? 'border-bottom: 1px ' . $config['header']['separator_style'] . ' #000; padding-bottom: 10px; margin-bottom: 15px;' : 'margin-bottom: 15px;' }}
        }
        
        .logo {
            margin-bottom: 10px;
        }
        
        .logo img {
            max-width: {{ $config['header']['logo_size'] === 'small' ? '60px' : ($config['header']['logo_size'] === 'medium' ? '100px' : '150px') }};
            height: auto;
        }
        
        .restaurant-name {
            font-size: {{ $config['header']['restaurant_name_size'] === 'small' ? '16px' : ($config['header']['restaurant_name_size'] === 'medium' ? '20px' : '24px') }};
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .restaurant-address, .restaurant-phone, .gstin {
            font-size: 10px;
            margin-bottom: 3px;
        }
        
        .order-info {
            margin-bottom: 15px;
        }
        
        .order-info div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .items {
            margin-bottom: 15px;
        }
        
        .item {
            margin-bottom: 8px;
            {{ $config['item']['item_separator'] === 'dashed' ? 'border-bottom: 1px dashed #ccc; padding-bottom: 5px;' : ($config['item']['item_separator'] === 'solid' ? 'border-bottom: 1px solid #ccc; padding-bottom: 5px;' : '') }}
        }
        
        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .item-details {
            font-size: 10px;
            color: #666;
            margin-left: 10px;
        }
        
        .item-qty-price {
            display: flex;
            justify-content: space-between;
            margin-top: 2px;
        }
        
        .modifier {
            font-size: 9px;
            color: #888;
            margin-left: 15px;
        }
        
        .totals {
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-bottom: 15px;
        }
        
        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .total-line.final {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        .payment-info {
            margin-bottom: 15px;
        }
        
        .payment-method {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        
        .qr-code {
            margin: 10px 0;
            text-align: center;
        }
        
        .qr-code svg {
            max-width: {{ $config['footer']['qr_code_size'] === 'small' ? '100px' : ($config['footer']['qr_code_size'] === 'medium' ? '150px' : '200px') }};
            height: auto;
        }
        
        .footer-text {
            font-size: 10px;
            margin-top: 10px;
        }
        
        @media print {
            body { margin: 0; padding: 0; }
            .receipt { border: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header Section -->
        <div class="header">
            @if($config['header']['show_logo'] && $bill->tenant->logo_url)
                <div class="logo">
                    <img src="{{ $bill->tenant->logo_url }}" alt="{{ $bill->tenant->name }} Logo">
                </div>
            @endif
            
            @if($config['header']['show_restaurant_name'])
                <div class="restaurant-name">{{ $bill->tenant->name ?? 'Restaurant' }}</div>
            @endif
            
            @if($config['header']['show_address'] && $bill->outlet->address)
                <div class="restaurant-address">
                    @if(is_array($bill->outlet->address))
                        {{ $bill->outlet->address['street'] ?? '' }}<br>
                        {{ $bill->outlet->address['city'] ?? '' }}, {{ $bill->outlet->address['state'] ?? '' }}<br>
                        {{ $bill->outlet->address['pincode'] ?? '' }}
                    @else
                        {{ $bill->outlet->address }}
                    @endif
                </div>
            @endif
            
            @if($config['header']['show_phone'] && $bill->tenant->phone)
                <div class="restaurant-phone">Phone: {{ $bill->tenant->phone }}</div>
            @endif
            
            @if($config['header']['show_gstin'] && $bill->outlet->gstin)
                <div class="gstin">GSTIN: {{ $bill->outlet->gstin }}</div>
            @endif
        </div>
        
        <!-- Order Information -->
        <div class="order-info">
            <div><span>Invoice #:</span><span>{{ $bill->invoice_no }}</span></div>
            <div><span>Date:</span><span>{{ $bill->created_at->format('d/m/Y H:i') }}</span></div>
            <div><span>Order Type:</span><span>{{ $bill->order->orderType->name ?? ($bill->order->mode === 'DELIVERY' ? 'Delivery' : ($bill->order->mode === 'DINE_IN' ? 'Dine In' : ($bill->order->mode === 'PICKUP' ? 'Take Away' : 'Dine In'))) }}</span></div>
            @if($bill->order->table_no)
                <div><span>Table:</span><span>{{ $bill->order->table_no }}</span></div>
            @endif
        </div>
        
        <!-- Customer Information - Always show fields, populate only for delivery -->
        <div class="customer-info" style="margin-bottom: 15px; border-top: 1px solid #000; padding-top: 10px;">
            <div><span>Name:</span><span>{{ ($bill->order->orderType->slug ?? '') === 'delivery' || $bill->order->mode === 'DELIVERY' ? ($bill->order->customer_name ?? '') : '' }}</span></div>
            <div><span>Address:</span><span>{{ ($bill->order->orderType->slug ?? '') === 'delivery' || $bill->order->mode === 'DELIVERY' ? ($bill->order->delivery_address ?? $bill->order->customer_address ?? '') : '' }}</span></div>
        </div>
        
        <!-- Items Section -->
        <div class="items">
            @foreach($bill->order->items as $item)
                <div class="item">
                    <div class="item-name">{{ $item->item->name }}</div>
                    
                    @if($config['item']['show_item_description'] && $item->note)
                        <div class="item-details">{{ $item->note }}</div>
                    @endif
                    
                    @if($config['item']['show_modifiers'] && $item->modifiers->count() > 0)
                        @foreach($item->modifiers as $modifier)
                            <div class="modifier">+ {{ $modifier->modifier->name }} (₹{{ number_format($modifier->price, 2) }})</div>
                        @endforeach
                    @endif
                    
                    <div class="item-qty-price">
                        <span>{{ $item->qty }} x ₹{{ number_format($item->price, 2) }}</span>
                        <span>₹{{ number_format($item->qty * $item->price + $item->modifiers->sum('price'), 2) }}</span>
                    </div>
                    
                    @if($config['item']['show_tax_breakdown'] && $item->tax_rate > 0)
                        <div class="item-details">Tax ({{ $item->tax_rate }}%): ₹{{ number_format(($item->qty * $item->price + $item->modifiers->sum('price')) * ($item->tax_rate / 100), 2) }}</div>
                    @endif
                    
                    @if($config['item']['show_discount'] && $item->discount > 0)
                        <div class="item-details">Discount: -₹{{ number_format($item->discount, 2) }}</div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Totals Section -->
        <div class="totals">
            <div class="total-line">
                <span>Sub Total:</span>
                <span>₹{{ number_format($bill->sub_total, 2) }}</span>
            </div>
            
            @if($bill->tax_total > 0)
                <div class="total-line">
                    <span>Tax:</span>
                    <span>₹{{ number_format($bill->tax_total, 2) }}</span>
                </div>
            @endif
            
            @if($bill->discount_total > 0)
                <div class="total-line">
                    <span>Discount:</span>
                    <span>-₹{{ number_format($bill->discount_total, 2) }}</span>
                </div>
            @endif
            
            @if($bill->order->delivery_fee > 0 && $bill->order->orderType->slug === 'delivery')
                <div class="total-line">
                    <span>Delivery Fee:</span>
                    <span>₹{{ number_format($bill->order->delivery_fee, 2) }}</span>
                </div>
            @endif
            
            @if($bill->round_off != 0)
                <div class="total-line">
                    <span>Round Off:</span>
                    <span>₹{{ number_format($bill->round_off, 2) }}</span>
                </div>
            @endif
            
            <div class="total-line final">
                <span>Total:</span>
                <span>₹{{ number_format($bill->net_total, 2) }}</span>
            </div>
        </div>
        
        <!-- Payment Information -->
        @if($config['payment']['show_payment_methods'] && $bill->payments->count() > 0)
            <div class="payment-info">
                <div style="border-top: 1px {{ $config['payment']['payment_separator'] }} #000; padding-top: 10px;">
                    @foreach($bill->payments as $payment)
                        <div class="payment-method">
                            <span>{{ $payment->method }}:</span>
                            <span>₹{{ number_format($payment->amount, 2) }}</span>
                        </div>
                        @if($config['payment']['show_payment_reference'] && $payment->ref)
                            <div style="font-size: 10px; color: #666; margin-left: 10px;">Ref: {{ $payment->ref }}</div>
                        @endif
                    @endforeach
                    
                    @if($config['payment']['show_change_amount'] && $bill->paid_amount > $bill->net_total)
                        <div class="payment-method">
                            <span>Change:</span>
                            <span>₹{{ number_format($bill->paid_amount - $bill->net_total, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Footer Section -->
        <div class="footer">
            @if($config['footer']['show_qr_code'])
                <div class="qr-code">
                    {!! app(\App\Services\PrinterService::class)->generateQRCode($bill->invoice_no, 120) !!}
                </div>
            @endif
            
            @if($config['footer']['show_payment_qr'])
                <div class="qr-code">
                    <div style="font-size: 10px; margin-bottom: 5px;">Scan to Pay</div>
                    {!! app(\App\Services\PrinterService::class)->generatePaymentQRCode($bill) !!}
                </div>
            @endif
            
            @if($config['footer']['show_footer_text'])
                <div class="footer-text">{{ $config['footer']['footer_text'] }}</div>
            @endif
        </div>
    </div>
</body>
</html>
