<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Category;
use App\Models\QRCode as QRCodeModel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Get the base URL for QR codes
     */
    private function getBaseUrl(): string
    {
        // Check if we're in a local development environment
        if (app()->environment('local')) {
            // Try to get the actual server IP from environment or use a fallback
            $serverIp = config('app.qr_base_ip') ?? $_SERVER['SERVER_ADDR'] ?? '192.168.29.111';
            $port = $_SERVER['SERVER_PORT'] ?? '8000';
            return "http://{$serverIp}:{$port}";
        }
        
        // For production, use the configured app URL
        return config('app.url');
    }
    /**
     * Generate QR code for a specific item
     */
    public function generateItemQR(Item $item, string $tenantSlug, int $tenantId): string
    {
        $baseUrl = $this->getBaseUrl();
        $url = "{$baseUrl}/{$tenantSlug}/qr-order/item/{$item->id}";
        
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($url);
            
        $filename = "qr-codes/items/item-{$item->id}-" . time() . '.svg';
        Storage::disk('public')->put($filename, $qrCode);
        
        // Save to database
        QRCodeModel::create([
            'tenant_id' => $tenantId,
            'type' => 'item',
            'name' => $item->name,
            'file_path' => $filename,
            'url' => $url,
            'metadata' => ['item_id' => $item->id],
            'is_active' => true
        ]);
        
        return Storage::url($filename);
    }
    
    /**
     * Generate QR code for a category
     */
    public function generateCategoryQR(Category $category, string $tenantSlug, int $tenantId): string
    {
        $baseUrl = $this->getBaseUrl();
        $url = "{$baseUrl}/{$tenantSlug}/qr-order/category/{$category->id}";
        
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($url);
            
        $filename = "qr-codes/categories/category-{$category->id}-" . time() . '.svg';
        Storage::disk('public')->put($filename, $qrCode);
        
        // Save to database
        QRCodeModel::create([
            'tenant_id' => $tenantId,
            'type' => 'category',
            'name' => $category->name,
            'file_path' => $filename,
            'url' => $url,
            'metadata' => ['category_id' => $category->id],
            'is_active' => true
        ]);
        
        return Storage::url($filename);
    }
    
    /**
     * Generate QR code for the full menu
     */
    public function generateMenuQR(string $tenantSlug, int $tenantId): string
    {
        $baseUrl = $this->getBaseUrl();
        $url = "{$baseUrl}/{$tenantSlug}/qr-order/menu";
        
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($url);
            
        $filename = "qr-codes/menu-" . time() . '.svg';
        Storage::disk('public')->put($filename, $qrCode);
        
        // Save to database
        QRCodeModel::create([
            'tenant_id' => $tenantId,
            'type' => 'menu',
            'name' => 'Full Menu',
            'file_path' => $filename,
            'url' => $url,
            'metadata' => [],
            'is_active' => true
        ]);
        
        return Storage::url($filename);
    }
    
    /**
     * Generate QR code for table ordering
     */
    public function generateTableQR(string $tenantSlug, string $tableNo, int $tenantId): string
    {
        $baseUrl = $this->getBaseUrl();
        $url = "{$baseUrl}/{$tenantSlug}/qr-order/table/{$tableNo}";
        
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($url);
            
        $filename = "qr-codes/tables/table-{$tableNo}-" . time() . '.svg';
        Storage::disk('public')->put($filename, $qrCode);
        
        // Save to database
        QRCodeModel::create([
            'tenant_id' => $tenantId,
            'type' => 'table',
            'name' => "Table {$tableNo}",
            'file_path' => $filename,
            'url' => $url,
            'metadata' => ['table_no' => $tableNo],
            'is_active' => true
        ]);
        
        return Storage::url($filename);
    }
}
