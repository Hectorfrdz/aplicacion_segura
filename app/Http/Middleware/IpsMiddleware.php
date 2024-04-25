<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IpsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role == 1 && $request->ip() == '192.10.0.1') {
            return $next($request);
        } else
        if (Auth::user()->role == 2) {
            return $next($request);
        }
        if (Auth::user()->role == 3 && $request->ip() != '192.10.0.1') {
            return $next($request);
        }
        return abort(403);
    }
}