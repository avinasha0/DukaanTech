<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'description',
        'website',
        'industry',
        'company_size',
        'founded_year',
        'owner_id',
        'status',
        'plan',
        'settings',
        'logo_path',
        'logo_url',
        'contact_info',
        'business_hours',
        'social_media',
        'tax_settings',
        'notification_settings',
        'kot_enabled',
        'kot_settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'contact_info' => 'array',
        'business_hours' => 'array',
        'social_media' => 'array',
        'tax_settings' => 'array',
        'notification_settings' => 'array',
        'kot_enabled' => 'boolean',
        'kot_settings' => 'array',
    ];

    public function outlets(): HasMany
    {
        return $this->hasMany(Outlet::class, 'tenant_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'tenant_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'tenant_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'tenant_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'tenant_id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'tenant_id');
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class, 'tenant_id');
    }

    public function taxRates(): HasMany
    {
        return $this->hasMany(TaxRate::class, 'tenant_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'tenant_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * When true (default), QR dine-in and pickup orders stay in PENDING_QR_APPROVAL until POS confirms before KOT.
     * When false, QR orders go straight to NEW and the kitchen can receive KOT without a POS confirm step.
     */
    public function qrRequirePosApprovalBeforeKot(): bool
    {
        return (bool) data_get($this->settings, 'qr_require_pos_approval_before_kot', true);
    }

    /**
     * When true, every QR submit that adds items to an already-approved (NEW) order sends it back to POS for approval.
     * When false, only the first basket needs approval; further adds stay on the same order without re-approval.
     * Only applies while {@see qrRequirePosApprovalBeforeKot()} is true.
     */
    public function qrApprovalEachSubmit(): bool
    {
        return (bool) data_get($this->settings, 'qr_approval_each_submit', false);
    }

    public function paymentGatewayEnabled(): bool
    {
        return (bool) data_get($this->settings, 'payment_gateway_enabled', false);
    }

    /**
     * Razorpay Key Id (public, safe for Checkout.js).
     */
    public function razorpayKeyId(): ?string
    {
        $v = data_get($this->settings, 'razorpay_key_id');

        return is_string($v) && $v !== '' ? $v : null;
    }

    /**
     * Decrypted Razorpay Key Secret for server-side API calls.
     */
    public function razorpayKeySecret(): ?string
    {
        $blob = data_get($this->settings, 'razorpay_key_secret_encrypted');
        if (! is_string($blob) || $blob === '') {
            return null;
        }
        try {
            return Crypt::decryptString($blob);
        } catch (\Throwable) {
            return null;
        }
    }

    public function razorpayWebhookSecret(): ?string
    {
        $blob = data_get($this->settings, 'razorpay_webhook_secret_encrypted');
        if (! is_string($blob) || $blob === '') {
            return null;
        }
        try {
            return Crypt::decryptString($blob);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Gateway is on and both API keys are present.
     */
    public function razorpayQrPaymentReady(): bool
    {
        return $this->paymentGatewayEnabled()
            && $this->razorpayKeyId() !== null
            && $this->razorpayKeySecret() !== null;
    }
}
