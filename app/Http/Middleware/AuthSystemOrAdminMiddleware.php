<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthSystemOrAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return error_response("User doesn't authenticated", null, HttpResponse::HTTP_UNAUTHORIZED);
        }

        if (!$user->isSystem() && !$user->isAdmin()) {
            return error_response('Unauthorized', null, HttpResponse::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
