# Multi-Terminal Billing Usage Guide

## Overview
The multi-terminal billing feature allows you to operate multiple POS terminals/counters from different areas of your restaurant, with all data synced to a master station. Each terminal can generate orders, KOTs, and process payments independently while maintaining proper attribution.

## Step-by-Step Setup

### 1. Create Terminal Devices
1. **Access Device Management:**
   - Go to your tenant dashboard
   - Navigate to `/devices` (e.g., `yoursite.com/yourtenant/devices`)
   - Click "Add New Device"

2. **Configure Each Terminal:**
   - **Device Name:** e.g., "Counter 1", "Kitchen Terminal", "Bar Counter"
   - **Type:** Choose from:
     - `POS` - For billing counters
     - `KITCHEN` - For kitchen display systems
     - `TOKEN` - For token counters
   - **Outlet:** Select which outlet this terminal belongs to
   - Click "Create Device"

3. **Note the API Key:**
   - Each device gets a unique API key (e.g., `pos_abc123def456`)
   - Copy this key - you'll need it for terminal configuration

### 2. Set Up Each Terminal Machine

#### Option A: Using API Key (Recommended)
1. **Open POS on each terminal machine**
2. **The system automatically:**
   - Loads available devices for the outlet
   - Selects the first device automatically
   - Uses the device's API key for identification

#### Option B: Manual Device Selection
If you want to manually select which device to use:
1. Modify the POS interface to show a device dropdown
2. Bind it to `deviceId` and `deviceKey` variables
3. The system will use the selected device for all operations

### 3. How It Works

#### Order Creation
- When creating an order, the system automatically:
  - Attaches the current device ID to the order
  - Sends the device's API key in the `X-Device-Key` header
  - Records which terminal created the order

#### KOT Generation
- When firing a KOT, the system:
  - Links the kitchen ticket to the same device as the order
  - Ensures kitchen knows which terminal sent the order
  - Maintains proper attribution for kitchen workflow

#### Billing & Payments
- When creating bills and processing payments:
  - Bills are tagged with the device that created them
  - Payments are recorded with the terminal that processed them
  - All financial data is properly attributed

## Practical Examples

### Example 1: Restaurant with 3 Counters
1. **Create 3 devices:**
   - "Main Counter" (POS) - API Key: `pos_main123`
   - "Bar Counter" (POS) - API Key: `pos_bar456`
   - "Takeaway Counter" (POS) - API Key: `pos_take789`

2. **Set up terminals:**
   - Counter 1 computer: Uses "Main Counter" device
   - Bar computer: Uses "Bar Counter" device  
   - Takeaway computer: Uses "Takeaway Counter" device

3. **Result:**
   - Each order shows which counter created it
   - KOTs are sent to kitchen with terminal info
   - Bills and payments are tracked per counter
   - Master reports show sales by terminal

### Example 2: Kitchen Display System
1. **Create devices:**
   - "Hot Kitchen" (KITCHEN) - API Key: `kitchen_hot123`
   - "Cold Kitchen" (KITCHEN) - API Key: `kitchen_cold456`

2. **Set up kitchen displays:**
   - Hot kitchen screen: Uses "Hot Kitchen" device
   - Cold kitchen screen: Uses "Cold Kitchen" device

3. **Result:**
   - KOTs are routed to appropriate kitchen stations
   - Each kitchen station sees only relevant orders
   - Order completion is tracked per station

## API Usage

### Creating Orders with Device Attribution
```javascript
// The POS automatically includes device info
const orderData = {
    outlet_id: 1,
    device_id: 5,  // Automatically set
    order_type_id: 1,
    payment_method: 'cash',
    items: [...]
};

// Headers include device identification
const headers = {
    'Content-Type': 'application/json',
    'X-Device-Key': 'pos_abc123def456'  // Automatically set
};
```

### Manual API Calls
If making API calls outside the POS interface:

```bash
# Create order with specific device
curl -X POST /yourtenant/api/public/orders \
  -H "Content-Type: application/json" \
  -H "X-Device-Key: pos_abc123def456" \
  -d '{
    "outlet_id": 1,
    "order_type_id": 1,
    "payment_method": "cash",
    "items": [...]
  }'

# Fire KOT with device attribution
curl -X POST /yourtenant/api/public/orders/123/kot \
  -H "Content-Type: application/json" \
  -H "X-Device-Key: pos_abc123def456" \
  -d '{"station": "hot-kitchen"}'
```

## Reporting & Analytics

### Terminal-Specific Reports
With device attribution, you can now generate reports showing:
- Sales per terminal/counter
- Orders processed per device
- KOTs generated per kitchen station
- Payment methods used per terminal
- Peak hours per counter

### Database Queries
```sql
-- Sales by terminal
SELECT d.name as terminal, SUM(b.net_total) as total_sales
FROM bills b
JOIN devices d ON b.device_id = d.id
WHERE b.created_at >= '2024-01-01'
GROUP BY d.id, d.name;

-- Orders per terminal today
SELECT d.name as terminal, COUNT(*) as order_count
FROM orders o
JOIN devices d ON o.device_id = d.id
WHERE DATE(o.created_at) = CURDATE()
GROUP BY d.id, d.name;
```

## Troubleshooting

### Common Issues

1. **Device not found:**
   - Check if device exists in the database
   - Verify API key is correct
   - Ensure device belongs to the correct outlet

2. **Orders not attributed to device:**
   - Check if `X-Device-Key` header is being sent
   - Verify device API key matches database
   - Ensure device is active and not deleted

3. **POS not loading devices:**
   - Check outlet_id is correct
   - Verify user has access to the outlet
   - Check browser console for API errors

### Debug Steps
1. Check browser network tab for API calls
2. Verify device API key in database
3. Test device resolution in middleware
4. Check order/bill records for device_id

## Security Notes

- API keys are unique per device and should be kept secure
- Each device can only access its own outlet's data
- Device attribution cannot be spoofed without valid API key
- All device operations are logged for audit purposes

## Migration Notes

- Existing orders/bills without device_id will show as NULL
- New records will automatically get device attribution
- No data loss occurs during migration
- Reports can filter by device_id to show only new data

---

This multi-terminal system gives you complete visibility and control over your restaurant operations across multiple counters while maintaining data integrity and proper attribution.
