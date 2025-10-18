<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BillTemplate;
use App\Models\Account;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    /**
     * Get the current tenant with fallback mechanisms
     */
    private function getTenant()
    {
        // Try to get tenant from service container
        if (app()->bound('tenant')) {
            return app('tenant');
        } elseif (app()->bound('tenant.model')) {
            return app('tenant.model');
        } else {
            // Fallback: get tenant from route parameter
            $tenantSlug = request()->route('tenant');
            if ($tenantSlug) {
                return Account::where('slug', $tenantSlug)->first();
            }
        }
        return null;
    }
    public function index()
    {
        try {
            $tenant = $this->getTenant();
            
            if (!$tenant) {
                \Log::error('SettingsController: Tenant not found', [
                    'tenant_slug' => request()->route('tenant'),
                    'app_bound_tenant' => app()->bound('tenant'),
                    'app_bound_tenant_model' => app()->bound('tenant.model')
                ]);
                abort(404, 'Tenant not found');
            }
            
            \Log::info('SettingsController: Loading settings', [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'tenant_slug' => $tenant->slug
            ]);
            
            $outlets = Outlet::where('tenant_id', $tenant->id)->get();
            $billTemplate = BillTemplate::where('tenant_id', $tenant->id)
                ->where('is_default', true)
                ->where('is_active', true)
                ->first();

            return view('tenant.settings', compact('tenant', 'outlets', 'billTemplate'));
        } catch (\Exception $e) {
            \Log::error('SettingsController: Error in index method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateGeneral(Request $request)
    {
        try {
            \Log::info('SettingsController: updateGeneral called', [
                'request_data' => $request->all(),
                'tenant_id' => $this->getTenant()?->id
            ]);

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'description' => 'nullable|string|max:1000',
                'website' => 'nullable|url|max:255',
                'industry' => 'nullable|string|max:100',
                'company_size' => 'nullable|string|max:50',
                'founded_year' => 'nullable|string|max:4',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'contact_info' => 'nullable|array',
                'business_hours' => 'nullable|array',
                'social_media' => 'nullable|array',
                'tax_settings' => 'nullable|array',
                'notification_settings' => 'nullable|array',
            ]);

            \Log::info('SettingsController: Validation passed', ['validated_data' => $data]);

        $tenant = $this->getTenant();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('logos', 'public');
            $data['logo_path'] = $logoPath;
            $data['logo_url'] = Storage::url($logoPath);
        }
        
        \Log::info('SettingsController: About to update tenant', [
                'tenant_id' => $tenant->id,
                'data_to_update' => $data
            ]);

            $tenant->update($data);

            \Log::info('SettingsController: Tenant updated successfully', [
                'tenant_id' => $tenant->id
            ]);

            return response()->json(['message' => 'Organization settings updated successfully']);

        } catch (\Exception $e) {
            \Log::error('SettingsController: Error in updateGeneral', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'An error occurred while updating settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle redirects from old tenant slugs
     */
    public function handleOldSlugRedirect($oldSlug)
    {
        // Find tenant by current slug first
        $tenant = Account::where('slug', $oldSlug)->first();
        
        if (!$tenant) {
            // Check if this is an old slug in the settings
            $tenant = Account::whereJsonContains('settings->previous_slugs', [
                ['slug' => $oldSlug]
            ])->first();
        }
        
        if ($tenant) {
            // Redirect to current slug
            return redirect()->route('tenant.settings', ['tenant' => $tenant->slug], 301);
        }
        
        // If no tenant found, return 404
        abort(404, 'Tenant not found');
    }

    public function storeOutlet(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
        ]);

        $data['tenant_id'] = app('tenant')->id;
        
        // Convert address string to array format
        $data['address'] = [
            'street' => $data['address'],
            'city' => '',
            'state' => '',
            'zip' => ''
        ];
        
        $outlet = Outlet::create($data);

        return response()->json(['message' => 'Outlet created successfully', 'outlet' => $outlet]);
    }

    public function destroyOutlet(Outlet $outlet)
    {
        // Ensure the outlet belongs to the current tenant
        if ($outlet->tenant_id !== app('tenant')->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $outlet->delete();

        return response()->json(['message' => 'Outlet deleted successfully']);
    }

    public function getBillFormat()
    {
        $template = BillTemplate::where('tenant_id', app('tenant')->id)
            ->where('is_default', true)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return response()->json([
                'header_config' => [],
                'footer_config' => [],
                'item_config' => [],
                'payment_config' => []
            ]);
        }

        return response()->json([
            'header_config' => $template->header_config ?? [],
            'footer_config' => $template->footer_config ?? [],
            'item_config' => $template->item_config ?? [],
            'payment_config' => $template->payment_config ?? []
        ]);
    }

    public function updateBillFormat(Request $request)
    {
        $data = $request->validate([
            'template_config' => 'nullable|array',
            'header_config' => 'nullable|array',
            'footer_config' => 'nullable|array',
            'item_config' => 'nullable|array',
            'payment_config' => 'nullable|array',
        ]);

        // Get or create default template
        $template = BillTemplate::where('tenant_id', app('tenant')->id)
            ->where('is_default', true)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            $template = BillTemplate::create([
                'tenant_id' => app('tenant')->id,
                'name' => 'Default Template',
                'slug' => 'default-template',
                'description' => 'Default bill template',
                'is_default' => true,
                'is_active' => true,
            ]);
        }

        $template->update($data);

        return response()->json(['message' => 'Bill format settings updated successfully']);
    }

    public function updateOutlet(Request $request, Outlet $outlet)
    {
        // Ensure the outlet belongs to the current tenant
        if ($outlet->tenant_id !== app('tenant')->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'code' => 'nullable|string|max:10',
            'gstin' => 'nullable|string|max:15',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Convert address string to array format
        $data['address'] = [
            'street' => $data['address'],
            'city' => '',
            'state' => '',
            'zip' => ''
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('outlet-logos', 'public');
            $data['logo_path'] = $logoPath;
            $data['logo_url'] = Storage::url($logoPath);
        }

        $outlet->update($data);

        return response()->json(['message' => 'Outlet updated successfully', 'outlet' => $outlet]);
    }

    public function previewBillFormat(Request $request)
    {
        $template = BillTemplate::where('tenant_id', app('tenant')->id)
            ->where('is_default', true)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            $template = new BillTemplate();
            $template->setRawAttributes([
                'template_config' => $template->getDefaultTemplateConfig(),
                'header_config' => $template->getDefaultHeaderConfig(),
                'footer_config' => $template->getDefaultFooterConfig(),
                'item_config' => $template->getDefaultItemConfig(),
                'payment_config' => $template->getDefaultPaymentConfig(),
            ]);
        }

        // Apply any temporary changes from request
        if ($request->has('template_config')) {
            $template->template_config = array_merge($template->getDefaultTemplateConfig(), $request->template_config);
        }
        if ($request->has('header_config')) {
            $template->header_config = array_merge($template->getDefaultHeaderConfig(), $request->header_config);
        }
        if ($request->has('footer_config')) {
            $template->footer_config = array_merge($template->getDefaultFooterConfig(), $request->footer_config);
        }
        if ($request->has('item_config')) {
            $template->item_config = array_merge($template->getDefaultItemConfig(), $request->item_config);
        }
        if ($request->has('payment_config')) {
            $template->payment_config = array_merge($template->getDefaultPaymentConfig(), $request->payment_config);
        }

        $config = $template->getMergedConfig();
        $sampleBill = $this->generateSampleBill();

        $html = view('prints.bill-template', [
            'bill' => $sampleBill,
            'template' => $template,
            'config' => $config
        ])->render();

        return response()->json(['html' => $html]);
    }

    protected function generateSampleBill()
    {
        $tenant = Account::find(app('tenant')->id);
        $outlet = Outlet::where('tenant_id', app('tenant')->id)->first();

        return (object) [
            'id' => 1,
            'invoice_no' => 'SAMPLE-001',
            'sub_total' => 150.00,
            'tax_total' => 18.00,
            'discount_total' => 10.00,
            'round_off' => 0.00,
            'net_total' => 158.00,
            'created_at' => now(),
            'tenant' => $tenant,
            'outlet' => $outlet,
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