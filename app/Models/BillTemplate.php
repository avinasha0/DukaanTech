<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillTemplate extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'is_default',
        'is_active',
        'template_config',
        'header_config',
        'footer_config',
        'item_config',
        'payment_config',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'template_config' => 'array',
        'header_config' => 'array',
        'footer_config' => 'array',
        'item_config' => 'array',
        'payment_config' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function getDefaultTemplateConfig(): array
    {
        return [
            'paper_size' => 'thermal_80mm',
            'font_family' => 'monospace',
            'font_size' => 12,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'show_border' => false,
            'border_style' => 'solid',
        ];
    }

    public function getDefaultHeaderConfig(): array
    {
        return [
            'show_logo' => true,
            'logo_size' => 'medium',
            'logo_position' => 'center',
            'show_restaurant_name' => true,
            'restaurant_name_size' => 'large',
            'show_address' => true,
            'show_phone' => true,
            'show_gstin' => true,
            'show_separator' => true,
            'separator_style' => 'dashed',
        ];
    }

    public function getDefaultFooterConfig(): array
    {
        return [
            'show_qr_code' => true,
            'qr_code_size' => 'medium',
            'qr_code_position' => 'center',
            'show_footer_text' => true,
            'footer_text' => 'Thank you for your visit!',
            'show_payment_qr' => true,
            'payment_qr_size' => 'small',
        ];
    }

    public function getDefaultItemConfig(): array
    {
        return [
            'show_item_code' => false,
            'show_item_description' => true,
            'show_modifiers' => true,
            'show_tax_breakdown' => true,
            'show_discount' => true,
            'item_separator' => 'dashed',
            'group_by_category' => false,
        ];
    }

    public function getDefaultPaymentConfig(): array
    {
        return [
            'show_payment_methods' => true,
            'show_change_amount' => true,
            'show_payment_reference' => true,
            'payment_separator' => 'solid',
        ];
    }

    public function getMergedConfig(): array
    {
        return [
            'template' => array_merge($this->getDefaultTemplateConfig(), $this->template_config ?? []),
            'header' => array_merge($this->getDefaultHeaderConfig(), $this->header_config ?? []),
            'footer' => array_merge($this->getDefaultFooterConfig(), $this->footer_config ?? []),
            'item' => array_merge($this->getDefaultItemConfig(), $this->item_config ?? []),
            'payment' => array_merge($this->getDefaultPaymentConfig(), $this->payment_config ?? []),
        ];
    }
}