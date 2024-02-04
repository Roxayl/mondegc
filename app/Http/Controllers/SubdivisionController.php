<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Http\Request;
use Roxayl\MondeGC\Models\Subdivision;
use YlsIdeas\FeatureFlags\Manager as FeatureManager;

class SubdivisionController extends Controller
{
    public function __construct(private readonly FeatureManager $featureManager)
    {
        $this->middleware(function(Request $request, \Closure $next) {
            if(! $this->featureManager->accessible('subdivision')) {
                abort(404);
            }

            return $next($request);
        });
    }

    public function create()
    {
        // TODO.
    }

    public function store(Request $request)
    {
        // TODO.
    }

    public function show(
        int $paysId,
        string $paysSlug,
        string $subdivisionTypeName,
        Subdivision $subdivision,
        string $subdivisionSlug
    ) {
        $routeParameter = $subdivision->showRouteParameter();

        if(
            $paysId !== $routeParameter['paysId']
            || $paysSlug !== $routeParameter['paysSlug']
            || $subdivisionTypeName !== $routeParameter['subdivisionTypeName']
            || $subdivisionSlug !== $routeParameter['subdivisionSlug']
        ) {
            return to_route('subdivision.show', $routeParameter);
        }

        if(! $this->featureEnabled($subdivision)) {
            abort(404);
        }

        return view('subdivision.show', compact('subdivision'));
    }

    public function edit($id)
    {
        // TODO.
    }

    public function update(Request $request, $id)
    {
        // TODO.
    }

    public function destroy($id)
    {
        // TODO.
    }

    /**
     * @param Subdivision $subdivision
     * @return bool
     */
    private function featureEnabled(Subdivision $subdivision): bool
    {
        return (bool) $subdivision->pays?->use_subdivisions;
    }
}
