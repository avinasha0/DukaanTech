<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Category;
use App\Models\Outlet;
use App\Models\QRCode as QRCodeModel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Base URL for links encoded inside QR images (customer scans).
     * Prefer the current request origin so LAN/mobile access matches how staff opened the app.
     */
    private function getEncodedLinkBaseUrl(): string
    {
        try {
            if (app()->bound('request')) {
                $request = request();
                if ($request && $request->getHost()) {
                    return rtrim($request->getSchemeAndHttpHost(), '/');
                }
            }
        } catch (\Throwable) {
            // fall through
        }

        return rtrim((string) config('app.url'), '/');
    }

    /**
     * Path for fetching the SVG over HTTP (same-origin; avoids APP_URL mismatch with Storage::url()).
     */
    private function publicStorageUrlPath(string $filename): string
    {
        return '/storage/' . ltrim($filename, '/');
    }
    /**
     * Generate QR code for a specific item
     */
    public function generateItemQR(Item $item, string $tenantSlug, int $tenantId): string
    {
        $baseUrl = $this->getEncodedLinkBaseUrl();
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
        
        return $this->publicStorageUrlPath($filename);
    }
    
    /**
     * Generate QR code for a category
     */
    public function generateCategoryQR(Category $category, string $tenantSlug, int $tenantId): string
    {
        $baseUrl = $this->getEncodedLinkBaseUrl();
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
        
        return $this->publicStorageUrlPath($filename);
    }
    
    /**
     * Generate QR code for the full menu
     */
    public function generateMenuQR(string $tenantSlug, int $tenantId): string
    {
        $baseUrl = $this->getEncodedLinkBaseUrl();
        $url = "{$baseUrl}/{$tenantSlug}/qr-order/menu";
        $firstOutlet = Outlet::where('tenant_id', $tenantId)->orderBy('id')->first();
        if ($firstOutlet) {
            $url .= '?outlet_id='.$firstOutlet->id;
        }
        
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
        
        return $this->publicStorageUrlPath($filename);
    }
    
    /**
     * Generate QR code for table ordering
     */
    public function generateTableQR(string $tenantSlug, string $tableNo, int $tenantId): string
    {
        $baseUrl = $this->getEncodedLinkBaseUrl();
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
        
        return $this->publicStorageUrlPath($filename);
    }
}
