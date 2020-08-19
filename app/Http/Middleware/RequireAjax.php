<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
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
        if (App::environment() === 'production' && !$request->ajax()) {
            throw new AccessDeniedHttpException();
        }

        return $next($request);
    }
}
