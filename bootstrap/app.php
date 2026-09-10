<?php

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
        // Public website middleware customisation will be added as Stage 2 requires it.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Central exception reporting hooks will be added with production observability.
    })->create();
