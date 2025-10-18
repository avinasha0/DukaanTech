<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ShiftService;
use App\Models\Shift;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Account;
use App\Models\Outlet;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderType;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ShiftSummaryCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected $shiftService;
    protected $tenant;
    protected $outlet;
    protected $shift;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->shiftService = new ShiftService();
        
        // Create minimal test data
        $this->tenant = Account::create([
            'name' => 'Test Restaurant',
            'slug' => 'test-restaurant',
            'plan' => 'free'
        ]);
        
        $this->outlet = Outlet::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Main Outlet',
            'address' => '123 Test Street'
        ]);
        
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        
        $this->shift = Shift::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'opened_by' => $user->id,
            'opening_float' => 100.00
        ]);
        
        // Set tenant context
        app()->instance('tenant', $this->tenant);
        app()->instance('tenant.id', $this->tenant->id);
    }

    public function test_shift_summary_with_no_orders()
    {
        $summary = $this->shiftService->getShiftSummary($this->shift);
        
        $this->assertEquals(0, $summary['total_sales']);
        $this->assertEquals(0, $summary['total_orders']);
        $this->assertEquals(0, $summary['cash_sales']);
        $this->assertEquals(0, $summary['card_sales']);
        $this->assertEquals(0, $summary['upi_sales']);
        $this->assertEquals(100.00, $summary['opening_float']);
    }

    public function test_shift_summary_calculates_correctly_with_cash_orders()
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
        
        // Create order
        $order = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'cash',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        // Create order item
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $order->id,
            'item_id' => $item->id,
            'qty' => 2,
            'price' => 15.00,
            'tax_rate' => 10.0,
            'discount' => 0
        ]);
        
        $summary = $this->shiftService->getShiftSummary($this->shift);
        
        // Expected calculation:
        // Subtotal: 2 * 15.00 = 30.00
        // Tax: 30.00 * 0.10 = 3.00
        // Total: 30.00 + 3.00 = 33.00
        
        $this->assertEquals(33.00, $summary['total_sales']);
        $this->assertEquals(1, $summary['total_orders']);
        $this->assertEquals(33.00, $summary['cash_sales']);
        $this->assertEquals(0, $summary['card_sales']);
        $this->assertEquals(0, $summary['upi_sales']);
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

    public function test_shift_summary_with_discounts()
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

    public function test_shift_summary_calculation_formula()
    {
        // Test the exact calculation formula used in ShiftService
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
            'name' => 'Test Item',
            'price' => 10.00,
            'is_active' => true
        ]);
        
        $order = Order::create([
            'tenant_id' => $this->tenant->id,
            'outlet_id' => $this->outlet->id,
            'order_type_id' => $orderType->id,
            'payment_method' => 'cash',
            'status' => 'PAID',
            'state' => 'CLOSED'
        ]);
        
        // Test with: qty=3, price=10.00, tax_rate=15%, discount=2.00
        OrderItem::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $order->id,
            'item_id' => $item->id,
            'qty' => 3,
            'price' => 10.00,
            'tax_rate' => 15.0,
            'discount' => 2.00
        ]);
        
        $summary = $this->shiftService->getShiftSummary($this->shift);
        
        // Manual calculation:
        // subtotal = 3 * 10.00 = 30.00
        // modifierTotal = 0 (no modifiers)
        // tax = (30.00 + 0) * (15.0 / 100) = 30.00 * 0.15 = 4.50
        // total = 30.00 + 0 + 4.50 - 2.00 = 32.50
        
        $this->assertEquals(32.50, $summary['total_sales']);
        $this->assertEquals(1, $summary['total_orders']);
        $this->assertEquals(32.50, $summary['cash_sales']);
    }
}
