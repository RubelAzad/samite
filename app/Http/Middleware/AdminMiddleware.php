<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            return auth()->check()
                ? redirect()->route('member.dashboard')->with('error', 'Access denied. Admin only.')
                : redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->company && ! $user->company->isActive()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Your company account is inactive. Contact support.');
        }

        return $next($request);
    }
}
