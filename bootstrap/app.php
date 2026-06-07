<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->replace(
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \App\Http\Middleware\VerifyCsrfToken::class
        );

        $middleware->validateCsrfTokens(except: [
            'livewire/*',
            'logout',
        ]);

        $middleware->web(append: [
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->redirectGuestsTo(fn ($request) => $request->is('verifikasi/*')
            ? 'http://192.168.1.9:8000/portal/login'
            : route('login')
        );

        // Trust proxies for production environment
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            \Log::warning('CSRF Token Mismatch Details:', [
                'session_id' => $request->session()->getId(),
                'session_token' => $request->session()->token(),
                'request_token' => $request->input('_token') ?: $request->header('X-CSRF-TOKEN') ?: $request->header('X-XSRF-TOKEN'),
                'cookies' => $request->cookies->all(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
            ]);
            return back()->with('error', 'Sesi Anda telah berakhir atau token CSRF kedaluwarsa. Halaman telah dimuat ulang secara otomatis, silakan coba lagi.');
        });
    })->create();
