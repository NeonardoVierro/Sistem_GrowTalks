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
        // Middleware untuk handle session names berbeda per guard
        $middleware->alias([
            'guard.internal' => \App\Http\Middleware\SeparateGuardSessions::class . ':internal',
            'guard.web' => \App\Http\Middleware\SeparateGuardSessions::class . ':web',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
