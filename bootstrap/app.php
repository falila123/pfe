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

        // ici on ajoutera le middleware role plus tard
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Force le changement de mot de passe à la 1ʳᵉ connexion (toutes les pages web)
        $middleware->web(append: [
            \App\Http\Middleware\ForcePasswordChange::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();