<?php
/**
 * Table Occupancy API Test Script
 * 
 * This script tests the backend API endpoints for table occupancy functionality.
 * Run this script to verify that the API endpoints work correctly.
 */

class TableOccupancyAPITester
{
    private $baseUrl;
    private $tenantSlug;
    private $outletId;
    private $testResults = [];
    
    public function __construct($baseUrl = 'http://localhost:8000', $tenantSlug = 'tenant-slug', $outletId = 1)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->tenantSlug = $tenantSlug;
        $this->outletId = $outletId;
    }
    
    private function log($message, $type = 'INFO')
    {
        $timestamp = date('Y-m-d H:i:s');
        echo "[{$timestamp}] {$type}: {$message}\n";
    }
    
    private function makeRequest($endpoint, $method = 'GET', $data = null)
    {
        $url = $this->baseUrl . '/' . $this->tenantSlug . '/pos/api' . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Requested-With: XMLHttpRequest'
        ]);
        
        if ($method === 'POST' || $method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: {$error}");
        }
        
        $decodedResponse = json_decode($response, true);
        
        return [
            'http_code' => $httpCode,
            'response' => $decodedResponse,
            'raw_response' => $response
        ];
    }
    
    public function test1_FetchTables()
    {
        $this->log("Test 1: Fetching tables...");
        
        try {
            $result = $this->makeRequest("/tables?outlet_id={$this->outletId}");
            
            if ($result['http_code'] === 200 && isset($result['response']['success']) && $result['response']['success']) {
                $tables = $result['response']['tables'];
                $this->log("✅ Successfully fetched " . count($tables) . " tables");
                
                foreach ($tables as $table) {
                    $this->log("  - Table {$table['name']}: {$table['status']} (₹{$table['total_amount']})");
                }
                
                $this->testResults['test1'] = ['status' => 'PASSED', 'tables' => $tables];
                return $tables;
            } else {
                throw new Exception("API returned error: " . ($result['response']['error'] ?? 'Unknown error'));
            }
        } catch (Exception $e) {
            $this->log("❌ Test 1 FAILED: " . $e->getMessage(), 'ERROR');
            $this->testResults['test1'] = ['status' => 'FAILED', 'error' => $e->getMessage()];
            return null;
        }
    }
    
    public function test2_UpdateTableStatus($tableId, $status, $totalAmount = null, $orders = null)
    {
        $this->log("Test 2: Updating table {$tableId} status to {$status}...");
        
        try {
            $updateData = ['status' => $status];
            if ($totalAmount !== null) $updateData['total_amount'] = $totalAmount;
            if ($orders !== null) $updateData['orders'] = $orders;
            
            $result = $this->makeRequest("/tables/{$tableId}/status", 'PATCH', $updateData);
            
            if ($result['http_code'] === 200) {
                $updatedTable = $result['response'];
                $this->log("✅ Table {$tableId} status updated to {$updatedTable['status']}");
                $this->testResults['test2'] = ['status' => 'PASSED', 'table' => $updatedTable];
                return $updatedTable;
            } else {
                throw new Exception("HTTP {$result['http_code']}: " . ($result['response']['error'] ?? 'Unknown error'));
            }
        } catch (Exception $e) {
            $this->log("❌ Test 2 FAILED: " . $e->getMessage(), 'ERROR');
            $this->testResults['test2'] = ['status' => 'FAILED', 'error' => $e->getMessage()];
            return null;
        }
    }
    
    public function test3_CreateOrder($tableId)
    {
        $this->log("Test 3: Creating order for table {$tableId}...");
        
        try {
            $orderData = [
                'outlet_id' => $this->outletId,
                'order_type_id' => 1,
                'payment_method' => 'cash',
                'table_no' => $tableId,
                'customer_name' => 'Test Customer',
                'customer_phone' => '1234567890',
                'mode' => 'DINE_IN',
                'items' => [
                    ['item_id' => 1, 'qty' => 1]
                ]
            ];
            
            $result = $this->makeRequest('/orders', 'POST', $orderData);
            
            if ($result['http_code'] === 200 || $result['http_code'] === 201) {
                $order = $result['response'];
                $this->log("✅ Order #{$order['id']} created successfully");
                $this->testResults['test3'] = ['status' => 'PASSED', 'order' => $order];
                return $order;
            } else {
                throw new Exception("HTTP {$result['http_code']}: " . ($result['response']['error'] ?? 'Unknown error'));
            }
        } catch (Exception $e) {
            $this->log("❌ Test 3 FAILED: " . $e->getMessage(), 'ERROR');
            $this->testResults['test3'] = ['status' => 'FAILED', 'error' => $e->getMessage()];
            return null;
        }
    }
    
    public function test4_VerifyTableOccupancyAfterOrder($tableId)
    {
        $this->log("Test 4: Verifying table {$tableId} is occupied after order creation...");
        
        try {
            // Wait a moment for any async processing
            sleep(1);
            
            $result = $this->makeRequest("/tables?outlet_id={$this->outletId}");
            
            if ($result['http_code'] === 200 && isset($result['response']['success']) && $result['response']['success']) {
                $tables = $result['response']['tables'];
                $table = array_filter($tables, function($t) use ($tableId) {
                    return $t['id'] == $tableId;
                });
                
                if (empty($table)) {
                    throw new Exception("Table {$tableId} not found after order creation");
                }
                
                $table = array_values($table)[0];
                
                if ($table['status'] === 'occupied') {
                    $this->log("✅ Table {$tableId} is correctly marked as occupied");
                    $this->log("  - Total amount: ₹{$table['total_amount']}");
                    $this->log("  - Orders count: " . (isset($table['orders']) ? count($table['orders']) : 0));
                    $this->testResults['test4'] = ['status' => 'PASSED', 'table' => $table];
                    return true;
                } else {
                    $this->log("⚠️ Table {$tableId} status is '{$table['status']}' (expected 'occupied')");
                    $this->testResults['test4'] = ['status' => 'WARNING', 'table' => $table];
                    return false;
                }
            } else {
                throw new Exception("Failed to fetch tables after order creation");
            }
        } catch (Exception $e) {
            $this->log("❌ Test 4 FAILED: " . $e->getMessage(), 'ERROR');
            $this->testResults['test4'] = ['status' => 'FAILED', 'error' => $e->getMessage()];
            return false;
        }
    }
    
    public function test5_ResetTable($tableId)
    {
        $this->log("Test 5: Resetting table {$tableId} to free...");
        
        try {
            $result = $this->test2_UpdateTableStatus($tableId, 'free', 0, []);
            
            if ($result) {
                $this->log("✅ Table {$tableId} reset to free successfully");
                $this->testResults['test5'] = ['status' => 'PASSED'];
                return true;
            } else {
                $this->log("❌ Failed to reset table {$tableId}");
                $this->testResults['test5'] = ['status' => 'FAILED'];
                return false;
            }
        } catch (Exception $e) {
            $this->log("❌ Test 5 FAILED: " . $e->getMessage(), 'ERROR');
            $this->testResults['test5'] = ['status' => 'FAILED', 'error' => $e->getMessage()];
            return false;
        }
    }
    
    public function runAllTests()
    {
        $this->log("🚀 Starting comprehensive API test suite...");
        $this->log("Base URL: {$this->baseUrl}");
        $this->log("Tenant Slug: {$this->tenantSlug}");
        $this->log("Outlet ID: {$this->outletId}");
        $this->log("=" . str_repeat("=", 50));
        
        // Test 1: Fetch tables
        $tables = $this->test1_FetchTables();
        if (!$tables) {
            $this->log("❌ Cannot proceed - no tables available", 'ERROR');
            return;
        }
        
        $testTable = $tables[0];
        $this->log("Using table {$testTable['name']} (ID: {$testTable['id']}) for testing");
        
        // Test 2: Update table status
        $this->test2_UpdateTableStatus($testTable['id'], 'occupied', 150.75, [
            ['id' => 'test1', 'total' => 150.75, 'status' => 'active', 'items' => []]
        ]);
        
        // Test 3: Create order
        $order = $this->test3_CreateOrder($testTable['id']);
        
        // Test 4: Verify table occupancy
        $this->test4_VerifyTableOccupancyAfterOrder($testTable['id']);
        
        // Test 5: Reset table
        $this->test5_ResetTable($testTable['id']);
        
        // Summary
        $this->log("=" . str_repeat("=", 50));
        $this->log("📊 TEST SUMMARY:");
        
        $passed = 0;
        $failed = 0;
        $warnings = 0;
        
        foreach ($this->testResults as $testName => $result) {
            $status = $result['status'];
            $this->log("  {$testName}: {$status}");
            
            if ($status === 'PASSED') $passed++;
            elseif ($status === 'FAILED') $failed++;
            elseif ($status === 'WARNING') $warnings++;
        }
        
        $this->log("=" . str_repeat("=", 50));
        $this->log("✅ Passed: {$passed}");
        $this->log("⚠️ Warnings: {$warnings}");
        $this->log("❌ Failed: {$failed}");
        
        if ($failed === 0) {
            $this->log("🎉 All tests completed successfully!", 'SUCCESS');
        } else {
            $this->log("⚠️ Some tests failed. Please check the logs above.", 'WARNING');
        }
    }
    
    public function getTestResults()
    {
        return $this->testResults;
    }
}

// Configuration
$baseUrl = 'http://localhost:8000';
$tenantSlug = 'tenant-slug'; // Change this to your actual tenant slug
$outletId = 1; // Change this to your actual outlet ID

// Run tests
$tester = new TableOccupancyAPITester($baseUrl, $tenantSlug, $outletId);
$tester->runAllTests();

// Optional: Save results to file
$results = $tester->getTestResults();
file_put_contents('test_results_' . date('Y-m-d_H-i-s') . '.json', json_encode($results, JSON_PRETTY_PRINT));
echo "\nTest results saved to test_results_" . date('Y-m-d_H-i-s') . ".json\n";
?>

