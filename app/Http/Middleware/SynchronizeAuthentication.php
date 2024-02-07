<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SynchronizeAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('userLegacyId') && auth()->guest()) {
            $userLegacyId = session('userLegacyId');
            auth()->loginUsingId($userLegacyId);
        } elseif (!session()->has('userLegacyId')) {
            auth()->logout();
        }

        return $next($request);
    }
}
