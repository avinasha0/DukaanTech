<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Account;
use App\Models\Outlet;
use App\Models\Shift;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\Category;
use App\Models\OrderType;
use App\Models\User;
use App\Services\ShiftService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShiftCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $outlet;
    protected $shift;
    protected $shiftService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create tenant
        $this->tenant = Account::create([
            'name' => 'Test Restaurant',
            'slug' => 'test-restaurant',
            'plan' => 'free'
        ]);
        
        // Set tenant context
        app()->instance('tenant', $this->tenant);
        app()->instance('tenant.id', $this->tenant->id);
        
        // Create outlet
        $this->outlet = Outlet::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Main Outlet',
            'address' => '123 Test Street'
        ]);
        
        // Create user
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        
        // Create shift
        $this->shift = Shift::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'opened_by' => $user->id,
            'opening_float' => 100.00
        ]);
        
        $this->shiftService = new ShiftService();
    }

    public function test_shift_summary_with_no_orders_returns_zero_values()
    {
        $summary = $this->shiftService->getShiftSummary($this->shift);
        
        $this->assertEquals(0, $summary['total_sales']);
        $this->assertEquals(0, $summary['total_orders']);
        $this->assertEquals(0, $summary['cash_sales']);
        $this->assertEquals(0, $summary['card_sales']);
        $this->assertEquals(0, $summary['upi_sales']);
        $this->assertEquals(100.00, $summary['opening_float']);
    }

    public function test_shift_summary_with_cash_orders_calculates_correctly()
    {
        // Create category
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Food'
        ]);
        
        // Create order type
        $orderType = OrderType::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Dine In'
        ]);
        
        // Create items with prices
        $item1 = Item::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Burger',
            'price' => 15.00, // Use direct price for testing
            'is_active' => true
        ]);
        
        $item2 = Item::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Fries',
            'price' => 8.00, // Use direct price for testing
            'is_active' => true
        ]);
        
        // Create orders
        $order1 = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'cash',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        $order2 = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'cash',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        // Create order items
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $order1->id,
            'item_id' => $item1->id,
            'qty' => 2,
            'price' => 15.00,
            'tax_rate' => 10.0,
            'discount' => 0
        ]);
        
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $order2->id,
            'item_id' => $item2->id,
            'qty' => 1,
            'price' => 8.00,
            'tax_rate' => 10.0,
            'discount' => 0
        ]);
        
        $summary = $this->shiftService->getShiftSummary($this->shift);
        
        // Expected calculations:
        // Order 1: 2 * 15.00 + (2 * 15.00 * 0.10) = 30.00 + 3.00 = 33.00
        // Order 2: 1 * 8.00 + (1 * 8.00 * 0.10) = 8.00 + 0.80 = 8.80
        // Total: 33.00 + 8.80 = 41.80
        
        $this->assertEquals(41.80, $summary['total_sales']);
        $this->assertEquals(2, $summary['total_orders']);
        $this->assertEquals(41.80, $summary['cash_sales']);
        $this->assertEquals(0, $summary['card_sales']);
        $this->assertEquals(0, $summary['upi_sales']);
        $this->assertEquals(100.00, $summary['opening_float']);
    }

    public function test_shift_summary_with_mixed_payment_methods()
    {
        // Create category and order type
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Food'
        ]);
        
        $orderType = OrderType::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Dine In'
        ]);
        
        // Create item
        $item = Item::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Pizza',
            'price' => 20.00,
            'is_active' => true
        ]);
        
        // Create orders with different payment methods
        $cashOrder = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'cash',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        $cardOrder = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'card',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        $upiOrder = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'upi',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        // Create order items
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $cashOrder->id,
            'item_id' => $item->id,
            'qty' => 1,
            'price' => 20.00,
            'tax_rate' => 0,
            'discount' => 0
        ]);
        
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $cardOrder->id,
            'item_id' => $item->id,
            'qty' => 2,
            'price' => 20.00,
            'tax_rate' => 0,
            'discount' => 0
        ]);
        
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $upiOrder->id,
            'item_id' => $item->id,
            'qty' => 1,
            'price' => 20.00,
            'tax_rate' => 0,
            'discount' => 0
        ]);
        
        $summary = $this->shiftService->getShiftSummary($this->shift);
        
        // Expected calculations:
        // Cash: 1 * 20.00 = 20.00
        // Card: 2 * 20.00 = 40.00
        // UPI: 1 * 20.00 = 20.00
        // Total: 20.00 + 40.00 + 20.00 = 80.00
        
        $this->assertEquals(80.00, $summary['total_sales']);
        $this->assertEquals(3, $summary['total_orders']);
        $this->assertEquals(20.00, $summary['cash_sales']);
        $this->assertEquals(40.00, $summary['card_sales']);
        $this->assertEquals(20.00, $summary['upi_sales']);
    }

    public function test_shift_summary_excludes_cancelled_orders()
    {
        // Create category and order type
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Food'
        ]);
        
        $orderType = OrderType::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Dine In'
        ]);
        
        // Create item
        $item = Item::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Burger',
            'price' => 15.00,
            'is_active' => true
        ]);
        
        // Create paid order
        $paidOrder = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'cash',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        // Create cancelled order
        $cancelledOrder = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'cash',
            'status' => 'CANCELLED',
            'state' => 'CLOSED'
        ]);
        
        // Create order items
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $paidOrder->id,
            'item_id' => $item->id,
            'qty' => 1,
            'price' => 15.00,
            'tax_rate' => 0,
            'discount' => 0
        ]);
        
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $cancelledOrder->id,
            'item_id' => $item->id,
            'qty' => 2,
            'price' => 15.00,
            'tax_rate' => 0,
            'discount' => 0
        ]);
        
        $summary = $this->shiftService->getShiftSummary($this->shift);
        
        // Should only include the paid order, not the cancelled one
        $this->assertEquals(15.00, $summary['total_sales']);
        $this->assertEquals(1, $summary['total_orders']);
        $this->assertEquals(15.00, $summary['cash_sales']);
    }

    public function test_shift_summary_with_discounts_and_modifiers()
    {
        // Create category and order type
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Food'
        ]);
        
        $orderType = OrderType::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Dine In'
        ]);
        
        // Create item
        $item = Item::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Burger',
            'price' => 20.00,
            'is_active' => true
        ]);
        
        // Create order
        $order = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'cash',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        // Create order item with discount
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $order->id,
            'item_id' => $item->id,
            'qty' => 2,
            'price' => 20.00,
            'tax_rate' => 10.0,
            'discount' => 5.00
        ]);
        
        $summary = $this->shiftService->getShiftSummary($this->shift);
        
        // Expected calculation:
        // Subtotal: 2 * 20.00 = 40.00
        // Tax: 40.00 * 0.10 = 4.00
        // Total before discount: 40.00 + 4.00 = 44.00
        // After discount: 44.00 - 5.00 = 39.00
        
        $this->assertEquals(39.00, $summary['total_sales']);
        $this->assertEquals(1, $summary['total_orders']);
        $this->assertEquals(39.00, $summary['cash_sales']);
    }

    public function test_shift_summary_api_endpoint()
    {
        // Create category, order type, and item
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Food'
        ]);
        
        $orderType = OrderType::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Dine In'
        ]);
        
        $item = Item::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Burger',
            'price' => 15.00,
            'is_active' => true
        ]);
        
        // Create order
        $order = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'cash',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $order->id,
            'item_id' => $item->id,
            'qty' => 1,
            'price' => 15.00,
            'tax_rate' => 0,
            'discount' => 0
        ]);
        
        // Test API endpoint
        $response = $this->get("/{$this->tenant->slug}/api/dashboard/shift/current?outlet_id={$this->outlet->id}");
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'shift',
            'summary' => [
                'total_sales',
                'total_orders',
                'cash_sales',
                'card_sales',
                'upi_sales',
                'opening_float'
            ]
        ]);
        
        $data = $response->json();
        $this->assertEquals(15.00, $data['summary']['total_sales']);
        $this->assertEquals(1, $data['summary']['total_orders']);
        $this->assertEquals(15.00, $data['summary']['cash_sales']);
    }
}