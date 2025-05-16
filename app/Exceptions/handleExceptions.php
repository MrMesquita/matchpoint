<?php

namespace App\Exceptions;

use App\Models\ErrorLog;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Illuminate\Validation\ValidationException;
use Throwable;

function handleExceptions(Exceptions $exceptions): Exceptions
{
    return $exceptions->renderable(function (NotFoundHttpException $e) {
        if ($e->getPrevious() instanceof ModelNotFoundException) {
            $modelException = $e->getPrevious();
            return error_response(
                class_basename($modelException->getModel()) . " not found",
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        return error_response('The requested URL does not match any valid resource.', null, Response::HTTP_NOT_FOUND);
    })->renderable(function (NotFoundResourceException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (MethodNotAllowedHttpException $e) {
        return error_response('The HTTP method used is not allowed for this resource.', null, Response::HTTP_METHOD_NOT_ALLOWED);
    })->renderable(function (ValidationException $e) {
        return error_response($e->getMessage(), $e->errors(), Response::HTTP_BAD_REQUEST);
    })->renderable(function (HttpException $e) {
        return error_response($e->getMessage(), null, $e->getStatusCode());
    })->renderable(function (AuthenticationException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_UNAUTHORIZED);
    })->renderable(function (UnauthorizedException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_UNAUTHORIZED);
    })->renderable(function (ModelNotFoundException $e) {
        return error_response($e->getMessage() . " a " . $e->getModel(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (ReservationCanceledException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_CONFLICT);
    })->renderable(function (UserNotFoundException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (Throwable $e) {
        $messageWithTraceId = "";
        if (config('app.env') == 'production') {
            Log::channel('slack-error')->critical($e->getMessage(), [
                'trace_id' => app('trace_id'),
                'exception' => (string)$e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => optional(auth()->user())->id,
                'url' => request()->fullUrl(),
                'ip' => request()->ip()
            ]);

            $messageWithTraceId = " trace_id= " . app('trace_id');
        }

        return error_response("An unknown error occurred! Please contact support." . $messageWithTraceId, null, Response::HTTP_INTERNAL_SERVER_ERROR);
    });
}
