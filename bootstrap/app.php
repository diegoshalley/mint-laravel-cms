<?php

use App\Http\Middleware\EnsureCmsAccess;
use App\Http\Middleware\EnsureMfaPassed;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [SecurityHeaders::class]);
        $middleware->alias([
            'cms.access' => EnsureCmsAccess::class,
            'mfa' => EnsureMfaPassed::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Reportable exceptions are configured here as integrations are added.
    })->create();
