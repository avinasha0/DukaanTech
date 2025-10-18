<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\QRCode;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class QRCodeController extends Controller
{
    public function __construct(
        private QRCodeService $qrCodeService
    ) {}

    /**
     * Display QR code management interface
     */
    public function index()
    {
        $tenant = app('tenant');
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        $categories = Category::where('tenant_id', $tenant->id)
            ->withCount('items')
            ->get();

        $items = Item::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->with('category')
            ->get();

        // Load saved QR codes
        $savedQRCodes = QRCode::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tenant.qr-codes.index', compact('tenant', 'categories', 'items', 'savedQRCodes'));
    }

    /**
     * Generate QR code for menu
     */
    public function generateMenuQR()
    {
        $tenant = app('tenant');
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        try {
            $qrUrl = $this->qrCodeService->generateMenuQR($tenant->slug, $tenant->id);
            return response()->json(['qr_url' => $qrUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate QR code: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate QR code for category
     */
    public function generateCategoryQR(Category $category)
    {
        $tenant = app('tenant');
        if (!$tenant || $category->tenant_id !== $tenant->id) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        try {
            $qrUrl = $this->qrCodeService->generateCategoryQR($category, $tenant->slug, $tenant->id);
            return response()->json(['qr_url' => $qrUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate QR code: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate QR code for item
     */
    public function generateItemQR(Item $item)
    {
        $tenant = app('tenant');
        if (!$tenant || $item->tenant_id !== $tenant->id) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        try {
            $qrUrl = $this->qrCodeService->generateItemQR($item, $tenant->slug, $tenant->id);
            return response()->json(['qr_url' => $qrUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate QR code: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate QR code for table
     */
    public function generateTableQR(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $request->validate([
            'table_no' => 'required|string|max:255'
        ]);

        try {
            $qrUrl = $this->qrCodeService->generateTableQR($tenant->slug, $request->table_no, $tenant->id);
            return response()->json(['qr_url' => $qrUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate QR code: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download QR code as image
     */
    public function downloadQR(Request $request)
    {
        $request->validate([
            'qr_url' => 'required|url'
        ]);

        $qrUrl = $request->qr_url;
        $filename = 'qr-code-' . time() . '.png';
        
        return response()->download(public_path(parse_url($qrUrl, PHP_URL_PATH)), $filename);
    }

    /**
     * Debug method to check QR codes
     */
    public function debugQRCodes()
    {
        $tenant = app('tenant');
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $allQRCodes = QRCode::where('tenant_id', $tenant->id)->get();
        
        return response()->json([
            'tenant_id' => $tenant->id,
            'tenant_slug' => $tenant->slug,
            'total_qr_codes' => $allQRCodes->count(),
            'qr_codes' => $allQRCodes->map(function($qr) {
                return [
                    'id' => $qr->id,
                    'name' => $qr->name,
                    'type' => $qr->type,
                    'is_active' => $qr->is_active,
                    'created_at' => $qr->created_at
                ];
            })->toArray()
        ]);
    }

    /**
     * Test delete method for debugging
     */
    public function testDelete(Request $request, $qrCodeId)
    {
        \Log::info('=== TEST DELETE METHOD CALLED ===', [
            'qr_code_id' => $qrCodeId,
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]);

        // Call the actual delete method
        return $this->deleteQR($request, $qrCodeId);
    }

    /**
     * Delete a QR code
     */
    public function deleteQR(Request $request, $qrCodeId)
    {
        \Log::info('=== QR CODE DELETE REQUEST START ===');
        \Log::info('Method parameters', [
            'qrCodeId_parameter' => $qrCodeId,
            'qrCodeId_type' => gettype($qrCodeId),
            'request_route_parameters' => $request->route() ? $request->route()->parameters() : 'No route',
            'request_all_parameters' => $request->all()
        ]);
        
        // Fix: Get the correct QR code ID from route parameters
        $routeParams = $request->route() ? $request->route()->parameters() : [];
        \Log::info('Route parameters debug', [
            'all_route_params' => $routeParams,
            'qrCodeId_from_param' => $qrCodeId,
            'tenant_from_route' => $routeParams['tenant'] ?? 'not_found',
            'qrCodeId_from_route' => $routeParams['qrCodeId'] ?? 'not_found'
        ]);
        
        // Use the correct QR code ID from route parameters
        if (isset($routeParams['qrCodeId'])) {
            $qrCodeId = $routeParams['qrCodeId'];
            \Log::info('Using QR code ID from route parameters', ['qrCodeId' => $qrCodeId]);
        } else {
            \Log::warning('QR code ID not found in route parameters, using method parameter', [
                'method_param' => $qrCodeId,
                'route_params' => $routeParams
            ]);
        }
        \Log::info('Request details', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'qr_code_id' => $qrCodeId,
            'qr_code_id_type' => gettype($qrCodeId),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);

        $tenant = app('tenant');
        if (!$tenant) {
            \Log::error('Tenant not found in deleteQR', [
                'qr_code_id' => $qrCodeId,
                'tenant_context' => app('tenant')
            ]);
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        \Log::info('Tenant context found', [
            'tenant_id' => $tenant->id,
            'tenant_slug' => $tenant->slug,
            'tenant_name' => $tenant->name ?? 'unknown'
        ]);

        try {
            // Convert to integer to ensure proper type
            $originalQrCodeId = $qrCodeId;
            $qrCodeId = (int) $qrCodeId;
            
            \Log::info('QR Code ID conversion', [
                'original_id' => $originalQrCodeId,
                'converted_id' => $qrCodeId,
                'original_type' => gettype($originalQrCodeId),
                'converted_type' => gettype($qrCodeId)
            ]);

            // Get all QR codes for this tenant for debugging
            \Log::info('Querying QR codes from database', [
                'tenant_id' => $tenant->id,
                'tenant_id_type' => gettype($tenant->id)
            ]);
            
            $allQRCodes = QRCode::where('tenant_id', $tenant->id)->get();
            \Log::info('Database query result', [
                'query_executed' => true,
                'count' => $allQRCodes->count(),
                'tenant_id_used' => $tenant->id,
                'qr_codes' => $allQRCodes->map(function($qr) {
                    return [
                        'id' => $qr->id,
                        'id_type' => gettype($qr->id),
                        'name' => $qr->name,
                        'type' => $qr->type,
                        'is_active' => $qr->is_active,
                        'tenant_id' => $qr->tenant_id,
                        'tenant_id_type' => gettype($qr->tenant_id)
                    ];
                })->toArray()
            ]);

            // Try to find the QR code
            \Log::info('Attempting to find specific QR code', [
                'searching_for_id' => $qrCodeId,
                'searching_for_id_type' => gettype($qrCodeId),
                'tenant_id' => $tenant->id,
                'tenant_id_type' => gettype($tenant->id)
            ]);
            
            $qrCode = QRCode::where('id', $qrCodeId)
                ->where('tenant_id', $tenant->id)
                ->first();
                
            \Log::info('QR Code search result', [
                'qr_code_id' => $qrCodeId,
                'tenant_id' => $tenant->id,
                'found' => $qrCode ? true : false,
                'query_executed' => true,
                'qr_code_data' => $qrCode ? [
                    'id' => $qrCode->id,
                    'id_type' => gettype($qrCode->id),
                    'name' => $qrCode->name,
                    'type' => $qrCode->type,
                    'is_active' => $qrCode->is_active,
                    'tenant_id' => $qrCode->tenant_id,
                    'tenant_id_type' => gettype($qrCode->tenant_id)
                ] : null
            ]);

            if (!$qrCode) {
                \Log::warning('QR Code not found with primary search, trying alternatives');
                
                // Try alternative search methods
                \Log::info('Trying string ID search', [
                    'string_id' => (string) $qrCodeId,
                    'tenant_id' => $tenant->id
                ]);
                
                $qrCode = QRCode::where('id', (string) $qrCodeId)
                    ->where('tenant_id', $tenant->id)
                    ->first();
                    
                if (!$qrCode) {
                    \Log::info('Trying string tenant ID search', [
                        'qr_code_id' => $qrCodeId,
                        'string_tenant_id' => (string) $tenant->id
                    ]);
                    
                    $qrCode = QRCode::where('id', $qrCodeId)
                        ->where('tenant_id', (string) $tenant->id)
                        ->first();
                }
                
                if (!$qrCode) {
                    \Log::error('QR Code not found after all search attempts', [
                        'qr_code_id' => $qrCodeId,
                        'qr_code_id_type' => gettype($qrCodeId),
                        'tenant_id' => $tenant->id,
                        'tenant_id_type' => gettype($tenant->id),
                        'all_available_qr_codes' => $allQRCodes->toArray(),
                        'available_ids' => $allQRCodes->pluck('id')->toArray(),
                        'available_tenant_ids' => $allQRCodes->pluck('tenant_id')->unique()->toArray()
                    ]);
                    
                    return response()->json([
                        'error' => 'QR code not found',
                        'debug' => [
                            'qr_code_id' => $qrCodeId,
                            'tenant_id' => $tenant->id,
                            'available_ids' => $allQRCodes->pluck('id')->toArray(),
                            'search_methods_tried' => ['integer_id', 'string_id', 'string_tenant_id']
                        ]
                    ], 404);
                } else {
                    \Log::info('QR Code found with alternative search method', [
                        'qr_code_id' => $qrCode->id,
                        'search_method_used' => 'alternative'
                    ]);
                }
            }

            // Check if it's already inactive
            if (!$qrCode->is_active) {
                \Log::info('QR Code already inactive', [
                    'qr_code_id' => $qrCodeId,
                    'is_active' => $qrCode->is_active,
                    'qr_code_name' => $qrCode->name
                ]);
                return response()->json(['error' => 'QR code is already deleted'], 400);
            }

            \Log::info('QR Code found and active, proceeding with deletion', [
                'qr_code_id' => $qrCode->id,
                'qr_code_name' => $qrCode->name,
                'qr_code_type' => $qrCode->type,
                'file_path' => $qrCode->file_path
            ]);

            // Delete the file from storage
            $fileExists = Storage::disk('public')->exists($qrCode->file_path);
            \Log::info('File deletion check', [
                'file_path' => $qrCode->file_path,
                'file_exists' => $fileExists
            ]);
            
            if ($fileExists) {
                $deleted = Storage::disk('public')->delete($qrCode->file_path);
                \Log::info('File deletion result', [
                    'file_path' => $qrCode->file_path,
                    'deleted' => $deleted
                ]);
            }

            // Mark as inactive instead of deleting
            \Log::info('Updating QR code to inactive', [
                'qr_code_id' => $qrCode->id,
                'current_is_active' => $qrCode->is_active
            ]);
            
            $updateResult = $qrCode->update(['is_active' => false]);
            
            \Log::info('QR Code update result', [
                'qr_code_id' => $qrCode->id,
                'update_successful' => $updateResult,
                'new_is_active' => $qrCode->fresh()->is_active
            ]);

            \Log::info('=== QR CODE DELETE SUCCESS ===', [
                'qr_code_id' => $qrCode->id,
                'qr_code_name' => $qrCode->name
            ]);

            return response()->json(['message' => 'QR code deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('=== QR CODE DELETE EXCEPTION ===', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'qr_code_id' => $qrCodeId,
                'tenant_id' => $tenant->id ?? 'not_found',
                'request_data' => $request->all()
            ]);
            return response()->json(['error' => 'Failed to delete QR code: ' . $e->getMessage()], 500);
        }
    }
}
