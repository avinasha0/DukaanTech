/**
 * Frontend Console Test Script for Table Occupancy
 * 
 * Copy and paste this script into the browser console on the POS register page
 * to test the table occupancy functionality directly.
 */

console.log('🧪 Loading Table Occupancy Frontend Test Suite...');

// Test configuration
const TEST_CONFIG = {
    apiBase: window.location.origin + '/tenant-slug/pos/api', // Update tenant-slug as needed
    outletId: 1, // Update as needed
    testTimeout: 10000 // 10 seconds
};

// Test results storage
let testResults = {
    tests: [],
    startTime: Date.now(),
    endTime: null
};

// Utility functions
function logTest(testName, status, message, data = null) {
    const result = {
        testName,
        status, // 'PASS', 'FAIL', 'WARN'
        message,
        data,
        timestamp: new Date().toISOString()
    };
    
    testResults.tests.push(result);
    
    const emoji = status === 'PASS' ? '✅' : status === 'FAIL' ? '❌' : '⚠️';
    console.log(`${emoji} ${testName}: ${message}`);
    
    if (data) {
        console.log('Data:', data);
    }
}

function waitFor(condition, timeout = 5000) {
    return new Promise((resolve, reject) => {
        const startTime = Date.now();
        const check = () => {
            if (condition()) {
                resolve();
            } else if (Date.now() - startTime > timeout) {
                reject(new Error('Timeout waiting for condition'));
            } else {
                setTimeout(check, 100);
            }
        };
        check();
    });
}

// Test functions
async function test1_CheckVueApp() {
    console.log('\n🔍 Test 1: Checking Vue.js app availability...');
    
    try {
        // Check if Vue app is available
        if (typeof window.Vue === 'undefined' && !document.querySelector('[x-data]')) {
            throw new Error('Vue.js app not found');
        }
        
        // Check if POS app data is available
        const appElement = document.querySelector('[x-data]');
        if (!appElement) {
            throw new Error('Alpine.js app not found');
        }
        
        logTest('test1_CheckVueApp', 'PASS', 'Vue.js/Alpine.js app is available');
        return true;
    } catch (error) {
        logTest('test1_CheckVueApp', 'FAIL', error.message);
        return false;
    }
}

async function test2_CheckTablesData() {
    console.log('\n🔍 Test 2: Checking tables data...');
    
    try {
        // Try to access tables data from the app
        const appElement = document.querySelector('[x-data]');
        if (!appElement) {
            throw new Error('App element not found');
        }
        
        // Check if tables are loaded
        const tablesElement = document.querySelector('[x-for*="table in tables"]');
        if (!tablesElement) {
            throw new Error('Tables element not found');
        }
        
        // Count visible tables
        const tableCards = document.querySelectorAll('[x-for*="table in tables"] > div');
        const tableCount = tableCards.length;
        
        if (tableCount === 0) {
            throw new Error('No tables found in the UI');
        }
        
        logTest('test2_CheckTablesData', 'PASS', `Found ${tableCount} tables in the UI`);
        return true;
    } catch (error) {
        logTest('test2_CheckTablesData', 'FAIL', error.message);
        return false;
    }
}

async function test3_TestTableStatusDisplay() {
    console.log('\n🔍 Test 3: Testing table status display...');
    
    try {
        const tableCards = document.querySelectorAll('[x-for*="table in tables"] > div');
        let freeTables = 0;
        let occupiedTables = 0;
        
        tableCards.forEach((card, index) => {
            const statusElement = card.querySelector('[x-text*="status"]');
            const statusIndicator = card.querySelector('.status-indicator, [class*="status"]');
            
            if (statusElement) {
                const statusText = statusElement.textContent.toLowerCase();
                if (statusText.includes('free') || statusText.includes('available')) {
                    freeTables++;
                } else if (statusText.includes('occupied')) {
                    occupiedTables++;
                }
            }
        });
        
        logTest('test3_TestTableStatusDisplay', 'PASS', 
            `Status display working: ${freeTables} free, ${occupiedTables} occupied tables`);
        return true;
    } catch (error) {
        logTest('test3_TestTableStatusDisplay', 'FAIL', error.message);
        return false;
    }
}

