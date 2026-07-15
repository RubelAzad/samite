<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireCompanyFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $company = auth()->user()?->company;

        if ($company && ! $company->hasFeature($feature)) {
            abort(403, 'This feature is not included in your current subscription plan.');
        }

        return $next($request);
    }
}
