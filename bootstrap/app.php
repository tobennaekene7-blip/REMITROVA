<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Railway terminates TLS at its edge and forwards plain HTTP to
        // the container. Without trusting the proxy headers, Laravel
        // thinks every request is http:// and route() builds http://
        // callback URLs (this is what Paga's callbackUrl was showing).
        $middleware->trustProxies(at: '*');
        $middleware->prepend(HandleCors::class);
        $middleware->validateCsrfTokens(except: [
            'api/webhooks/paga/persistent-account',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
