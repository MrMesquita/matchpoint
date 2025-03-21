<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Illuminate\Validation\ValidationException;
use Throwable;

function handleExceptions(Exceptions $exceptions)
{
    return $exceptions->renderable(function (NotFoundHttpException $e) {
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
    })->renderable(function (AdminNotFoundException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (ArenaNotFoundException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (CustomerNotFoundException $e) {
        return error_response($e->getMessage(), null, $e->getCode());
    })->renderable(function (CourtNotFoundException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (CourtTimetableNotFoundException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (ReservationNotFoundException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_NOT_FOUND);
    })->renderable(function (ReservationCanceledException $e) {
        return error_response($e->getMessage(), null, Response::HTTP_CONFLICT);
    })->renderable(function (Throwable $e) {
        return error_response($e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
    });
}
