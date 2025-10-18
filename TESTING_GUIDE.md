# Table Occupancy Testing Guide

This guide provides comprehensive testing procedures to verify that the table occupancy functionality works correctly after order placement.

## 🎯 Test Objectives

Verify that:
1. Tables are correctly marked as "occupied" when orders are placed
2. Table status persists and doesn't revert to "free" after page refresh
3. Multiple orders can be added to the same table
4. Periodic refresh doesn't override recent local changes
5. API endpoints work correctly
6. Frontend UI updates reflect database changes

## 📋 Test Files

### 1. `test_table_occupancy.html` - Browser-based Test Suite
- **Purpose**: Comprehensive frontend testing with visual interface
- **Usage**: Open in browser, configure API settings, run tests
- **Features**:
  - Visual table status display
  - Individual test execution
  - Real-time test results
  - API endpoint testing

### 2. `test_table_occupancy_api.php` - Backend API Testing
- **Purpose**: Test backend API endpoints directly
- **Usage**: Run via command line: `php test_table_occupancy_api.php`
- **Features**:
  - Tests all API endpoints
  - Verifies database updates
  - Comprehensive error reporting

### 3. `test_frontend_console.js` - Browser Console Testing
- **Purpose**: Test frontend functionality directly in browser console
- **Usage**: Copy and paste into browser console on POS page
- **Features**:
  - Tests Vue.js/Alpine.js app functionality
  - Tests UI interactions
  - Tests API integration

## 🚀 Quick Start Testing

### Step 1: Backend API Testing
```bash
# Update configuration in test_table_occupancy_api.php
php test_table_occupancy_api.php
```

### Step 2: Frontend Browser Testing
1. Open `test_table_occupancy.html` in browser
2. Update API Base URL and Outlet ID
3. Click "Run All Tests"

### Step 3: Live POS Testing
1. Open POS register page
2. Open browser console
3. Copy and paste `test_frontend_console.js`
4. Run `tableOccupancyTests.runAllTests()`

## 📊 Test Scenarios

### Scenario 1: Basic Table Occupancy
1. **Setup**: Ensure at least one table is in "free" status
2. **Action**: Create an order for the table
3. **Expected**: Table status changes to "occupied"
4. **Verify**: Check both UI and database

### Scenario 2: Table Status Persistence
1. **Setup**: Table is occupied with an order
2. **Action**: Refresh the page
3. **Expected**: Table remains "occupied"
4. **Verify**: Status doesn't revert to "free"

### Scenario 3: Multiple Orders on Same Table
1. **Setup**: Table is occupied with one order
2. **Action**: Create another order for the same table
3. **Expected**: Table remains "occupied", total amount increases
4. **Verify**: Orders array contains multiple orders

### Scenario 4: Periodic Refresh Protection
1. **Setup**: Table is occupied with recent order
2. **Action**: Wait for periodic refresh (10 seconds)
3. **Expected**: Table status remains "occupied"
4. **Verify**: Local changes are preserved

### Scenario 5: API Endpoint Testing
1. **Test**: GET `/tables` - Should return table list
2. **Test**: PATCH `/tables/{id}/status` - Should update status
3. **Test**: POST `/orders` - Should create order
4. **Verify**: All endpoints return correct responses

## 🔧 Configuration

### API Configuration
Update these values in test files:
```javascript
const TEST_CONFIG = {
    apiBase: 'http://localhost:8000/your-tenant-slug/pos/api',
    outletId: 1,
    testTimeout: 10000
};
```

### Database Configuration
Ensure your database has:
- `restaurant_tables` table with `orders` JSON field
- At least one table record
- Proper tenant and outlet relationships

## 📈 Expected Results

### ✅ Passing Tests
- All API endpoints return 200/201 status
- Tables update status correctly
- Orders are created successfully
- UI reflects database changes
- Periodic refresh preserves local changes

### ❌ Common Issues
- **Table not updating**: Check API endpoint configuration
- **Status reverting**: Check periodic refresh protection
- **API errors**: Check tenant slug and outlet ID
- **UI not updating**: Check Vue.js/Alpine.js app state

## 🐛 Debugging

### Check Browser Console
```javascript
// Check current table status
console.log('Current tables:', window.app?.tables);

// Check selected table
console.log('Selected table:', window.app?.selectedTable);

// Check last update time
console.log('Last update:', window.app?.lastTableUpdate);
```

### Check Network Tab
1. Open browser DevTools
2. Go to Network tab
3. Create an order
4. Check API calls:
   - POST to `/orders` - Should return 200
   - PATCH to `/tables/{id}/status` - Should return 200

### Check Database
```sql
-- Check table status
SELECT id, name, status, total_amount, orders FROM restaurant_tables;

-- Check recent orders
SELECT id, table_id, status, created_at FROM orders ORDER BY created_at DESC LIMIT 10;
```

## 📝 Test Results Template

```
Test Date: [DATE]
Tester: [NAME]
Environment: [LOCAL/STAGING/PRODUCTION]

Backend API Tests:
- Test 1: Fetch Tables - [PASS/FAIL]
- Test 2: Update Table Status - [PASS/FAIL]
- Test 3: Create Order - [PASS/FAIL]
- Test 4: Verify Table Occupancy - [PASS/FAIL]
- Test 5: Reset Table - [PASS/FAIL]

Frontend Tests:
- Test 1: Vue App Check - [PASS/FAIL]
- Test 2: Tables Data - [PASS/FAIL]
- Test 3: Status Display - [PASS/FAIL]
- Test 4: Table Selection - [PASS/FAIL]
- Test 5: Order Creation - [PASS/FAIL]

Browser Tests:
- Test 6: API Endpoints - [PASS/FAIL]
- Test 7: Table Status Update - [PASS/FAIL]
- Test 8: Order Creation API - [PASS/FAIL]

Overall Result: [PASS/FAIL]
Issues Found: [LIST ISSUES]
```

## 🔄 Continuous Testing

### Automated Testing
Set up these tests to run automatically:
1. **Pre-commit**: Run API tests before code commits
2. **CI/CD**: Include tests in deployment pipeline
3. **Monitoring**: Set up alerts for test failures

### Manual Testing Checklist
- [ ] Tables load correctly on page load
- [ ] Table selection works
- [ ] Order creation updates table status
- [ ] Table status persists after refresh
- [ ] Multiple orders work on same table
- [ ] Periodic refresh doesn't override changes
- [ ] API endpoints return correct responses

## 📞 Support

If tests fail:
1. Check the test logs for specific error messages
2. Verify API configuration and database connectivity
3. Check browser console for JavaScript errors
4. Review the code changes made to fix the issue

Remember: **Don't mark the functionality as completed without thorough testing!**

