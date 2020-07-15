<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RequireAjax
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
        if (config('app.env') === 'production' && !$request->ajax()) {
            throw new AccessDeniedHttpException();
        }

        return $next($request);
    }
}
