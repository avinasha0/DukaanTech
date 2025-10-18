// Terminal Configuration Example
// Copy this file to each terminal and modify the DEVICE_CONFIG

const DEVICE_CONFIG = {
    // Counter 1 Configuration
    device_key: 'pos_abc123def456ghi7',  // Copy from device management
    terminal_name: 'Counter 1',
    outlet_id: 1,
    
    // Optional: Override default settings
    settings: {
        auto_select_first_device: true,
        show_device_selector: false,
        default_order_type: 'dine-in'
    }
};

// Apply configuration to POS register
window.TERMINAL_CONFIG = DEVICE_CONFIG;

// Example: Counter 2 Configuration
const COUNTER_2_CONFIG = {
    device_key: 'pos_xyz789uvw012rst3',
    terminal_name: 'Counter 2', 
    outlet_id: 1,
    settings: {
        auto_select_first_device: true,
        show_device_selector: false,
        default_order_type: 'takeaway'
    }
};

// Example: Kitchen Display Configuration  
const KITCHEN_CONFIG = {
    device_key: 'kitchen_def456ghi789jkl0',
    terminal_name: 'Kitchen Display',
    outlet_id: 1,
    settings: {
        auto_select_first_device: true,
        show_device_selector: false
    }
};
