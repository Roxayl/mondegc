<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestToken = $request->bearerToken();

        if($requestToken !== config('scribe.auth.use_value')) {
            return app(Authenticate::class)->handle(
                $request,
                function(Request $request) use ($next) {
                    return $next($request);
                },
                'api');
        }

        return $next($request);
    }
}
