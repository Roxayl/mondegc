<?php

namespace Roxayl\MondeGC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RequireAjax
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (App::environment() === 'production' && !$request->ajax()) {
            throw new AccessDeniedHttpException();
        }

        return $next($request);
    }
}
