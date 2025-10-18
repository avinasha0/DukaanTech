<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BillTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BillTemplateController extends Controller
{
    public function index()
    {
        $templates = BillTemplate::where('tenant_id', app('tenant.id'))
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();

        return response()->json($templates);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_config' => 'nullable|array',
            'header_config' => 'nullable|array',
            'footer_config' => 'nullable|array',
            'item_config' => 'nullable|array',
            'payment_config' => 'nullable|array',
            'is_default' => 'boolean',
        ]);

        $data['tenant_id'] = app('tenant.id');
        $data['slug'] = Str::slug($data['name']);

        // If this is set as default, unset other defaults
        if ($data['is_default'] ?? false) {
            BillTemplate::where('tenant_id', app('tenant.id'))
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $template = BillTemplate::create($data);

        return response()->json($template, 201);
    }

    public function show(BillTemplate $template)
    {
        return response()->json($template);
    }

    public function update(Request $request, BillTemplate $template)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'template_config' => 'nullable|array',
            'header_config' => 'nullable|array',
            'footer_config' => 'nullable|array',
            'item_config' => 'nullable|array',
            'payment_config' => 'nullable|array',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // If this is set as default, unset other defaults
        if ($data['is_default'] ?? false) {
            BillTemplate::where('tenant_id', app('tenant.id'))
                ->where('id', '!=', $template->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $template->update($data);

        return response()->json($template);
    }

    public function destroy(BillTemplate $template)
    {
        // Don't allow deletion of default template
        if ($template->is_default) {
            return response()->json(['error' => 'Cannot delete default template'], 400);
        }

        $template->update(['is_active' => false]);

        return response()->json(['message' => 'Template deactivated successfully']);
    }

    public function setDefault(BillTemplate $template)
    {
        // Unset current default
        BillTemplate::where('tenant_id', app('tenant.id'))
            ->where('is_default', true)
            ->update(['is_default' => false]);

        // Set new default
        $template->update(['is_default' => true]);

        return response()->json(['message' => 'Default template updated successfully']);
    }

    public function duplicate(BillTemplate $template)
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Copy)';
        $newTemplate->slug = Str::slug($newTemplate->name);
        $newTemplate->is_default = false;
        $newTemplate->save();

        return response()->json($newTemplate, 201);
    }

    public function preview(Request $request, BillTemplate $template)
    {
        // Generate a preview with sample data
        $sampleBill = $this->generateSampleBill();
        $config = $template->getMergedConfig();
        
        $html = view('prints.bill-template', [
            'bill' => $sampleBill,
            'template' => $template,
            'config' => $config
        ])->render();

        return response()->json(['html' => $html]);
    }

    protected function generateSampleBill()
    {
        // This would generate a sample bill for preview
        // For now, return a mock object
        return (object) [
            'id' => 1,
            'invoice_no' => 'SAMPLE-001',
            'sub_total' => 150.00,
            'tax_total' => 18.00,
            'discount_total' => 10.00,
            'round_off' => 0.00,
            'net_total' => 158.00,
            'created_at' => now(),
            'tenant' => (object) [
                'name' => 'Sample Restaurant',
                'phone' => '123-456-7890',
                'logo_url' => null,
            ],
            'outlet' => (object) [
                'address' => [
                    'street' => '123 Main Street',
                    'city' => 'Sample City',
                    'state' => 'Sample State',
                    'pincode' => '123456',
                ],
                'gstin' => '12ABCDE1234F1Z5',
            ],
            'order' => (object) [
                'orderType' => (object) ['name' => 'Dine In'],
                'customer_name' => 'John Doe',
                'customer_phone' => '9876543210',
                'table_no' => 'T-01',
                'items' => collect([
                    (object) [
                        'item' => (object) ['name' => 'Sample Item 1'],
                        'qty' => 2,
                        'price' => 50.00,
                        'tax_rate' => 12.0,
                        'discount' => 5.00,
                        'note' => 'Extra spicy',
                        'modifiers' => collect([
                            (object) [
                                'modifier' => (object) ['name' => 'Extra Cheese'],
                                'price' => 10.00,
                            ]
                        ]),
                    ],
                    (object) [
                        'item' => (object) ['name' => 'Sample Item 2'],
                        'qty' => 1,
                        'price' => 80.00,
                        'tax_rate' => 12.0,
                        'discount' => 5.00,
                        'note' => null,
                        'modifiers' => collect([]),
                    ],
                ]),
            ],
            'payments' => collect([
                (object) [
                    'method' => 'CASH',
                    'amount' => 200.00,
                    'ref' => null,
                ],
            ]),
            'paid_amount' => 200.00,
        ];
    }
}