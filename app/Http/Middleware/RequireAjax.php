<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RequireAjax
{
    /**
     * Handle an incoming request.
     *
     * @param  Request   $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        if (App::environment() === 'production' && ! $request->ajax()) {
            abort(403);
        }

        return $next($request);
    }
}
