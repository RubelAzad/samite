<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->isSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            if (! auth()->check()) {
                return redirect()->route('login');
            }

            return auth()->user()->isAdmin()
                ? redirect()->route('admin.dashboard')->with('error', 'Access denied. Super admin only.')
                : redirect()->route('member.dashboard')->with('error', 'Access denied. Super admin only.');
        }

        return $next($request);
    }
}
