<?php

use App\Exceptions\ApiException;
use App\Http\Middleware\AssignRequestId;
use App\Support\Responses\ApiErrorResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(append: [
            AssignRequestId::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport([
            ValidationException::class,
            AuthenticationException::class,
            AuthorizationException::class,
            ModelNotFoundException::class,
            NotFoundHttpException::class,
            MethodNotAllowedHttpException::class,
            TooManyRequestsHttpException::class,
            ApiException::class,
        ]);

        $exceptions->render(function (ApiException $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiErrorResponse::make(
                title: $e->title(),
                message: $e->getMessage(),
                status: $e->status(),
                errors: $e->errors(),
                meta: $e->meta()
            );
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiErrorResponse::make(
                title: 'Validation Failed',
                message: 'The given data was invalid.',
                status: 422,
                errors: $e->errors()
            );
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiErrorResponse::make(
                title: 'Unauthenticated',
                message: 'Authentication is required to access this resource.',
                status: 401
            );
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiErrorResponse::make(
                title: 'Forbidden',
                message: 'You are not allowed to perform this action.',
                status: 403
            );
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiErrorResponse::make(
                title: 'Resource Not Found',
                message: 'The requested resource does not exist.',
                status: 404
            );
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiErrorResponse::make(
                title: 'Route Not Found',
                message: 'The requested endpoint does not exist.',
                status: 404
            );
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiErrorResponse::make(
                title: 'Method Not Allowed',
                message: 'The HTTP method is not allowed for this endpoint.',
                status: 405
            );
        });

        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiErrorResponse::make(
                title: 'Too Many Requests',
                message: 'Too many attempts. Please try again later.',
                status: 429
            );
        });

        $exceptions->render(function (HttpException $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            $status = $e->getStatusCode();

            return ApiErrorResponse::make(
                title: 'HTTP Error',
                message: $e->getMessage() ?: 'An HTTP error occurred.',
                status: $status > 0 ? $status : 500
            );
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            $meta = [];

            if (config('app.debug')) {
                $meta['debug'] = [
                    'exception' => $e::class,
                    'message'   => $e->getMessage(),
                    'file'      => $e->getFile(),
                    'line'      => $e->getLine(),
                ];
            }

            return ApiErrorResponse::make(
                title: 'Internal Server Error',
                message: 'An unexpected error occurred while processing the request.',
                status: 500,
                meta: $meta
            );
        });
    })
    ->create();
