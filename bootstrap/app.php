<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$base = dirname(__DIR__);
$loadMiddlewareClass = static function (string $class, array $relativePaths) use ($base): void {
    if (class_exists($class)) {
        return;
    }
    foreach ($relativePaths as $rel) {
        $path = $base.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $rel);
        if (is_file($path)) {
            require_once $path;

            return;
        }
    }
};

$loadMiddlewareClass('App\\Http\\Middleware\\CheckMaintenanceMode', [
    'app/Http/Middleware/CheckMaintenanceMode.php',
    'app/Http/CheckMaintenanceMode.php',
]);
$loadMiddlewareClass('App\\Http\\Middleware\\HandleInertiaRequests', [
    'app/Http/Middleware/HandleInertiaRequests.php',
    'app/Http/HandleInertiaRequests.php',
]);
$loadMiddlewareClass('App\\Http\\Middleware\\EnsureUserIsAdmin', [
    'app/Http/Middleware/EnsureUserIsAdmin.php',
    'app/Http/EnsureUserIsAdmin.php',
]);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: ['zynlepay/*']);

        $prepend = [];
        $maintenanceMiddleware = 'App\\Http\\Middleware\\CheckMaintenanceMode';
        if (class_exists($maintenanceMiddleware)) {
            $prepend[] = $maintenanceMiddleware;
        }

        $append = [
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ];
        if (class_exists(\App\Http\Middleware\HandleInertiaRequests::class)) {
            array_unshift($append, \App\Http\Middleware\HandleInertiaRequests::class);
        }

        $middleware->web(prepend: $prepend, append: $append);

        $aliases = [];
        if (class_exists(\App\Http\Middleware\EnsureUserIsAdmin::class)) {
            $aliases['admin'] = \App\Http\Middleware\EnsureUserIsAdmin::class;
        }
        if ($aliases !== []) {
            $middleware->alias($aliases);
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->header('X-Inertia') || str_contains($request->header('Accept', ''), 'text/html')) {
                return Inertia::render('Errors/NotFound', [])
                    ->toResponse($request)
                    ->setStatusCode(404);
            }
            return null;
        });
        // 419 CSRF / session expired: redirect back with message so Inertia shows it instead of error modal
        $exceptions->renderable(function (TokenMismatchException $e, Request $request) {
            return back()->with('error', 'Session expired. Please refresh the page and try again.');
        });
    })->create();
