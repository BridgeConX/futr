<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class AcceptsJsonIsRequired
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('futr.strict') && ! $request->wantsJson()) {
            return response()
              ->json([
                'status'  => 'error',
                'message' => 'Header [Accept] missing or invalid. Please set it to [application/json].',
              ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $next($request);
    }
}
