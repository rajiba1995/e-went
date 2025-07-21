<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\SanctumAuthMiddleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register global or aliased middleware
        $middleware->alias([
            'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
            'auth.sanctum.custom' => SanctumAuthMiddleware::class,
            'auth.sanctum' => EnsureFrontendRequestsAreStateful::class,

            //  middleware
            'check.permission' => \App\Http\Middleware\CheckPermission::class,
            'admin.maintenance' => \App\Http\Middleware\AdminUnderMaintenance::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
