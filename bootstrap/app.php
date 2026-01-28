<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
->withMiddleware(function (Middleware $middleware): void {

    // WEB (stack base + Inertia)
    $middleware->web(append: [
        \App\Http\Middleware\HandleInertiaRequests::class,
        \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
    ]);

    // API (Sanctum SPA)
    $middleware->api(append: [
        EnsureFrontendRequestsAreStateful::class,
    ]);

    // Middleware aliases (equivalente ao Kernel.php antigo)
    $middleware->alias([
        'auth'  => \Illuminate\Auth\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    ]);
})




    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
