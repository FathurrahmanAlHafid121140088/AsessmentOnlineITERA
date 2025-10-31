<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan middleware 'guest' di sini
        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);

        // ... middleware lain yang mungkin sudah ada
        $middleware->replace(
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\VerifyCsrfToken::class
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ⚡ CENTRALIZED ERROR HANDLING

        // 1. Handle Database Exceptions (Query errors, connection errors)
        $exceptions->renderable(function (\Illuminate\Database\QueryException $e, $request) {
            \Log::error('Database Query Error', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A',
                'url' => $request->fullUrl(),
                'user' => $request->user()?->nim ?? 'Guest',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Database error occurred',
                    'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada database.'
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan pada database. Silakan coba lagi.');
        });

        // 2. Handle Model Not Found (404 for Eloquent models)
        $exceptions->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            \Log::warning('Model Not Found', [
                'model' => $e->getModel(),
                'url' => $request->fullUrl(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Resource not found',
                    'message' => 'Data tidak ditemukan.'
                ], 404);
            }

            return redirect()->route('home')->with('error', 'Data yang Anda cari tidak ditemukan.');
        });

        // 3. Handle Authentication Exceptions
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => 'Silakan login terlebih dahulu.'
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        });

        // 4. Handle Authorization Exceptions (403)
        $exceptions->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            \Log::warning('Authorization Failed', [
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'user' => $request->user()?->nim ?? 'Guest',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Anda tidak memiliki akses.'
                ], 403);
            }

            return back()->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        });

        // 5. Handle Validation Exceptions (handled automatically by Laravel, but we can log it)
        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            \Log::info('Validation Failed', [
                'errors' => $e->errors(),
                'url' => $request->fullUrl(),
            ]);
            // Let Laravel handle validation errors naturally
            return null;
        });

        // 6. Handle General Exceptions (catch-all)
        $exceptions->renderable(function (\Throwable $e, $request) {
            // Don't handle HTTP exceptions (404, 403, etc.) - let Laravel handle them
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return null;
            }

            // Log all unhandled exceptions
            \Log::error('Unhandled Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request->fullUrl(),
                'user' => $request->user()?->nim ?? 'Guest',
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'Hidden in production',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Server Error',
                    'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada server. Silakan coba lagi.'
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        });
    })
    ->create();
