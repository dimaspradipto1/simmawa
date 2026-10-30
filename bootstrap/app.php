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
            'checkrole' => \App\Http\Middleware\Checkrole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            $message = 'Ukuran file yang diunggah terlalu besar melebihi batas kapasitas server. Silakan pilih atau kompres file dengan ukuran yang lebih kecil (maksimal 2 MB).';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 413);
            }

            if (class_exists(\RealRashid\SweetAlert\Facades\Alert::class)) {
                \RealRashid\SweetAlert\Facades\Alert::warning('Ukuran File Terlalu Besar', $message);
            }

            return back()->with('error', $message)->withErrors([
                'logo'             => $message,
                'file'             => $message,
                'photo_profile'    => $message,
                'background_card'  => $message,
            ]);
        });
    })->create();
