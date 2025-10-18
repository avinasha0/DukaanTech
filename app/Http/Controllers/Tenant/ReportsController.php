<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Models\Bill;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function index()
    {
        $tenant = app('tenant');
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        $outlets = Outlet::where('tenant_id', app('tenant.id'))->get();
        
        return view('tenant.reports.index', compact('tenant', 'outlets'));
    }

    public function logError(Request $request)
    {
        try {
            $logData = $request->all();
            
            \Log::channel('daily')->info('Frontend Report Error', [
                'timestamp' => $logData['timestamp'] ?? now()->toISOString(),
                'level' => $logData['level'] ?? 'INFO',
                'message' => $logData['message'] ?? 'No message',
                'data' => $logData['data'] ?? null,
                'url' => $logData['url'] ?? 'Unknown',
                'user_agent' => $logData['userAgent'] ?? 'Unknown',
                'tenant_id' => app('tenant.id'),
                'user_id' => auth()->id() ?? 'Guest'
            ]);
            
            return response()->json(['status' => 'logged']);
        } catch (\Exception $e) {
            \Log::error('Failed to log frontend error', [
                'error' => $e->getMessage(),
                'original_data' => $request->all()
            ]);
            
            return response()->json(['status' => 'failed'], 500);
        }
    }

    public function logs(Request $request)
    {
        try {
            $level = $request->get('level');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            
            // Read log files
            $logPath = storage_path('logs/laravel.log');
            $logs = [];
            
            if (file_exists($logPath)) {
                $logContent = file_get_contents($logPath);
                $logLines = explode("\n", $logContent);
                
                foreach ($logLines as $line) {
                    if (empty(trim($line))) continue;
                    
                    // Parse log line
                    if (preg_match('/^\[(.*?)\].*?(\w+):\s*(.*)$/', $line, $matches)) {
                        $timestamp = $matches[1];
                        $logLevel = $matches[2];
                        $message = $matches[3];
                        
                        // Filter by level if specified
                        if ($level && $logLevel !== $level) continue;
                        
                        // Filter by date if specified
                        if ($dateFrom || $dateTo) {
                            $logDate = date('Y-m-d', strtotime($timestamp));
                            if ($dateFrom && $logDate < $dateFrom) continue;
                            if ($dateTo && $logDate > $dateTo) continue;
                        }
                        
                        $logs[] = [
                            'timestamp' => $timestamp,
                            'level' => $logLevel,
                            'message' => $message,
                            'data' => null
                        ];
                    }
                }
            }
            
            // Get stats
            $stats = [
                'errors' => count(array_filter($logs, fn($log) => $log['level'] === 'ERROR')),
                'warnings' => count(array_filter($logs, fn($log) => $log['level'] === 'WARNING')),
                'reports' => count(array_filter($logs, fn($log) => strpos($log['message'], 'report') !== false))
            ];
            
            return response()->json([
                'logs' => array_slice($logs, -50), // Last 50 logs
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to read logs', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to read logs'], 500);
        }
    }

    public function generateTestData(Request $request)
    {
        try {
            \Log::info('Generating test data for reports');
            
            // Run the seeder
            $seeder = new \Database\Seeders\ReportTestDataSeeder();
            $seeder->run();
            
            \Log::info('Test data generation completed successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Test data generated successfully! You can now generate reports with real data for the last 7 days.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to generate test data', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate test data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sales(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'report_type' => 'required|in:daily,hourly,summary'
        ]);

        $outletId = $request->outlet_id;
        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);
        $reportType = $request->report_type;

        $data = [];

        switch ($reportType) {
            case 'daily':
                $data = $this->getDailySalesReport($outletId, $dateFrom, $dateTo);
                break;
            case 'hourly':
                $data = $this->getHourlySalesReport($outletId, $dateFrom, $dateTo);
                break;
            case 'summary':
                $data = $this->getSalesSummaryReport($outletId, $dateFrom, $dateTo);
                break;
        }

        return response()->json($data);
    }

    public function topItems(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'limit' => 'integer|min:1|max:50'
        ]);

        $outletId = $request->outlet_id;
        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);
        $limit = $request->get('limit', 10);

        $topItems = $this->reportService->getTopSellingItems($outletId, $dateFrom, $dateTo, $limit);

        return response()->json($topItems);
    }

    public function shiftReport(Request $request)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id'
        ]);

        $shift = Shift::findOrFail($request->shift_id);
        
        // Verify shift belongs to current tenant
        if ($shift->tenant_id !== app('tenant.id')) {
            abort(403, 'Unauthorized access to shift');
        }

        $report = $this->reportService->getShiftReport($shift);

        return response()->json($report);
    }

    public function orderSummary(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'order_types' => 'array',
            'order_types.*' => 'exists:order_types,id'
        ]);

        $outletId = $request->outlet_id;
        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);
        $orderTypes = $request->get('order_types', []);

        $report = $this->reportService->getOrderSummaryReport($outletId, $dateFrom, $dateTo, $orderTypes);

        return response()->json($report);
    }

    public function export(Request $request)
    {
        try {
        $request->validate([
            'report_type' => 'required|in:sales,top_items,shift,order_summary',
            'format' => 'required|in:pdf,csv,excel',
            'outlet_id' => 'required|exists:outlets,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from'
        ]);

        $reportType = $request->report_type;
        $format = $request->format;
        $outletId = $request->outlet_id;
        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);

            \Log::info('Export request', [
                'report_type' => $reportType,
                'format' => $format,
                'outlet_id' => $outletId,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ]);

            // Check if dates are in the future - don't allow
            if ($dateFrom->isFuture() || $dateTo->isFuture()) {
                \Log::warning('Future dates detected, rejecting request');
                return response()->json([
                    'error' => 'Future dates are not allowed',
                    'message' => 'Please select dates from today or earlier.'
                ], 400);
            }

            // For today's date, calculate up to current time
            if ($dateTo->isToday()) {
                $dateTo = now(); // Use current time for today
                \Log::info('Today date detected, using current time', [
                    'original_date_to' => $request->date_to,
                    'adjusted_date_to' => $dateTo->format('Y-m-d H:i:s')
                ]);
            }

            // Generate report data from actual database
        $data = $this->generateReportData($reportType, $outletId, $dateFrom, $dateTo);

            \Log::info('Generated report data', [
                'data_count' => is_array($data) ? count($data) : 'not_array',
                'report_type' => $reportType,
                'outlet_id' => $outletId,
                'date_from' => $dateFrom->format('Y-m-d'),
                'date_to' => $dateTo->format('Y-m-d H:i:s'),
                'has_data' => $this->hasReportData($data, $reportType)
            ]);

        // Return appropriate format
        switch ($format) {
            case 'pdf':
                return $this->generatePDF($data, $reportType, $dateFrom, $dateTo);
            case 'csv':
                return $this->generateCSV($data, $reportType, $dateFrom, $dateTo);
            case 'excel':
                return $this->generateExcel($data, $reportType, $dateFrom, $dateTo);
                default:
                    return response()->json(['error' => 'Invalid format specified'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Export error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Export failed: ' . $e->getMessage(),
                'message' => 'Please check the logs for more details.'
            ], 500);
        }
    }

    private function getDailySalesReport($outletId, $dateFrom, $dateTo)
    {
        $dailyData = [];
        $currentDate = $dateFrom->copy();

        while ($currentDate->lte($dateTo)) {
            $dayReport = $this->reportService->getDailySales($outletId, $currentDate);
            $dailyData[] = $dayReport;
            $currentDate->addDay();
        }

        return [
            'period' => [
                'from' => $dateFrom->format('Y-m-d'),
                'to' => $dateTo->format('Y-m-d')
            ],
            'summary' => [
                'total_sales' => collect($dailyData)->sum('total_sales'),
                'total_bills' => collect($dailyData)->sum('total_bills'),
                'average_bill_value' => collect($dailyData)->avg('average_bill_value'),
                'total_tax' => collect($dailyData)->sum('tax_total'),
                'total_discount' => collect($dailyData)->sum('discount_total')
            ],
            'daily_data' => $dailyData
        ];
    }

    private function getHourlySalesReport($outletId, $dateFrom, $dateTo)
    {
        $hourlyData = [];
        $currentDate = $dateFrom->copy();

        while ($currentDate->lte($dateTo)) {
            $dayHourlyData = $this->reportService->getHourlySales($outletId, $currentDate);
            $hourlyData[$currentDate->format('Y-m-d')] = $dayHourlyData;
            $currentDate->addDay();
        }

        return [
            'period' => [
                'from' => $dateFrom->format('Y-m-d'),
                'to' => $dateTo->format('Y-m-d')
            ],
            'hourly_data' => $hourlyData
        ];
    }

    private function getSalesSummaryReport($outletId, $dateFrom, $dateTo)
    {
        // Query orders instead of bills since bills table is empty
        $orders = Order::where('tenant_id', app('tenant.id'))
            ->where('outlet_id', $outletId)
            ->where('status', 'PAID') // Only get paid orders
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('items')
            ->get();
            
        \Log::info('Sales summary query result', [
            'orders_count' => $orders->count(),
            'total_sales' => $orders->sum('total'),
            'outlet_id' => $outletId,
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateTo->format('Y-m-d')
        ]);
        
        // Calculate totals from orders
        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Calculate payment methods from orders
        $paymentMethods = [
            'cash' => $orders->where('payment_method', 'CASH')->sum('total'),
            'card' => $orders->where('payment_method', 'CARD')->sum('total'),
            'upi' => $orders->where('payment_method', 'UPI')->sum('total'),
            'wallet' => $orders->where('payment_method', 'WALLET')->sum('total'),
            'other' => $orders->where('payment_method', 'OTHER')->sum('total'),
        ];
        
        // Calculate tax and discount from order items
        $taxTotal = $orders->sum(function($order) {
            return $order->items->sum(function($item) {
                return ($item->qty * $item->price) * ($item->tax_rate / 100);
            });
        });
        
        $discountTotal = $orders->sum(function($order) {
            return $order->items->sum('discount');
        });
        
        $summary = [
            'period' => [
                'from' => $dateFrom->format('Y-m-d'),
                'to' => $dateTo->format('Y-m-d')
            ],
            'total_sales' => $totalSales,
            'total_bills' => $totalOrders,
            'average_bill_value' => $averageOrderValue,
            'payment_methods' => $paymentMethods,
            'total_tax' => $taxTotal,
            'total_discount' => $discountTotal
        ];

        return $summary;
    }

    private function generateReportData($reportType, $outletId, $dateFrom, $dateTo)
    {
        $data = [];
        
        switch ($reportType) {
            case 'sales':
                $data = $this->getSalesSummaryReport($outletId, $dateFrom, $dateTo);
                break;
            case 'top_items':
                $data = $this->reportService->getTopSellingItems($outletId, $dateFrom, $dateTo, 20);
                break;
            case 'shift':
                // For shift reports, we'd need to get all shifts in the date range
                $shifts = Shift::where('tenant_id', app('tenant.id'))
                    ->where('outlet_id', $outletId)
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->get();
                
                $data = $shifts->map(function ($shift) {
                    return $this->reportService->getShiftReport($shift);
                })->toArray();
                break;
            case 'order_summary':
                $data = $this->reportService->getOrderSummaryReport($outletId, $dateFrom, $dateTo);
                break;
        }
        
        // Add outlet_id to the data for PDF generation
        $data['outlet_id'] = $outletId;
        
        return $data;
    }

    private function generateSampleData($reportType, $outletId, $dateFrom, $dateTo)
    {
        \Log::info('Generating sample data for testing', [
            'report_type' => $reportType,
            'outlet_id' => $outletId,
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateTo->format('Y-m-d')
        ]);

        switch ($reportType) {
            case 'sales':
                return [
                    'is_sample_data' => true,
                    'outlet_id' => $outletId,
                    'total_sales' => 12500.00,
                    'total_bills' => 45,
                    'average_bill_value' => 277.78,
                    'payment_methods' => [
                        'cash' => 4500.00,
                        'card' => 6200.00,
                        'upi' => 1500.00,
                        'wallet' => 200.00,
                        'other' => 100.00,
                    ],
                    'tax_total' => 1250.00,
                    'discount_total' => 300.00,
                    'sample_note' => 'This is sample data generated for future dates testing'
                ];
                
            case 'top_items':
                return [
                    'is_sample_data' => true,
                    'outlet_id' => $outletId,
                    [
                        'item' => ['name' => 'Chicken Biryani'],
                        'total_qty' => 25,
                        'total_revenue' => 3750.00,
                        'order_count' => 18
                    ],
                    [
                        'item' => ['name' => 'Mutton Curry'],
                        'total_qty' => 18,
                        'total_revenue' => 2700.00,
                        'order_count' => 12
                    ],
                    [
                        'item' => ['name' => 'Fish Fry'],
                        'total_qty' => 15,
                        'total_revenue' => 2250.00,
                        'order_count' => 10
                    ],
                    [
                        'item' => ['name' => 'Vegetable Biryani'],
                        'total_qty' => 12,
                        'total_revenue' => 1800.00,
                        'order_count' => 8
                    ],
                    [
                        'item' => ['name' => 'Chicken Tikka'],
                        'total_qty' => 10,
                        'total_revenue' => 1500.00,
                        'order_count' => 7
                    ]
                ];
                
            case 'shift':
                return [
                    'is_sample_data' => true,
                    'outlet_id' => $outletId,
                    [
                        'shift_id' => 1,
                        'opened_at' => $dateFrom->format('Y-m-d') . ' 09:00:00',
                        'closed_at' => $dateFrom->format('Y-m-d') . ' 18:00:00',
                        'opened_by' => 'John Manager',
                        'total_sales' => 8500.00,
                        'total_bills' => 28,
                        'payment_breakdown' => [
                            'cash' => 3200.00,
                            'card' => 4200.00,
                            'upi' => 1000.00,
                            'wallet' => 100.00,
                            'other' => 0.00,
                        ],
                        'cash_management' => [
                            'opening_float' => 500.00,
                            'expected_cash' => 3700.00,
                            'actual_cash' => 3650.00,
                            'variance' => -50.00,
                        ]
                    ],
                    [
                        'shift_id' => 2,
                        'opened_at' => $dateTo->format('Y-m-d') . ' 09:00:00',
                        'closed_at' => null,
                        'opened_by' => 'Sarah Assistant',
                        'total_sales' => 4000.00,
                        'total_bills' => 17,
                        'payment_breakdown' => [
                            'cash' => 1300.00,
                            'card' => 2000.00,
                            'upi' => 500.00,
                            'wallet' => 100.00,
                            'other' => 100.00,
                        ],
                        'cash_management' => [
                            'opening_float' => 500.00,
                            'expected_cash' => 1800.00,
                            'actual_cash' => null,
                            'variance' => null,
                        ]
                    ]
                ];
                
            case 'order_summary':
                return [
                    'is_sample_data' => true,
                    'outlet_id' => $outletId,
                    'period' => [
                        'from' => $dateFrom->format('Y-m-d'),
                        'to' => $dateTo->format('Y-m-d')
                    ],
                    'summary' => [
                        'total_orders' => 45,
                        'total_revenue' => 12500.00,
                        'average_order_value' => 277.78,
                        'payment_methods' => [
                            'CASH' => 4500.00,
                            'CARD' => 6200.00,
                            'UPI' => 1500.00,
                            'WALLET' => 200.00,
                            'OTHER' => 100.00,
                        ],
                    ],
                    'order_types' => [
                        [
                            'order_type' => ['name' => 'Dine In'],
                            'total_orders' => 25,
                            'total_revenue' => 7500.00,
                            'average_order_value' => 300.00,
                            'table_numbers' => ['1', '2', '3', '4', '5'],
                            'delivery_addresses' => []
                        ],
                        [
                            'order_type' => ['name' => 'Takeaway'],
                            'total_orders' => 15,
                            'total_revenue' => 3500.00,
                            'average_order_value' => 233.33,
                            'table_numbers' => [],
                            'delivery_addresses' => []
                        ],
                        [
                            'order_type' => ['name' => 'Delivery'],
                            'total_orders' => 5,
                            'total_revenue' => 1500.00,
                            'average_order_value' => 300.00,
                            'table_numbers' => [],
                            'delivery_addresses' => ['123 Main St', '456 Oak Ave', '789 Pine Rd']
                        ]
                    ],
                    'daily_breakdown' => [
                        [
                            'date' => $dateFrom->format('Y-m-d'),
                            'total_orders' => 28,
                            'total_revenue' => 8500.00,
                            'order_types' => [
                                ['name' => 'Dine In', 'count' => 15, 'revenue' => 5000.00],
                                ['name' => 'Takeaway', 'count' => 10, 'revenue' => 2500.00],
                                ['name' => 'Delivery', 'count' => 3, 'revenue' => 1000.00]
                            ]
                        ],
                        [
                            'date' => $dateTo->format('Y-m-d'),
                            'total_orders' => 17,
                            'total_revenue' => 4000.00,
                            'order_types' => [
                                ['name' => 'Dine In', 'count' => 10, 'revenue' => 2500.00],
                                ['name' => 'Takeaway', 'count' => 5, 'revenue' => 1000.00],
                                ['name' => 'Delivery', 'count' => 2, 'revenue' => 500.00]
                            ]
                        ]
                    ]
                ];
                
            default:
                return [
                    'is_sample_data' => true,
                    'outlet_id' => $outletId,
                    'message' => 'No sample data available for this report type',
                    'report_type' => $reportType
                ];
        }
    }

    private function hasReportData($data, $reportType)
    {
        switch ($reportType) {
            case 'sales':
                return isset($data['total_sales']) && $data['total_sales'] > 0;
            case 'top_items':
                return is_array($data) && count($data) > 0;
            case 'shift':
                return is_array($data) && count($data) > 0;
            case 'order_summary':
                return isset($data['summary']['total_orders']) && $data['summary']['total_orders'] > 0;
            default:
                return false;
        }
    }

    private function generatePDF($data, $reportType, $dateFrom, $dateTo)
    {
        try {
            $outlet = Outlet::find($data['outlet_id'] ?? null);
            $outletName = $outlet ? $outlet->name : 'All Outlets';
            
            $html = $this->generateReportHTML($data, $reportType, $dateFrom, $dateTo, $outletName);
            
            // Use Laravel's built-in PDF generation or a package like dompdf
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            
            $filename = strtolower($reportType) . '_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            // If PDF generation fails, return JSON response with error
        return response()->json([
                'error' => 'PDF generation failed: ' . $e->getMessage(),
                'data' => $data,
                'message' => 'PDF export is not available. Please try CSV or Excel format.'
        ]);
        }
    }

    private function generateCSV($data, $reportType, $dateFrom, $dateTo)
    {
        $filename = strtolower($reportType) . '_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $reportType) {
            $file = fopen('php://output', 'w');
            
            switch ($reportType) {
                case 'sales':
                    $this->generateSalesCSV($file, $data);
                    break;
                case 'top_items':
                    $this->generateTopItemsCSV($file, $data);
                    break;
                case 'shift':
                    $this->generateShiftCSV($file, $data);
                    break;
                case 'order_summary':
                    $this->generateOrderSummaryCSV($file, $data);
                    break;
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    private function generateExcel($data, $reportType, $dateFrom, $dateTo)
    {
        $filename = strtolower($reportType) . '_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.xlsx';
        
        // For Excel generation, we'll return a simple CSV for now
        // In a real implementation, you'd use a package like PhpSpreadsheet
        return $this->generateCSV($data, $reportType, $dateFrom, $dateTo);
    }

    private function generateReportHTML($data, $reportType, $dateFrom, $dateTo, $outletName)
    {
        $isSampleData = isset($data['is_sample_data']) && $data['is_sample_data'];
        $sampleNote = $isSampleData ? '<div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; border-radius: 5px; margin-bottom: 20px; color: #856404;"><strong>Note:</strong> This report contains sample data for testing purposes as the selected dates are in the future.</div>' : '';
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Report - ' . ucfirst($reportType) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #6E46AE; margin: 0; }
                .header p { color: #666; margin: 5px 0; }
                .summary { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
                .summary-item { text-align: center; }
                .summary-item h3 { margin: 0; color: #333; font-size: 24px; }
                .summary-item p { margin: 5px 0; color: #666; }
                .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                .table th, .table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                .table th { background-color: #6E46AE; color: white; }
                .table tr:nth-child(even) { background-color: #f2f2f2; }
                .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . ucfirst(str_replace('_', ' ', $reportType)) . ' Report</h1>
                <p>Outlet: ' . $outletName . '</p>
                <p>Period: ' . $dateFrom->format('M d, Y') . ' to ' . $dateTo->format('M d, Y') . '</p>
                <p>Generated on: ' . now()->format('M d, Y H:i:s') . '</p>
            </div>
            ' . $sampleNote . '
        ';
        
        switch ($reportType) {
            case 'sales':
                $html .= $this->generateSalesHTML($data);
                break;
            case 'top_items':
                $html .= $this->generateTopItemsHTML($data);
                break;
            case 'shift':
                $html .= $this->generateShiftHTML($data);
                break;
            case 'order_summary':
                $html .= $this->generateOrderSummaryHTML($data);
                break;
        }
        
        $html .= '
            <div class="footer">
                <p>Generated by TalentLit POS System</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    private function generateSalesHTML($data)
    {
        $html = '<div class="summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <h3>₹' . number_format($data['total_sales'] ?? 0, 2) . '</h3>
                    <p>Total Sales</p>
                </div>
                <div class="summary-item">
                    <h3>' . ($data['total_bills'] ?? 0) . '</h3>
                    <p>Total Bills</p>
                </div>
                <div class="summary-item">
                    <h3>₹' . number_format($data['average_bill_value'] ?? 0, 2) . '</h3>
                    <p>Average Bill Value</p>
                </div>
            </div>
        </div>';
        
        if (isset($data['payment_methods'])) {
            $html .= '<h3>Payment Methods Breakdown</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Payment Method</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>';
            
            foreach ($data['payment_methods'] as $method => $amount) {
                $html .= '<tr>
                    <td>' . ucfirst($method) . '</td>
                    <td>₹' . number_format($amount, 2) . '</td>
                </tr>';
            }
            
            $html .= '</tbody></table>';
        }
        
        return $html;
    }

    private function generateTopItemsHTML($data)
    {
        $html = '<h3>Top Selling Items</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Item Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Revenue</th>
                    <th>Order Count</th>
                </tr>
            </thead>
            <tbody>';
        
        if (is_array($data)) {
            foreach ($data as $index => $item) {
                $html .= '<tr>
                    <td>' . ($index + 1) . '</td>
                    <td>' . ($item['item']['name'] ?? 'N/A') . '</td>
                    <td>' . ($item['total_qty'] ?? 0) . '</td>
                    <td>₹' . number_format($item['total_revenue'] ?? 0, 2) . '</td>
                    <td>' . ($item['order_count'] ?? 0) . '</td>
                </tr>';
            }
        }
        
        $html .= '</tbody></table>';
        return $html;
    }

    private function generateShiftHTML($data)
    {
        $html = '<h3>Shift Reports</h3>';
        
        if (is_array($data)) {
            foreach ($data as $shift) {
                $html .= '<div class="summary" style="margin-bottom: 15px;">
                    <h4>Shift #' . ($shift['shift_id'] ?? 'N/A') . '</h4>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <h3>₹' . number_format($shift['total_sales'] ?? 0, 2) . '</h3>
                            <p>Total Sales</p>
                        </div>
                        <div class="summary-item">
                            <h3>' . ($shift['total_bills'] ?? 0) . '</h3>
                            <p>Total Bills</p>
                        </div>
                        <div class="summary-item">
                            <h3>' . ($shift['opened_by'] ?? 'N/A') . '</h3>
                            <p>Opened By</p>
                        </div>
                        <div class="summary-item">
                            <h3>' . ($shift['opened_at'] ? date('M d, Y H:i', strtotime($shift['opened_at'])) : 'N/A') . '</h3>
                            <p>Opened At</p>
                        </div>
                    </div>
                </div>';
            }
        }
        
        return $html;
    }

    private function generateOrderSummaryHTML($data)
    {
        $html = '';
        
        if (isset($data['summary'])) {
            $html .= '<div class="summary">
                <div class="summary-grid">
                    <div class="summary-item">
                        <h3>' . ($data['summary']['total_orders'] ?? 0) . '</h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="summary-item">
                        <h3>₹' . number_format($data['summary']['total_revenue'] ?? 0, 2) . '</h3>
                        <p>Total Revenue</p>
                    </div>
                    <div class="summary-item">
                        <h3>₹' . number_format($data['summary']['average_order_value'] ?? 0, 2) . '</h3>
                        <p>Average Order Value</p>
                    </div>
                </div>
            </div>';
        }
        
        if (isset($data['order_types']) && is_array($data['order_types'])) {
            $html .= '<h3>Order Types Breakdown</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order Type</th>
                        <th>Total Orders</th>
                        <th>Total Revenue</th>
                        <th>Average Value</th>
                    </tr>
                </thead>
                <tbody>';
            
            foreach ($data['order_types'] as $orderType) {
                $html .= '<tr>
                    <td>' . ($orderType['order_type']['name'] ?? 'N/A') . '</td>
                    <td>' . ($orderType['total_orders'] ?? 0) . '</td>
                    <td>₹' . number_format($orderType['total_revenue'] ?? 0, 2) . '</td>
                    <td>₹' . number_format($orderType['average_order_value'] ?? 0, 2) . '</td>
                </tr>';
            }
            
            $html .= '</tbody></table>';
        }
        
        return $html;
    }

    private function generateSalesCSV($file, $data)
    {
        fputcsv($file, ['Metric', 'Value']);
        fputcsv($file, ['Total Sales', '₹' . number_format($data['total_sales'] ?? 0, 2)]);
        fputcsv($file, ['Total Bills', $data['total_bills'] ?? 0]);
        fputcsv($file, ['Average Bill Value', '₹' . number_format($data['average_bill_value'] ?? 0, 2)]);
        
        if (isset($data['payment_methods'])) {
            fputcsv($file, []);
            fputcsv($file, ['Payment Method', 'Amount']);
            foreach ($data['payment_methods'] as $method => $amount) {
                fputcsv($file, [ucfirst($method), '₹' . number_format($amount, 2)]);
            }
        }
    }

    private function generateTopItemsCSV($file, $data)
    {
        fputcsv($file, ['Rank', 'Item Name', 'Quantity Sold', 'Total Revenue', 'Order Count']);
        
        if (is_array($data)) {
            foreach ($data as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item['item']['name'] ?? 'N/A',
                    $item['total_qty'] ?? 0,
                    '₹' . number_format($item['total_revenue'] ?? 0, 2),
                    $item['order_count'] ?? 0
                ]);
            }
        }
    }

    private function generateShiftCSV($file, $data)
    {
        fputcsv($file, ['Shift ID', 'Opened By', 'Opened At', 'Closed At', 'Total Sales', 'Total Bills']);
        
        if (is_array($data)) {
            foreach ($data as $shift) {
                fputcsv($file, [
                    $shift['shift_id'] ?? 'N/A',
                    $shift['opened_by'] ?? 'N/A',
                    $shift['opened_at'] ? date('Y-m-d H:i:s', strtotime($shift['opened_at'])) : 'N/A',
                    $shift['closed_at'] ? date('Y-m-d H:i:s', strtotime($shift['closed_at'])) : 'Open',
                    '₹' . number_format($shift['total_sales'] ?? 0, 2),
                    $shift['total_bills'] ?? 0
                ]);
            }
        }
    }

    private function generateOrderSummaryCSV($file, $data)
    {
        if (isset($data['summary'])) {
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Orders', $data['summary']['total_orders'] ?? 0]);
            fputcsv($file, ['Total Revenue', '₹' . number_format($data['summary']['total_revenue'] ?? 0, 2)]);
            fputcsv($file, ['Average Order Value', '₹' . number_format($data['summary']['average_order_value'] ?? 0, 2)]);
        }
        
        if (isset($data['order_types']) && is_array($data['order_types'])) {
            fputcsv($file, []);
            fputcsv($file, ['Order Type', 'Total Orders', 'Total Revenue', 'Average Value']);
            
            foreach ($data['order_types'] as $orderType) {
                fputcsv($file, [
                    $orderType['order_type']['name'] ?? 'N/A',
                    $orderType['total_orders'] ?? 0,
                    '₹' . number_format($orderType['total_revenue'] ?? 0, 2),
                    '₹' . number_format($orderType['average_order_value'] ?? 0, 2)
                ]);
            }
        }
    }
}
