<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SalesOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user() || !auth()->user()->sales) {
            return response()->view('errors.access-denied', [
                'message' => 'Access denied. Sales only area.'
            ], 403);
        }

        return $next($request);
    }
}