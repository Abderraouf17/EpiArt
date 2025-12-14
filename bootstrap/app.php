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
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'customer' => \App\Http\Middleware\IsCustomer::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), '[2002]') || str_contains($e->getMessage(), 'Connection refused')) {
                return response()->view('errors.db_error', [], 503);
            }
        });
        $exceptions->render(function (\PDOException $e) {
            if (str_contains($e->getMessage(), '[2002]') || str_contains($e->getMessage(), 'Connection refused')) {
                return response()->view('errors.db_error', [], 503);
            }
        });
    })->create();
