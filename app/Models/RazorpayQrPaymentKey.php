<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RazorpayQrPaymentKey extends Model
{
    protected $table = 'razorpay_qr_payment_keys';

    protected $fillable = [
        'tenant_id',
        'razorpay_payment_id',
        'order_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
