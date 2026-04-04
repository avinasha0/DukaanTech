<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Item;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\Outlet;
use App\Models\RestaurantTable;
use App\Services\OrderService;
use App\Services\QRCodeService;
use App\Services\QrPublicOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QROrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private QRCodeService $qrCodeService,
        private QrPublicOrderService $qrPublicOrderService
    ) {}

    /**
     * Display QR ordering interface for a specific tenant
     */
    public function showMenu(Request $request, string $tenantSlug)
    {
        $payload = $this->buildQrMenuPayload($request, $tenantSlug, null, false, null);
        if (isset($payload['error'])) {
            return view('qr-order.error', ['message' => $payload['error']]);
        }

        return view('qr-order.menu', $payload);
    }

    /**
     * Display items for a specific category
     */
    public function showCategory(Request $request, string $tenantSlug, Category $category)
    {
        $account = Account::where('slug', $tenantSlug)->first();

        if (! $account || $category->tenant_id !== $account->id) {
            return view('qr-order.error', ['message' => 'Category not found']);
        }

        $q = $request->query('outlet_id') ? '?outlet_id='.(int) $request->query('outlet_id') : '';

        return redirect("/{$tenantSlug}/qr-order/menu{$q}#category-{$category->id}");
    }

    /**
     * Display single item ordering page
     */
    public function showItem(Request $request, string $tenantSlug, Item $item)
    {
        $account = Account::where('slug', $tenantSlug)->first();

        if (! $account || $item->tenant_id !== $account->id) {
            return view('qr-order.error', ['message' => 'Item not found']);
        }

        $q = $request->query('outlet_id') ? '?outlet_id='.(int) $request->query('outlet_id') : '';

        return redirect("/{$tenantSlug}/qr-order/menu{$q}#item-{$item->id}");
    }

    /**
     * Display table-specific ordering page (legacy: table number or numeric id in URL)
     */
    public function showTable(Request $request, string $tenantSlug, string $tableNo)
    {
        $account = Account::where('slug', $tenantSlug)->first();
        if (! $account) {
            return view('qr-order.error', ['message' => 'Restaurant not found']);
        }

        $tableModel = RestaurantTable::where('tenant_id', $account->id)
            ->where(function ($q) use ($tableNo) {
                $q->where('name', $tableNo);
                if (ctype_digit((string) $tableNo)) {
                    $q->orWhere('id', (int) $tableNo);
                }
            })
            ->first();

        $tableNoLabel = $tableModel ? (string) $tableModel->name : $tableNo;
        $payload = $this->buildQrMenuPayload($request, $tenantSlug, $tableModel, true, $tableNoLabel);
        if (isset($payload['error'])) {
            return view('qr-order.error', ['message' => $payload['error']]);
        }

        return view('qr-order.menu', $payload);
    }

    /**
     * Table QR by restaurant_tables.id — stable unique link per physical table.
     */
    public function showTableById(Request $request, string $tenantSlug, int $tableId)
    {
        $account = Account::where('slug', $tenantSlug)->first();
        if (! $account) {
            return view('qr-order.error', ['message' => 'Restaurant not found']);
        }

        $tableModel = RestaurantTable::where('tenant_id', $account->id)
            ->whereKey($tableId)
            ->first();

        if (! $tableModel) {
            return view('qr-order.error', ['message' => 'Table not found']);
        }

        $payload = $this->buildQrMenuPayload($request, $tenantSlug, $tableModel, true, (string) $tableModel->name);
        if (isset($payload['error'])) {
            return view('qr-order.error', ['message' => $payload['error']]);
        }

        return view('qr-order.menu', $payload);
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildQrMenuPayload(
        Request $request,
        string $tenantSlug,
        ?RestaurantTable $tableModel,
        bool $fromTableQr,
        ?string $tableNoParam
    ): array {
        $account = Account::where('slug', $tenantSlug)->first();
        if (! $account) {
            return ['error' => 'Restaurant not found'];
        }

        $categories = Category::where('tenant_id', $account->id)
            ->whereHas('items', fn ($q) => $q->where('is_active', true))
            ->with(['items' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $outlets = Outlet::where('tenant_id', $account->id)->orderBy('id')->get();
        $requestedOutletId = $request->query('outlet_id');
        $defaultOutlet = $requestedOutletId
            ? ($outlets->firstWhere('id', (int) $requestedOutletId) ?? $outlets->first())
            : $outlets->first();

        if ($tableModel && $defaultOutlet && (int) $tableModel->outlet_id !== (int) $defaultOutlet->id) {
            $defaultOutlet = $outlets->firstWhere('id', $tableModel->outlet_id) ?? $defaultOutlet;
        }

        OrderType::ensureQrOrderingTypesExist($account->id);

        $orderTypes = OrderType::where('tenant_id', $account->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $orderTypes = OrderType::attachQrModes($orderTypes);
        // QR ordering: dine-in and pickup/take-away only — no delivery
        $orderTypes = OrderType::filterQrEligible($orderTypes);

        $qrApprovalEachSubmit = $account->qrApprovalEachSubmit();
        $forcedDineInOrderTypeId = $orderTypes->first(function ($ot) {
            return ($ot->qr_mode ?? '') === 'DINE_IN';
        })?->id;

        return [
            'account' => $account,
            'categories' => $categories,
            'orderTypes' => $orderTypes,
            'defaultOutlet' => $defaultOutlet,
            'fromTableQr' => $fromTableQr,
            'tableModel' => $tableModel,
            'tableNoParam' => $tableNoParam,
            'qrApprovalEachSubmit' => $qrApprovalEachSubmit,
            'forcedDineInOrderTypeId' => $forcedDineInOrderTypeId,
        ];
    }

    /**
     * Create order from QR ordering interface
     */
    public function createOrder(Request $request, string $tenantSlug)
    {
        try {
            $account = Account::where('slug', $tenantSlug)->first();

            if (! $account) {
                return response()->json(['error' => 'Restaurant not found'], 404);
            }

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
                'mode' => 'required|in:DINE_IN,TAKEAWAY,PICKUP',
                'table_no' => 'nullable|string|max:255',
                'table_id' => 'nullable|integer',
                'existing_order_id' => 'nullable|integer',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.qty' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();
            $result = $this->qrPublicOrderService->createOrAppend($data, $account);
            $order = $result['order'];

            return response()->json([
                'success' => true,
                'message' => $result['appended'] ? 'Items added to your order.' : 'Order placed successfully!',
                'order' => $order,
                'order_id' => $order->id,
                'appended' => $result['appended'],
                'created' => $result['created'],
            ], $result['created'] ? 201 : 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            \Log::error('QR Order Creation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: '.$e->getMessage(),
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
                $qrUrl = $this->qrCodeService->generateMenuQR($tenantSlug, $account->id);
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
                $tableId = $request->input('table_id');
                if ($tableId) {
                    $table = RestaurantTable::where('tenant_id', $account->id)->whereKey($tableId)->first();
                    if ($table) {
                        $qrUrl = $this->qrCodeService->generateTableQrByRestaurantTable($table, $tenantSlug, $account->id);
                    }
                } else {
                    $tableNo = $request->input('table_no');
                    if ($tableNo) {
                        $qrUrl = $this->qrCodeService->generateTableQR($tenantSlug, $tableNo, $account->id);
                    }
                }
                break;
        }

        if (!$qrUrl) {
            return response()->json(['error' => 'Failed to generate QR code'], 500);
        }

        return response()->json(['qr_url' => $qrUrl]);
    }
}
