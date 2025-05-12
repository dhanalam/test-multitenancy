<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\MessageBag;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // $domain = config('tenancy.central_domains')[0];
            Route::middleware([])
                ->group(base_path('routes/tenant.php'));
        },

    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->renderable(function (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], 500);
            }

            if ( $e instanceof \Illuminate\Database\QueryException) {
                return back()->withInput()->withErrors(['error' => $e->getMessage()]);
            }
        });

        $exceptions->renderable(function (Illuminate\Validation\ValidationException $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $e->validator->getMessageBag(),
                ], 422);
            }

            return back()->withInput()->withErrors($e->validator->getMessageBag());
        });

    })->create();
