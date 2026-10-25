<?php

use App\Http\Middleware\Role;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrasi Alias Middleware Role (Standar Arsitektur Laravel 12)
        $middleware->alias([
            // Alias utama dengan parameter dinamis (...$roles)
            'role' => RoleMiddleware::class,
            'check.role' => RoleMiddleware::class,

            // Alias spesifik per role (Poin 2 & 6: Minimal dua role sebagai alias)
            'admin_it' => RoleMiddleware::class.':admin_it,admin',
            'finance' => RoleMiddleware::class.':finance',
            'pimpinan' => RoleMiddleware::class.':pimpinan',
            'staff' => RoleMiddleware::class.':staff,user',

            // Alias dengan namespace role.*
            'role.admin_it' => RoleMiddleware::class.':admin_it,admin',
            'role.finance' => RoleMiddleware::class.':finance',
            'role.pimpinan' => RoleMiddleware::class.':pimpinan',
            'role.staff' => RoleMiddleware::class.':staff,user',

            // Alias legacy untuk backward compatibility
            'role.admin' => RoleMiddleware::class.':admin_it,admin',
            'role.user' => RoleMiddleware::class.':staff,user',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
