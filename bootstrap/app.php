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
    ->withMiddleware(function (Middleware $middleware) {
        // alias custom middleware di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // contoh lain (opsional):
        // $middleware->append(\App\Http\Middleware\SomeGlobalMiddleware::class);
        // $middleware->group('web', [ ... ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
