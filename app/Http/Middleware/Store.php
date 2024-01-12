<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Store
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->city_id) {
            session([
                'city_id' => $request->city_id
            ]);
        }

        return $next($request);
    }
}
