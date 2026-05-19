<?php

use App\Http\Middleware\SeoMiddleware;
use App\Http\Middleware\UtmTrackingMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use MoonShine\Laravel\Http\Middleware\Authenticate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'moonshine', Authenticate::class])
                ->group(base_path('routes/moonshine.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'seo' => SeoMiddleware::class,
            'utm.tracking' => UtmTrackingMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'laravel-filemanager/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
