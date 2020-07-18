<?php

namespace App\Http\Middleware;

use Closure;

class checkUserBlockedOrNot
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
        if (auth('web')->check()){
            if (auth('web')->user()->isBlocked){
                auth('web')->logout();
                return redirect()->to('/');
            }

        }
        return $next($request);
    }
}
