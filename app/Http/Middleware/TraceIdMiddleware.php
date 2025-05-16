<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TraceIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $traceId = $request->headers->get('X-Trace-Id') ?? (string)Str::uuid();

        Log::withContext(['traceId' => $traceId]);
        app()->instance('trace_id', $traceId);

        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $data = $request->except(['password', 'password_confirmation']);
        }

        Log::channel('slack-request')->info('Incoming request', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user' => auth()->user()?->email ?? 'guest',
            'trace_id' => $traceId,
            'body' => $data ?? "empty",
        ]);

        $response = $next($request);
        $response->headers->set('X-Trace-Id', $traceId);
        return $response;
    }
}
