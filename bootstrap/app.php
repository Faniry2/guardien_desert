<?php

// bootstrap/app.php
// Exclure le webhook Stripe du CSRF
// doc Cashier : stripe/* doit être exclu

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // ← ajouter cette ligne
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // doc Cashier : exclure stripe/* du CSRF
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
        ]);
        // ✅ Redirection après login
        $middleware->redirectUsersTo('/espace/dashboard');

        // ✅ Redirection si déjà connecté (middleware guest)
        $middleware->redirectGuestsTo('/entre-dans-le-carnet');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();