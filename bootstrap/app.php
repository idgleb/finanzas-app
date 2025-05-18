<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Redirector;
use Illuminate\Foundation\Configuration\Exceptions as ExceptionConfig;
use Illuminate\Foundation\Configuration\Middleware as MiddlewareConfig;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (ExceptionConfig $exceptions) {
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            // Si NO es petición AJAX o JSON…
            if (! $request->expectsJson()) {
                /** @var Redirector $redirect */
                $redirect = app(Redirector::class);

                return $redirect
                    ->guest(route('login'))
                    ->with('error', 'Debes iniciar sesión para acceder a esa página.');
            }
            // Para JSON, deja que el framework responda con 401 automáticamente
        });
    })
    ->create();
