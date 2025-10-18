<?php

namespace App\Http\Middleware;

use App\Models\Account;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // \Log::info('ResolveTenant middleware started', [
        //     'url' => $request->fullUrl(),
        //     'path' => $request->getPathInfo(),
        //     'host' => $request->getHost(),
        //     'method' => $request->method(),
        //     'is_api' => $request->is('*/api/*'),
        //     'headers' => $request->headers->all()
        // ]);

        $tenant = null;
        
        // Check for subdomain first (paid users)
        $host = $request->getHost();
        $subdomain = $this->extractSubdomain($host);
        
        \Log::info('Subdomain check', [
            'host' => $host,
            'subdomain' => $subdomain,
            'is_www' => $subdomain === 'www'
        ]);
        
        if ($subdomain && $subdomain !== 'www') {
            $tenant = Account::where('slug', $subdomain)->first();
            
            // If not found by current slug, check if it's an old slug
            if (!$tenant) {
                $tenant = Account::whereJsonContains('settings->previous_slugs', [
                    ['slug' => $subdomain]
                ])->first();
                
                if ($tenant) {
                    \Log::info('Old slug detected, redirecting to current slug', [
                        'old_slug' => $subdomain,
                        'current_slug' => $tenant->slug,
                        'tenant_id' => $tenant->id
                    ]);
                    
                    // Redirect to current slug
                    return redirect()->to("http://{$tenant->slug}.localhost:8000" . $request->getPathInfo(), 301);
                }
            }
            
            \Log::info('Subdomain tenant lookup', [
                'subdomain' => $subdomain,
                'tenant_found' => $tenant ? true : false,
                'tenant_id' => $tenant ? $tenant->id : null,
                'tenant_plan' => $tenant ? $tenant->plan : null
            ]);
            
            if ($tenant) {
                // Verify this is a paid user (professional or enterprise)
                if (in_array($tenant->plan, ['professional', 'enterprise'])) {
                    \Log::info('Setting tenant context from subdomain', [
                        'tenant_id' => $tenant->id,
                        'tenant_slug' => $tenant->slug,
                        'tenant_plan' => $tenant->plan
                    ]);
                    $this->setTenantContext($tenant);
                    $this->maybeBindDeviceFromHeader($request);
                    return $next($request);
                } else {
                    // Free user trying to access subdomain, redirect to path-based URL
                    \Log::info('Free user accessing subdomain, redirecting', [
                        'tenant_slug' => $tenant->slug,
                        'tenant_plan' => $tenant->plan
                    ]);
                    return redirect()->to("http://localhost:8000/{$tenant->slug}/dashboard")
                        ->with('info', 'Your free plan uses path-based URLs. Upgrade to Professional for subdomain access.');
                }
            }
        }
        
        // Check for path-based tenant (free users)
        $pathSegments = explode('/', trim($request->getPathInfo(), '/'));
        \Log::info('Path segments analysis', [
            'path_info' => $request->getPathInfo(),
            'path_segments' => $pathSegments,
            'segment_count' => count($pathSegments)
        ]);
        
        if (count($pathSegments) >= 1 && $pathSegments[0] !== '') {
            $tenantSlug = $pathSegments[0];
            
            // Cache tenant lookup in memory for this request lifecycle
            $cacheKey = 'tenant_' . $tenantSlug;
            if (app()->bound($cacheKey)) {
                $tenant = app($cacheKey);
            } else {
                $tenant = Account::where('slug', $tenantSlug)->first();
                
                // If not found by current slug, check if it's an old slug
                if (!$tenant) {
                    $tenant = Account::whereJsonContains('settings->previous_slugs', [
                        ['slug' => $tenantSlug]
                    ])->first();
                    
                    if ($tenant) {
                        \Log::info('Old slug detected in path, redirecting to current slug', [
                            'old_slug' => $tenantSlug,
                            'current_slug' => $tenant->slug,
                            'tenant_id' => $tenant->id,
                            'current_path' => $request->getPathInfo()
                        ]);
                        
                        // Redirect to current slug with same path
                        $newPath = str_replace("/{$tenantSlug}/", "/{$tenant->slug}/", $request->getPathInfo());
                        return redirect()->to($request->getSchemeAndHttpHost() . $newPath, 301);
                    }
                }
                
                if ($tenant) {
                    app()->instance($cacheKey, $tenant);
                }
            }
            
            \Log::info('Path-based tenant lookup', [
                'tenant_slug' => $tenantSlug,
                'tenant_found' => $tenant ? true : false,
                'tenant_id' => $tenant ? $tenant->id : null,
                'tenant_plan' => $tenant ? $tenant->plan : null
            ]);
            
            if ($tenant) {
                // Verify this is a free user
                if ($tenant->plan === 'free') {
                    \Log::info('Setting tenant context from path', [
                        'tenant_id' => $tenant->id,
                        'tenant_slug' => $tenant->slug,
                        'tenant_plan' => $tenant->plan
                    ]);
                    $this->setTenantContext($tenant);
                    $this->maybeBindDeviceFromHeader($request);
                    
                    // \Log::info('ResolveTenant middleware completed - tenant context set', [
                    //     'tenant_id' => app('tenant.id'),
                    //     'tenant_bound' => app()->bound('tenant.id')
                    // ]);
                    
                    return $next($request);
                } else {
                    // Paid user accessing path-based URL, redirect to subdomain
                    \Log::info('Paid user accessing path-based URL, redirecting', [
                        'tenant_slug' => $tenant->slug,
                        'tenant_plan' => $tenant->plan
                    ]);
                    return redirect()->to("http://{$tenant->slug}.localhost:8000/dashboard")
                        ->with('info', 'Your paid plan includes subdomain access.');
                }
            }
        }
        
        // No tenant found, continue normally
        \Log::info('No tenant found, continuing without tenant context');
        $this->maybeBindDeviceFromHeader($request);
        return $next($request);
    }
    
    /**
     * Set tenant context in the application
     */
    private function setTenantContext($tenant)
    {
        try {
            app()->instance('tenant.id', $tenant->id);
            app()->instance('tenant.model', $tenant);
            app()->instance('tenant', $tenant);
            
            \Log::info('Tenant context set successfully', [
                'tenant_id' => $tenant->id,
                'tenant_slug' => $tenant->slug,
                'app_bound_tenant_id' => app()->bound('tenant.id'),
                'app_bound_tenant_model' => app()->bound('tenant.model'),
                'app_bound_tenant' => app()->bound('tenant')
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to set tenant context', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenant->id,
                'tenant_slug' => $tenant->slug
            ]);
            throw $e;
        }
    }

    /**
     * Optionally bind device from X-Device-Key header if present and tenant context is set
     */
    private function maybeBindDeviceFromHeader(Request $request): void
    {
        try {
            if (!app()->bound('tenant.id')) {
                return;
            }
            $apiKey = $request->header('X-Device-Key');
            if (!$apiKey) {
                return;
            }
            $device = \App\Models\Device::where('tenant_id', app('tenant.id'))
                ->where('api_key', $apiKey)
                ->first();
            if ($device) {
                app()->instance('device.id', $device->id);
                app()->instance('device.outlet_id', $device->outlet_id);
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to resolve device from header', [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Extract subdomain from host
     */
    private function extractSubdomain($host)
    {
        $parts = explode('.', $host);
        
        // For localhost.com or localhost:8000
        if (count($parts) >= 2) {
            return $parts[0];
        }
        
        return null;
    }
}
