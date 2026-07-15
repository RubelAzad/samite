<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\MemberMiddleware;
use App\Http\Middleware\RequireCompanyFeature;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'member' => MemberMiddleware::class,
            'superadmin' => SuperAdminMiddleware::class,
            'feature' => RequireCompanyFeature::class,
        ]);

        $middleware->redirectTo(
            users: function () {
                if (! auth()->check()) {
                    return route('login');
                }
                if (auth()->user()->isSuperAdmin()) {
                    return route('super-admin.dashboard');
                }

                return auth()->user()->isAdmin()
                    ? route('admin.dashboard')
                    : route('member.dashboard');
            },
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
