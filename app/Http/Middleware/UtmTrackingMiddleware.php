<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UtmTrackingMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'] as $param) {
            if ($request->filled($param)) {
                session([$param => $request->string($param)->value()]);
            }
        }

        return $next($request);
    }
}
