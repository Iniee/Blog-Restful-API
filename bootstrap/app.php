<?php

use App\Http\Middleware\TokenMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        if (env('APP_ENV') === 'production')
            $middleware->api(prepend: [
                \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            ]);

        $middleware->validateCsrfTokens(except: [
            'http://localhost:8000/*',
            'http://localhost/*',
            'http://127.0.0.1:8000/*',
            'http://127.0.0.1/*',
            'http://localhost:3000/*',
            'http://localhost/*',
            'http://127.0.0.1:3000/*',
            'http://127.0.0.1/*',
        ]);
        //
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'token' => TokenMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'status' => 'failed',
                    'message' =>  'Unauthenticated. Please login to proceed'
                ], Response::HTTP_UNAUTHORIZED);
            }

            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'status' => 'failed',
                    'message' =>  'Unauthorized'
                ], Response::HTTP_FORBIDDEN);
            }

            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => 'failed',
                    'message' =>  'Requested resource is not found'
                ], Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof ValidationException) {
                return response()->json([
                    'status' => 'failed',
                    'errors' => $e->validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return response()->json([
                'status' => 'failed',
                'message' =>  'We were unable to handle your request, please try again'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        });
    })->create();