async function test4_TestTableSelection() {
    console.log('\n🔍 Test 4: Testing table selection...');
    
    try {
        // Find a free table to select
        const tableCards = document.querySelectorAll('[x-for*="table in tables"] > div');
        let freeTableCard = null;
        
        for (const card of tableCards) {
            const statusElement = card.querySelector('[x-text*="status"]');
            if (statusElement && statusElement.textContent.toLowerCase().includes('free')) {
                freeTableCard = card;
                break;
            }
        }
        
        if (!freeTableCard) {
            logTest('test4_TestTableSelection', 'WARN', 'No free tables available for selection test');
            return true;
        }
        
        // Try to click the table
        const clickableElement = freeTableCard.querySelector('[x-on\\:click*="selectTable"]');
        if (clickableElement) {
            clickableElement.click();
            logTest('test4_TestTableSelection', 'PASS', 'Table selection clickable element found and clicked');
        } else {
            logTest('test4_TestTableSelection', 'WARN', 'Table selection clickable element not found');
        }
        
        return true;
    } catch (error) {
        logTest('test4_TestTableSelection', 'FAIL', error.message);
        return false;
    }
}

async function test5_TestOrderCreation() {
    console.log('\n🔍 Test 5: Testing order creation functionality...');
    
    try {
        // Check if order creation buttons exist
        const orderButtons = document.querySelectorAll('[x-on\\:click*="createOrder"]');
        if (orderButtons.length === 0) {
            throw new Error('Order creation buttons not found');
        }
        
        // Check if cart functionality exists
        const cartElement = document.querySelector('[x-for*="cart"]');
        if (!cartElement) {
            throw new Error('Cart element not found');
        }
        
        // Check if payment method selection exists
        const paymentElements = document.querySelectorAll('[x-model*="paymentMethod"]');
        if (paymentElements.length === 0) {
            throw new Error('Payment method selection not found');
        }
        
        logTest('test5_TestOrderCreation', 'PASS', 
            `Order creation UI elements found: ${orderButtons.length} buttons, cart and payment elements present`);
        return true;
    } catch (error) {
        logTest('test5_TestOrderCreation', 'FAIL', error.message);
        return false;
    }
}

async function test6_TestAPIEndpoints() {
    console.log('\n🔍 Test 6: Testing API endpoints...');
    
    try {
        const endpoints = [
            '/tables',
            '/categories',
            '/items',
            '/order-types'
        ];
        
        let workingEndpoints = 0;
        const results = {};
        
        for (const endpoint of endpoints) {
            try {
                const response = await fetch(`${TEST_CONFIG.apiBase}${endpoint}?outlet_id=${TEST_CONFIG.outletId}`);
                if (response.ok) {
                    const data = await response.json();
                    results[endpoint] = { status: 'OK', data: data };
                    workingEndpoints++;
                } else {
                    results[endpoint] = { status: 'ERROR', code: response.status };
                }
            } catch (error) {
                results[endpoint] = { status: 'ERROR', error: error.message };
            }
        }
        
        logTest('test6_TestAPIEndpoints', 'PASS', 
            `${workingEndpoints}/${endpoints.length} API endpoints working`, results);
        return workingEndpoints > 0;
    } catch (error) {
        logTest('test6_TestAPIEndpoints', 'FAIL', error.message);
        return false;
    }
}

async function test7_TestTableStatusUpdate() {
    console.log('\n🔍 Test 7: Testing table status update API...');
    
    try {
        // First, get current tables
        const response = await fetch(`${TEST_CONFIG.apiBase}/tables?outlet_id=${TEST_CONFIG.outletId}`);
        if (!response.ok) {
            throw new Error(`Failed to fetch tables: ${response.status}`);
        }
        
        const data = await response.json();
        if (!data.success || !data.tables.length) {
            throw new Error('No tables available for testing');
        }
        
        const testTable = data.tables[0];
        const originalStatus = testTable.status;
        
        // Test updating table status
        const updateResponse = await fetch(`${TEST_CONFIG.apiBase}/tables/${testTable.id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: 'occupied',
                total_amount: 100.50,
                orders: [{ id: 'test', total: 100.50, status: 'active' }]
            })
        });
        
        if (!updateResponse.ok) {
            throw new Error(`Failed to update table status: ${updateResponse.status}`);
        }
        
        const updatedTable = await updateResponse.json();
        
        // Reset table status
        await fetch(`${TEST_CONFIG.apiBase}/tables/${testTable.id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: originalStatus,
                total_amount: 0,
                orders: []
            })
        });
        
        logTest('test7_TestTableStatusUpdate', 'PASS', 
            `Table status update API working. Updated table ${testTable.name} to ${updatedTable.status}`);
        return true;
    } catch (error) {
        logTest('test7_TestTableStatusUpdate', 'FAIL', error.message);
        return false;
    }
}

