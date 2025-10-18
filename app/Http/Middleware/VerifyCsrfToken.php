<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'rebootchai/api/public/*',
        'api/rebootchai/api/public/*',
    ];
    
    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $uri = $request->path();
        \Log::info('CSRF Check', ['uri' => $uri, 'except' => $this->except]);
        
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                \Log::info('CSRF Exempted', ['uri' => $uri, 'pattern' => $except]);
                return true;
            }
        }

        return false;
    }
}
