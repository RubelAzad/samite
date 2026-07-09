<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isMember()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return auth()->check()
                ? redirect()->route('admin.dashboard')->with('error', 'Access denied. Members only.')
                : redirect()->route('login');
        }

        if (auth()->user()->status === 'inactive') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is inactive. Contact admin.');
        }

        return $next($request);
    }
}
