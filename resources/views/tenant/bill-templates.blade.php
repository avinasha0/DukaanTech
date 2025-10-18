@extends('layouts.tenant')

@section('title', 'Bill Templates - Dukaantech POS')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Bill Templates</h1>
            <p class="mt-2 text-gray-600">Customize your bill format and printing options</p>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">Template Management</h2>
                    <button onclick="createTemplate()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Create New Template
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div id="templates-list" class="space-y-4">
                    <!-- Templates will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template Modal -->
<div id="template-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900">Create Template</h3>
            </div>
            
            <form id="template-form" class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Template Name</label>
                    <input type="text" id="template-name" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="template-description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Template Configuration -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Header Configuration -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900">Header Settings</h4>
                        
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" id="show-logo" name="header_config[show_logo]" class="mr-2">
                                <span class="text-sm text-gray-700">Show Logo</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" id="show-restaurant-name" name="header_config[show_restaurant_name]" checked class="mr-2">
                                <span class="text-sm text-gray-700">Show Restaurant Name</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" id="show-address" name="header_config[show_address]" checked class="mr-2">
                                <span class="text-sm text-gray-700">Show Address</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" id="show-phone" name="header_config[show_phone]" checked class="mr-2">
                                <span class="text-sm text-gray-700">Show Phone</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" id="show-gstin" name="header_config[show_gstin]" checked class="mr-2">
                                <span class="text-sm text-gray-700">Show GSTIN</span>
                            </label>
                        </div>
                    </div>

                    <!-- Footer Configuration -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900">Footer Settings</h4>
                        
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" id="show-qr-code" name="footer_config[show_qr_code]" checked class="mr-2">
                                <span class="text-sm text-gray-700">Show QR Code</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" id="show-payment-qr" name="footer_config[show_payment_qr]" checked class="mr-2">
                                <span class="text-sm text-gray-700">Show Payment QR</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" id="show-footer-text" name="footer_config[show_footer_text]" checked class="mr-2">
                                <span class="text-sm text-gray-700">Show Footer Text</span>
                            </label>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Footer Text</label>
                                <input type="text" id="footer-text" name="footer_config[footer_text]" value="Thank you for your visit!" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Configuration -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900">Item Display Settings</h4>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="show-modifiers" name="item_config[show_modifiers]" checked class="mr-2">
                            <span class="text-sm text-gray-700">Show Modifiers</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" id="show-tax-breakdown" name="item_config[show_tax_breakdown]" checked class="mr-2">
                            <span class="text-sm text-gray-700">Show Tax Breakdown</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" id="show-discount" name="item_config[show_discount]" checked class="mr-2">
                            <span class="text-sm text-gray-700">Show Discount</span>
                        </label>
                    </div>
                </div>

                <!-- Payment Configuration -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900">Payment Display Settings</h4>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="show-payment-methods" name="payment_config[show_payment_methods]" checked class="mr-2">
                            <span class="text-sm text-gray-700">Show Payment Methods</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" id="show-change-amount" name="payment_config[show_change_amount]" checked class="mr-2">
                            <span class="text-sm text-gray-700">Show Change Amount</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" id="show-payment-reference" name="payment_config[show_payment_reference]" checked class="mr-2">
                            <span class="text-sm text-gray-700">Show Payment Reference</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="is-default" name="is_default" class="mr-2">
                    <label for="is-default" class="text-sm text-gray-700">Set as default template</label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let templates = [];
let currentTemplate = null;

// Load templates on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTemplates();
});

async function loadTemplates() {
    try {
        const response = await fetch('/api/bill-templates');
        templates = await response.json();
        renderTemplates();
    } catch (error) {
        console.error('Error loading templates:', error);
    }
}

function renderTemplates() {
    const container = document.getElementById('templates-list');
    
    if (templates.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center py-8">No templates found. Create your first template!</p>';
        return;
    }

    container.innerHTML = templates.map(template => `
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">
                        ${template.name}
                        ${template.is_default ? '<span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Default</span>' : ''}
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">${template.description || 'No description'}</p>
                    <p class="text-xs text-gray-500 mt-2">Created: ${new Date(template.created_at).toLocaleDateString()}</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="previewTemplate(${template.id})" class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200">
                        Preview
                    </button>
                    <button onclick="editTemplate(${template.id})" class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200">
                        Edit
                    </button>
                    ${!template.is_default ? `
                        <button onclick="setDefault(${template.id})" class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                            Set Default
                        </button>
                        <button onclick="duplicateTemplate(${template.id})" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-sm hover:bg-yellow-200">
                            Duplicate
                        </button>
                    ` : ''}
                </div>
            </div>
        </div>
    `).join('');
}

function createTemplate() {
    currentTemplate = null;
    document.getElementById('modal-title').textContent = 'Create Template';
    document.getElementById('template-form').reset();
    document.getElementById('template-modal').classList.remove('hidden');
}

function editTemplate(id) {
    currentTemplate = templates.find(t => t.id === id);
    if (!currentTemplate) return;

    document.getElementById('modal-title').textContent = 'Edit Template';
    document.getElementById('template-name').value = currentTemplate.name;
    document.getElementById('template-description').value = currentTemplate.description || '';
    document.getElementById('is-default').checked = currentTemplate.is_default;

    // Set configuration values
    const configs = ['header_config', 'footer_config', 'item_config', 'payment_config'];
    configs.forEach(configType => {
        const config = currentTemplate[configType] || {};
        Object.keys(config).forEach(key => {
            const element = document.querySelector(`[name="${configType}[${key}]"]`);
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = config[key];
                } else {
                    element.value = config[key];
                }
            }
        });
    });

    document.getElementById('template-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('template-modal').classList.add('hidden');
    currentTemplate = null;
}

async function previewTemplate(id) {
    try {
        const response = await fetch(`/api/bill-templates/${id}/preview`);
        const data = await response.json();
        
        // Open preview in new window
        const previewWindow = window.open('', '_blank', 'width=800,height=600');
        previewWindow.document.write(data.html);
        previewWindow.document.close();
    } catch (error) {
        console.error('Error previewing template:', error);
        alert('Error loading preview');
    }
}

async function setDefault(id) {
    try {
        await fetch(`/api/bill-templates/${id}/set-default`, { method: 'POST' });
        await loadTemplates();
    } catch (error) {
        console.error('Error setting default template:', error);
    }
}

async function duplicateTemplate(id) {
    try {
        await fetch(`/api/bill-templates/${id}/duplicate`, { method: 'POST' });
        await loadTemplates();
    } catch (error) {
        console.error('Error duplicating template:', error);
    }
}

// Handle form submission
document.getElementById('template-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        name: formData.get('name'),
        description: formData.get('description'),
        is_default: formData.get('is_default') === 'on',
        header_config: {},
        footer_config: {},
        item_config: {},
        payment_config: {}
    };

    // Collect configuration data
    ['header_config', 'footer_config', 'item_config', 'payment_config'].forEach(configType => {
        formData.forEach((value, key) => {
            if (key.startsWith(configType)) {
                const configKey = key.replace(`${configType}[`, '').replace(']', '');
                data[configType][configKey] = value === 'on' ? true : value;
            }
        });
    });

    try {
        const url = currentTemplate ? `/api/bill-templates/${currentTemplate.id}` : '/api/bill-templates';
        const method = currentTemplate ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            closeModal();
            await loadTemplates();
        } else {
            const error = await response.json();
            alert('Error saving template: ' + (error.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error saving template:', error);
        alert('Error saving template');
    }
});
</script>
@endsection
