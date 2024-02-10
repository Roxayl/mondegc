<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Middleware;

use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, ?string $guard = null): mixed
    {
        if (auth()->guard($guard)->check()) {
            return redirect('/home');
        }

        return $next($request);
    }
}
