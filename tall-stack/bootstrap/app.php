<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',  // Enable API routes
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust Traefik reverse proxy (Docker internal subnet 172.16.0.0/12)
        // Using specific subnet instead of '*' to prevent IP spoofing via X-Forwarded-For
        $middleware->trustProxies(at: '172.16.0.0/12,10.0.0.0/8');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
