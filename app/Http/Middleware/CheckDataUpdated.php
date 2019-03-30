<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckDataUpdated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::guard()->user()->updated_data) {
            return redirect('activarse-paso-1');
        }

        return $next($request);
    }
}
