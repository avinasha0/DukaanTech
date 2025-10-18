<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Item;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\Outlet;
use App\Services\OrderService;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QROrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private QRCodeService $qrCodeService
    ) {}

    /**
     * Display QR ordering interface for a specific tenant
     */
    public function showMenu(Request $request, string $tenantSlug)
    {
        $account = Account::where('slug', $tenantSlug)->first();
        
        if (!$account) {
            return view('qr-order.error', ['message' => 'Restaurant not found']);
        }

        $categories = Category::where('tenant_id', $account->id)
            ->with(['items' => function($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $outlets = Outlet::where('tenant_id', $account->id)->get();
        $orderTypes = OrderType::where('tenant_id', $account->id)->get();

        return view('qr-order.menu', compact('account', 'categories', 'outlets', 'orderTypes'));
    }

    /**
     * Display items for a specific category
     */
    public function showCategory(Request $request, string $tenantSlug, Category $category)
    {
        $account = Account::where('slug', $tenantSlug)->first();
        
        if (!$account || $category->tenant_id !== $account->id) {
            return view('qr-order.error', ['message' => 'Category not found']);
        }

        $items = $category->items()->where('is_active', true)->get();
        $outlets = Outlet::where('tenant_id', $account->id)->get();
        $orderTypes = OrderType::where('tenant_id', $account->id)->get();

        return view('qr-order.category', compact('account', 'category', 'items', 'outlets', 'orderTypes'));
    }

    /**
     * Display single item ordering page
     */
    public function showItem(Request $request, string $tenantSlug, Item $item)
    {
        $account = Account::where('slug', $tenantSlug)->first();
        
        if (!$account || $item->tenant_id !== $account->id) {
            return view('qr-order.error', ['message' => 'Item not found']);
        }

        $outlets = Outlet::where('tenant_id', $account->id)->get();
        $orderTypes = OrderType::where('tenant_id', $account->id)->get();

        return view('qr-order.item', compact('account', 'item', 'outlets', 'orderTypes'));
    }

    /**
     * Display table-specific ordering page
     */
    public function showTable(Request $request, string $tenantSlug, string $tableNo)
    {
        $account = Account::where('slug', $tenantSlug)->first();
        
        if (!$account) {
            return view('qr-order.error', ['message' => 'Restaurant not found']);
        }

        $categories = Category::where('tenant_id', $account->id)
            ->with(['items' => function($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $outlets = Outlet::where('tenant_id', $account->id)->get();
        $orderTypes = OrderType::where('tenant_id', $account->id)->get();

        return view('qr-order.table', compact('account', 'categories', 'outlets', 'orderTypes', 'tableNo'));
    }

    /**
     * Create order from QR ordering interface
     */
    public function createOrder(Request $request, string $tenantSlug)
    {
        try {
            $account = Account::where('slug', $tenantSlug)->first();
            
            if (!$account) {
                return response()->json(['error' => 'Restaurant not found'], 404);
            }

            // Set tenant context for the order service
            app()->instance('tenant.id', $account->id);
            app()->instance('tenant.model', $account);
            app()->instance('tenant', $account);

            $validator = Validator::make($request->all(), [
                'outlet_id' => 'required|exists:outlets,id',
                'order_type_id' => 'required|exists:order_types,id',
                'payment_method' => 'required|in:cash,card,upi',
                'customer_name' => 'nullable|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_address' => 'nullable|string',
                'delivery_address' => 'nullable|string',
                'delivery_fee' => 'nullable|numeric|min:0',
                'special_instructions' => 'nullable|string',
                'mode' => 'required|in:DINE_IN,TAKEAWAY,DELIVERY,PICKUP',
                'table_no' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.qty' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();
            $data['tenant_id'] = $account->id;
            $data['state'] = 'NEW';

            $order = $this->orderService->create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order' => $order,
                'order_id' => $order->id
            ], 201);
        } catch (\Exception $e) {
            \Log::error('QR Order Creation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR codes for admin interface
     */
    public function generateQR(Request $request, string $tenantSlug)
    {
        $account = Account::where('slug', $tenantSlug)->first();
        
        if (!$account) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }

        $type = $request->input('type', 'menu');
        $qrUrl = null;

        switch ($type) {
            case 'menu':
                $qrUrl = $this->qrCodeService->generateMenuQR($tenantSlug);
                break;
            case 'category':
                $categoryId = $request->input('category_id');
                $category = Category::where('id', $categoryId)->where('tenant_id', $account->id)->first();
                if ($category) {
                    $qrUrl = $this->qrCodeService->generateCategoryQR($category, $tenantSlug);
                }
                break;
            case 'item':
                $itemId = $request->input('item_id');
                $item = Item::where('id', $itemId)->where('tenant_id', $account->id)->first();
                if ($item) {
                    $qrUrl = $this->qrCodeService->generateItemQR($item, $tenantSlug);
                }
                break;
            case 'table':
                $tableNo = $request->input('table_no');
                $qrUrl = $this->qrCodeService->generateTableQR($tenantSlug, $tableNo);
                break;
        }

        if (!$qrUrl) {
            return response()->json(['error' => 'Failed to generate QR code'], 500);
        }

        return response()->json(['qr_url' => $qrUrl]);
    }
}
