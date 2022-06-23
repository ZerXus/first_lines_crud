<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class forceJsonResponse
{
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}