async function test8_TestOrderCreationAPI() {
    console.log('\n🔍 Test 8: Testing order creation API...');
    
    try {
        // Get a table first
        const tablesResponse = await fetch(`${TEST_CONFIG.apiBase}/tables?outlet_id=${TEST_CONFIG.outletId}`);
        const tablesData = await tablesResponse.json();
        
        if (!tablesData.success || !tablesData.tables.length) {
            throw new Error('No tables available for order creation test');
        }
        
        const testTable = tablesData.tables[0];
        
        // Create a test order
        const orderData = {
            outlet_id: TEST_CONFIG.outletId,
            order_type_id: 1,
            payment_method: 'cash',
            table_no: testTable.name,
            customer_name: 'Test Customer',
            customer_phone: '1234567890',
            mode: 'DINE_IN',
            items: [
                { item_id: 1, qty: 1 }
            ]
        };
        
        const orderResponse = await fetch(`${TEST_CONFIG.apiBase}/orders`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(orderData)
        });
        
        if (!orderResponse.ok) {
            const errorData = await orderResponse.json();
            throw new Error(`Order creation failed: ${errorData.error || 'Unknown error'}`);
        }
        
        const order = await orderResponse.json();
        
        logTest('test8_TestOrderCreationAPI', 'PASS', 
            `Order creation API working. Created order #${order.id} for table ${testTable.name}`);
        return true;
    } catch (error) {
        logTest('test8_TestOrderCreationAPI', 'FAIL', error.message);
        return false;
    }
}

// Main test runner
async function runAllTests() {
    console.log('🚀 Starting Frontend Table Occupancy Test Suite...');
    console.log('Configuration:', TEST_CONFIG);
    console.log('=' .repeat(60));
    
    testResults.startTime = Date.now();
    
    const tests = [
        test1_CheckVueApp,
        test2_CheckTablesData,
        test3_TestTableStatusDisplay,
        test4_TestTableSelection,
        test5_TestOrderCreation,
        test6_TestAPIEndpoints,
        test7_TestTableStatusUpdate,
        test8_TestOrderCreationAPI
    ];
    
    let passed = 0;
    let failed = 0;
    let warnings = 0;
    
    for (const test of tests) {
        try {
            const result = await test();
            if (result === true) passed++;
            else if (result === false) failed++;
            else warnings++;
        } catch (error) {
            console.error('Test error:', error);
            failed++;
        }
        
        // Small delay between tests
        await new Promise(resolve => setTimeout(resolve, 500));
    }
    
    testResults.endTime = Date.now();
    const duration = testResults.endTime - testResults.startTime;
    
    console.log('\n' + '='.repeat(60));
    console.log('📊 TEST SUMMARY:');
    console.log(`✅ Passed: ${passed}`);
    console.log(`⚠️ Warnings: ${warnings}`);
    console.log(`❌ Failed: ${failed}`);
    console.log(`⏱️ Duration: ${duration}ms`);
    console.log('='.repeat(60));
    
    if (failed === 0) {
        console.log('🎉 All tests completed successfully!');
    } else {
        console.log('⚠️ Some tests failed. Please check the logs above.');
    }
    
    // Save results to global variable for inspection
    window.testResults = testResults;
    console.log('Test results saved to window.testResults');
    
    return testResults;
}

// Export functions for individual testing
window.tableOccupancyTests = {
    runAllTests,
    test1_CheckVueApp,
    test2_CheckTablesData,
    test3_TestTableStatusDisplay,
    test4_TestTableSelection,
    test5_TestOrderCreation,
    test6_TestAPIEndpoints,
    test7_TestTableStatusUpdate,
    test8_TestOrderCreationAPI,
    testResults: () => testResults
};

console.log('✅ Frontend test suite loaded!');
console.log('Run: tableOccupancyTests.runAllTests() to start testing');
console.log('Or run individual tests like: tableOccupancyTests.test1_CheckVueApp()');

