<?php

namespace App\Http\Middleware;

use Closure;

class SynchronizeAuthentication
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

        if(session()->has('userLegacyId') && auth()->guest()) {
            $userLegacyId = session('userLegacyId');
            auth()->loginUsingId($userLegacyId);
        }
        else if(!session()->has('userLegacyId')) {
            auth()->logout();
        }

        return $next($request);

    }
}
